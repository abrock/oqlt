<?php

// oqlt logo construction thingie.

// echo with newline.
function echol($text) {
	echo("$text\n");
}

// pentagram corner.
function pcorn($deg) {
	$deg = deg2rad($deg);
	$len = (W/2) - CSW - SW - PD;
	$x = (W/2) + ($len * cos($deg));
	$y = (W/2) + ($len * sin($deg));
	return ("$x,$y");
}

// given translate() transformation coords, returns the translated path coords
// great to remove inkscape's stupid translate() madness
// coords can be "12,34" or a complete path like "M 1,2 L 3,4 C 5,6,7 z".
function untranslate($x, $y, $coords) {
	return (preg_replace('/([0-9]+\.?[0-9]*),([0-9]+\.?[0-9]*)/e', "translate1($x, $y, '\\1', '\\2')", $coords));
}

// translate the coordinate $a,$b with $x,$y
function translate1($x, $y, $a, $b) {
	$a = (float)$a; $b = (float)$b;
	return (($a+$x).','.($b+$y));
}

// drawing mode.
define('MODE','onblack');

// width.
define('W', 666);

// penta stroke width.
define('SW', 9.5);

// circle stroke width.
define('CSW', SW);

// pentagram distance from circle.
define('PD', 1);

// color A (green)
define('A', '#ccff00');

// color B (white)
define('B', '#ffffff');

// the letters.
define('OQLT', '
	M 39.38644,0.06592 C 18.52829,0.06592 0,18.15042 0,38.45383 C 0,53.20986 11.64953,64.4156 26.84935,64.4156 C 47.7075,64.4156 66.45769,46.553 66.45769,26.69337 C 66.45769,11.27166 55.141,0.06592 39.38644,0.06592 M 36.83464,13.60154 C 45.15571,13.60154 51.25785,20.03652 51.25785,28.69043 C 51.25785,40.11804 40.9397,50.87998 29.84494,50.87998 C 21.41292,50.87998 15.19984,44.55595 15.19984,35.90203 C 15.19984,24.25254 25.51799,13.60154 36.83464,13.60154
	M 125.01895,1.44232 L 123.46568,9.31961 C 122.02337,6.43497 121.02483,5.32549 118.58398,3.66128 C 114.81177,1.22043 110.70669,0 105.49215,0 C 85.63253,0 67.88087,18.0845 67.88087,38.16602 C 67.88087,53.69868 78.19903,64.34968 93.2879,64.34968 C 100.83234,64.34968 108.1549,61.68693 113.48039,57.02713 L 108.59869,83.43269 L 123.35474,83.43269 L 138.55457,1.44232 L 125.01895,1.44232 M 104.82647,13.53562 C 113.48038,13.53562 119.24967,19.63776 119.24967,28.84641 C 119.24967,40.49591 109.15342,50.81406 97.72581,50.81406 C 88.73906,50.81406 83.08071,45.04477 83.08071,35.94706 C 83.08071,23.74283 93.06602,13.53562 104.82647,13.53562
	M 134.23682,83.39888 L 148.99287,83.39888 L 164.19271,1.40851 L 149.43666,1.40851 L 134.23682,83.39888
	M 162.31906,83.35811 L 177.07511,83.35811 L 185.95092,35.31774 L 194.82674,35.31774 L 197.37854,21.89307 L 188.50272,21.89307 L 192.27494,1.36774 L 177.5189,1.36774 L 173.74667,21.89307 L 166.53507,21.89307 L 163.98327,35.31774 L 171.19488,35.31774 L 162.31906,83.35811
	');

switch (MODE) {
	case 'onblack':
		define('BGVIS', 'visible');
		break;
	default:
		define('BGVIS', 'hidden');
		break;
}

$oqlt = array_map('trim', explode("\n", trim(OQLT)));

echol('<'.'?xml version="1.0" ?'.'>');

echol('<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   width="'.W.'"
   height="'.W.'"
>');

echol('<style type="text/css">
<![CDATA[
* { fill:none; }
#bgrect { fill:#000000; visibility:'.BGVIS.'; }
.penta { stroke:'.A.'; stroke-width:'.SW.'; stroke-linejoin:round; }
.circ { stroke:'.B.'; stroke-width:'.CSW.'; }
]]>
</style>');

// the background
echol('<rect x="0" y="0" width="'.W.'" height="'.W.'" id="bgrect" />');

// the circle
echol('<circle cx="'.(W/2).'" cy="'.(W/2).'" r="'.((W-CSW)/2).'" class="circ" />');

// the pentagram
$phi = 270;
$path = 'M ';
for ($i = 1; $i <= 5; $i++) {
	$path .= pcorn($phi).' ';
	if ($i == 1)
		$path .= 'L ';
	$phi = ($phi + (2*360) / 5) % 360;
}
$path .= 'z';
echol('<path d="'.$path.'" class="penta" />');

echol('<path d="'.$oqlt[3].'" class="penta" />');

echol('</svg>');

?>
