import datetime
import fileinput
import fnmatch
import os
import re
import sys

datere = re.compile('^Datum:\s+(.+)$')
dateparse = re.compile('^((\d{4})-(\d{2})-(\d{2}))?(\s+(\d{2}):(\d{2}))?$')
datesplit = re.compile('^([0-9\s:-]+)(?:\s+bis\s+([0-9\s:-]+))?$')

def warn(string):
		print >> sys.stderr, string

def parsedate(ds):
	match = datesplit.search(ds)
	if (match == None):
		return ('basic structure FAIL')
	frommatch = dateparse.search(match.group(1))
	if (frommatch == None):
		return ('"from" date invalid')
	tomatch = dateparse.search(match.group(2))
	return (datetime.datetime.now())
	fromdt = datetime.datetime(int(submatch.group(2)), int(submatch.group(3)), int(submatch.group(4)))
	return (fromdt)

def handlefile(fn):
	title = datestring = ''
	fi = fileinput.input(fn)
	for line in fi:
		line = line.strip()
		if (fi.filelineno() == 1):
			title = line
		match = datere.search(line)
		if (match != None):
			datestring = match.group(1).strip()
	if (datestring == ''):
		warn('File ' + fn + ' contains no date specification.')
	else:
		warn(fn)
		dt = parsedate(datestring)
		if (isinstance(dt, datetime.datetime)):
			print dt
		else:
			warn('File ' + fn + ' contains strange date specification (' + dt + '): ' + datestring)

for root, dirs, files in os.walk('.'):
	for file in fnmatch.filter(files, '_.event'):
		handlefile(os.path.join(root, file))
