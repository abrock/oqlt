#!/bin/sh

for file in $(find -name '_.event'); do
	spec="$(egrep -m 1 '^Datum: [^ ]+' "$file" | cut -d ' ' -f 2-)"
	words="$(echo "$spec" | wc -w)"
	echo "$words: $spec"
done
