#!/usr/bin/python
import sys

file = "oqlt-logo2.svg"

# size
height = width = 666
stroke_outer = 24
stroke_inner = 24

# color
color1 = "#000000"
color2 = "green"

# defaults
header = """<?xml version="1.0" standalone="yes"?>
<svg
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:cc="http://creativecommons.org/ns#"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:svg="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink"
	xmlns="http://www.w3.org/2000/svg"
	width="%s"
	height="%s"
>""" % (width, height)
style = """<style type="text/css">
<![CDATA[
* { fill:none; }
.main { stroke:%s; stroke-width:%s; }
.pentacle { stroke:%s; stroke-width:%s }
]]>
</style>""" % (color1, stroke_outer, color2, stroke_inner)
footer = """</svg>"""

def circle(cx, cy, r, style):
	circle = """<circle cx="%s" cy="%s" r="%s" class="%s" />""" % (cx, cy, r, style)
	return circle

def pentacle(width, height, offset, style):
	pentacle = """<path d="M %s %s L %s %s L %s %s L %s %s z" class="%s" />""" % (width/2, offset, offset, height/2, width/2, height-offset, width-offset, height/2, style)
	return pentacle

f = open(file, "w")
f.write(header + "\n")
f.write(style + "\n" )
# main circle
f.write(circle(width/2, height/2, (width-stroke_outer)/2, "main") + "\n")
# pentacle
f.write(pentacle(width, height, stroke_outer/2, "pentacle") + "\n")
f.write(footer)
f.close()

f = open(file, "r")
verbose = f.read()
f.close()
print verbose
