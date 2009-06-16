#!/bin/sh

# Muss im Repository-Root aufgerufen werden.

# Benötigt remind >= 03.01.07.


PREFIX='proj/ocalt'
WEB='drafts/homepage'
MONTHS=36

# Konstruiere Remind-Datei aus Event-Files.
for file in $(find events -name '_.event' | sort); do
	title="$(head -n 1 "$file" | sed -e 's/^Treffen$/oqlt-Treffen/')"
	place=''
	fplace=''
	[ "$title" = 'oqlt-Treffen' ] && place='FORUM'
	reminds="$(
	egrep -i '^((Rem|Datum|Ort|Wo):.*|[[:space:]]*)$' "$file" | sed -r -e 's/^([a-z]+):[ \t]*(.*)$/\1:\2/i' -e 's/[\t]/ /g' -e 's/ {2,}/ /g' | while read line; do
		if [ -z "$line" ]; then
			break
		fi
		type="$(echo "$line" | cut -d : -f 1 | tr a-z A-Z)"
		text="$(echo "$line" | cut -d : -f 2-)"
		if [ "$type" = 'DATUM' ]; then
			if echo "$text" | egrep -q '^[0-9]{4}-[0-9]{2}-[0-9]{2} bis [0-9]{4}-[0-9]{2}-[0-9]{2}$'; then
				text="$(echo "$text" | sed -r -e 's/ bis / *1 UNTIL /')"
			elif echo "$text" | egrep -q '^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{1,2}:[0-9]{2} bis [0-9]{1,2}:[0-9]{2}$'; then
				day="$(echo "$text" | cut -d ' ' -f 1)"
				time1="$(echo "$text" | cut -d ' ' -f 2)"
				time2="$(echo "$text" | cut -d ' ' -f 4 | sed -e 's/^24:00$/23:59/')"
				ts1="$(date -d "$day $time1" +%s)"
				ts2="$(date -d "$day $time2" +%s)"
				diff=$(($ts2 - $ts1))
				minutes=$(($diff / 60))
				hours=$(($minutes / 60))
				minutes=$(($minutes - ( $hours * 60 )))
				[ "$(expr length "$minutes")" -eq 1 ] && minutes="0$minutes"
				text="$day AT $time1 DURATION $hours:$minutes"
			else
				echo "Komisches Datum in $file: $text" >&2
				text=''
			fi
		elif [ "$type" = 'ORT' -o "$type" = 'WO' ]; then
			place="$text"
			continue
		fi
		if [ -z "$text" ]; then
			continue
		fi
		if [ -z "$fplace" ]; then
			[ -n "$place" ] && fplace=" ($place)"
		fi
		echo "REM $text MSG $title$fplace"
	done
	)"
	if [ -z "$reminds" ]; then
		continue
	fi
	lines="$(echo "$reminds" | wc -l)"
	if [ "$lines" -gt 1 ]; then
		reminds="$(echo "$reminds" | awk "{ print \$0 \" [\" NR \"/$lines]\" }")"
	fi
	echo "$reminds"
done > "$PREFIX/eventfiles.rem"

# Konstruiere Remind-Datei für zukünftige Events.
# Die Regel ist, dass an Tagen, an denen ein Event anliegt,
# kein „automatisches Treffen“ anberaumt wird. Findet trotzdem
# eins statt, muss eine Eventdatei dafür angelegt werden.
(
echo 'PUSH-OMIT-CONTEXT'
echo 'CLEAR-OMIT-CONTEXT'
remind -r "-s$MONTHS" "$PREFIX/eventfiles.rem" 2008 Jan 1 | sed -r -e 's#^([^ ]+).*#OMIT \1#'
echo "REM $(LC_ALL=C date -d 'Tue' '+%Y %b %d') *7 SKIP AT 18:00 DURATION 3:45 MSG voraussichtlich oqlt-Treffen (FORUM)"
echo 'POP-OMIT-CONTEXT'
) > "$PREFIX/zukunft.rem"

# Füge die generierten Dateien zusammen.
cat "$PREFIX/zukunft.rem" "$PREFIX/eventfiles.rem" > "$PREFIX/oqlt.rem"

# Generiere iCal-Dateien.
for opt in '' -norecur; do
	remind -r "-s$MONTHS" "$PREFIX/oqlt.rem" 2008 Jan 1 | HOSTNAME=oqlt.de TZ=Europe/Berlin proj/ocalt/rem2ics -do "$opt" > "$PREFIX/oqlt$opt.ics"
done

# Veröffentliche die Dateien auf der Website.
mv "$PREFIX/"oqlt{.rem,{,-norecur}.ics} "$WEB"
