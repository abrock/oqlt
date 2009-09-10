#!/bin/sh

plotcmd() {
	lw=''
	[ "$1" = 'lw' ] && lw=" linewidth 3"
	echo "plot 'score.txt' using 1$lw, \\
	$(for n in $(seq 2 $(head -n 1 score.txt | wc -w)); do echo "'' using $n$lw, \\"; done | head -c -4)"
}

plot() {
	key='top left'
	datastyle='histogram'
	histstyle='rowstacked'
	plotparam=''
	rotate=0
	case "$1" in
	score)
		datastyle='lines'
		plotparam='lw'
		;;
	games)
		key="outside $key"
		;;
	sum)
		histstyle='columnstacked'
		rotate=90
		;;
	*)
		return 1
		;;
	esac
	gnuplot <<EOF
	set terminal svg
	set output "$1.svg"
	set key $key autotitle columnheader
	set grid
	set style data $datastyle
	set style histogram $histstyle
	set style fill solid border -1
	set boxwidth 0.8
	set xtics 1 format '' rotate by $rotate
	$(plotcmd $plotparam)
EOF
}

plot score
plot games
plot sum
