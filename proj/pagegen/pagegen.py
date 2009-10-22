#!/usr/bin/env python
# coding=utf-8

import os
import re

TARGET='htdocs-new'
TEMPLATE='proj/pagegen/template.html'

def expand(template, varname, value):
	return re.sub('<!-- \\$%s -->' % varname, value, template)

def selectindexfile(files):
	for file in ['_.txt', 'README']:
		if file in files:
			return (file, 'pre')
	return (None, 'listing')

template=file(TEMPLATE).read()

if not os.path.exists(TARGET):
	os.makedirs(TARGET)

for root, dirs, files in os.walk('.', topdown=True, followlinks=True):
	for ignore in ['.git', 'private', TARGET]:
		if ignore in dirs:
			dirs.remove(ignore)
	tdir = os.path.join(TARGET, root)
	if not os.path.exists(tdir):
		os.mkdir(tdir)
	list = "<ul class='listing'>\n"
	if root == '.':
		index = expand(template, 'path', 'index')
	else:
		list += "\t<li class='up'><a href='../_.html'>../</a></li>\n"
		index = expand(template, 'path', root[2:])
	ifile, format = selectindexfile(files)
	index = expand(index, 'format', format)
	if ifile:
		# files.remove(ifile)
		index = expand(index, 'body', file(os.path.join(root, ifile)).read())
	if dirs:
		list += "".join(["\t<li class='dir'><a href='%(dir)s/_.html'>%(dir)s/</a></li>\n" % { "dir": dir } for dir in dirs])
	if files:
		list += "".join(["\t<li class='file'><a href='%(file)s'>%(file)s</a></li>\n" % { "file": thefile } for thefile in files])
	list += "</ul>\n"
	index = expand(index, 'list', list)
	if not ifile:
		index = expand(index, 'body', list)
	for thefile in files:
		os.symlink(os.path.abspath(os.path.join(root, thefile)), os.path.join(tdir, thefile))
	out = file(os.path.join(tdir, '_.html'), 'w')
	out.write(index)
	out.close()
