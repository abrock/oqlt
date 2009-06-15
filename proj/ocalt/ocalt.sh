#!/bin/sh

for file in $(find -name '_.event' | sort); do
	title="$(head -n 1 "$file")"
	reminds="$(
	egrep -i '^((Rem|Datum):.*|[[:space:]]*)$' "$file" | sed -r -e 's/^([a-z]+):[ \t]*(.*)$/\1:\2/i' -e 's/[\t]/ /g' -e 's/ {2,}/ /g' | while read line; do
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
		fi
		if [ -z "$text" ]; then
			continue
		fi
		echo "REM $text MSG $title"
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
done
