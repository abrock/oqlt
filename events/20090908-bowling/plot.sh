#!/bin/sh

plotcmd="plot 'score.txt' using 1 linewidth 3, \\
$(for n in $(seq 2 $(head -n 1 score.txt | wc -w)); do echo "'' using $n linewidth 3, \\"; done | head -c -4)"

gnuplot <<EOF
set terminal svg
set output 'score.svg'
set key top left
set key autotitle columnheader
set style data lines
set xtics 1 format ''
$plotcmd
EOF
