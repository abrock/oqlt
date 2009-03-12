#!/bin/sh
# To be run in oqlt's git root.
remind -s36 events 1 Jan 2008 | HOSTNAME=oqlt.de events/rem2ics -do -norecur > drafts/homepage/oqlt.ics
