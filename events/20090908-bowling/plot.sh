#!/bin/sh

plot="plot $(while read line; do
	name="$(echo "$line" | cut -f 1)"
	echo "$line" | cut -f 2- | tr '\t' '\n' > "$name.dat"
	echo "'$name.dat' title '$name' with lines linewidth 3, \\"
done < score.txt | head -c -4)"

gnuplot <<EOF
set terminal svg
set output 'score.svg'
set key top left
set style data lines
set xtics 1 format ''
$plot
EOF

rm *.dat
