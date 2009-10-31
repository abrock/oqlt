#!/bin/sh

dir='./proj/pagegen'

if [ ! -e "$dir/mach.sh" ]; then
	echo 'Bitte aus dem Repository-Root aufrufen.' >&2
	exit 1
fi

rm -rf htdocs-new
"$dir/pagegen.py"
rsync -a --del htdocs-new htdocs
rm -rf htdocs-new
