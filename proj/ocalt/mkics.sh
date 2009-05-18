#!/bin/sh
# To be run in oqlt's git root.
remind -r -s36 proj/ocalt 1 Jan 2008 | HOSTNAME=oqlt.de proj/ocalt/rem2ics -do -norecur > drafts/homepage/oqlt.ics
