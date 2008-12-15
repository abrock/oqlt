<?php

// oqlt logo construction thingie.

/* TODO:
 * Will break if oqltbb min values are != 0.
 * */

// echo with newline.
function echol($text) {
	echo("$text\n");
}

// pentagram corner.
function pcorn($deg) {
	$deg = deg2rad($deg);
	$len = (W/2) - CSW - (SW/2) - PD;
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

// calculate the bounding box of a path, not including bezier control points
function bounding($path) {
	$bound = array(array(null, null), array(null, null));
	preg_match_all('/([a-zA-Z])((?: *[0-9]+\.?[0-9]*,[0-9]+\.?[0-9]*)+)/', $path, $cmds, PREG_SET_ORDER);
	foreach ($cmds as $cmd) {
		$coords = array_map('trim', explode(' ', trim($cmd[2])));
		switch ($cmd[1]) {
		case 'M':
		case 'L':
			$bound = rebound($bound, $coords[0]);
			break;
		case 'C':
			$bound = rebound($bound, $coords[2]);
			break;
		}
	}
	return ($bound);
}

function rebound($bound, $coord) {
	$coord = explode(',', $coord);
	$x = (float)$coord[0]; $y = (float)$coord[1];
	// bound: ((xmin, ymin), (xmax, ymax))
	// echol("rebound: (".$bound[0][0].','.$bound[0][1].')-('.$bound[1][0].','.$bound[1][1].") with ($x,$y)");
	if ($bound[0][0] === null || $x < $bound[0][0])
		$bound[0][0] = $x;
	if ($bound[0][1] === null || $y < $bound[0][1])
		$bound[0][1] = $y;
	if ($bound[1][0] === null || $x > $bound[1][0])
		$bound[1][0] = $x;
	if ($bound[1][1] === null || $y > $bound[1][1])
		$bound[1][1] = $y;
	return ($bound);
}

function tly() {
	$tly = explode(',', pcorn(342));
	return ((float)$tly[1]);
}

function symboltrans($num, $width) {
	$num = (int)$num;
	if (($num < 0) || ($num > 4))
		return;
	$trans = 'translate('.((W-$width)/2).','.tly().')';
	if ($num > 0)
		$trans = 'rotate('.($num*72).','.(W/2).','.(W/2).") $trans";
	return ($trans);
}

// coordinates of center of corner #$nr
function cofc($nr) {
	$next = ($nr + 1) % 5;
	$corn[0] = explode(',', pcorn((270 + ($nr * 72)) % 360));
	$corn[1] = explode(',', pcorn((270 + ($next * 72)) % 360));
	for ($i = 0; $i < 2; $i++)
		for ($j = 0; $j < 2; $j++)
			$corn[$i][$j] = (float)$corn[$i][$j];
	return (($corn[0][0]+abs($corn[1][0]-$corn[0][0])).','.($corn[0][1]+abs($corn[1][1]-$corn[0][1])));
}

// base pentagram path for the corner at $d degrees
function bp($d) {
	return ('<path d="M '.pcorn($d).' m 0,-'.(SW/2).' ');
}

// drawing mode.
define('MODE','transparent');

// width.
define('W', 666);

// penta stroke width.
define('SW', 9.5);

// circle stroke width.
define('CSW', SW);

// symbol circle width.
define('SCW', 11);

// switch distance.
define('SD', 60);

// resistor length.
define('RL', 7*SW);

// cut for printing.
define('CUT', 1);

// pentagram distance from circle.
define('PD', CUT);

// the letters (the last one being "esse" for "oqltesse").
define('OQLT', '
	M 39.38644,0.06592 C 18.52829,0.06592 0,18.15042 0,38.45383 C 0,53.20986 11.64953,64.4156 26.84935,64.4156 C 47.7075,64.4156 66.45769,46.553 66.45769,26.69337 C 66.45769,11.27166 55.141,0.06592 39.38644,0.06592 M 36.83464,13.60154 C 45.15571,13.60154 51.25785,20.03652 51.25785,28.69043 C 51.25785,40.11804 40.9397,50.87998 29.84494,50.87998 C 21.41292,50.87998 15.19984,44.55595 15.19984,35.90203 C 15.19984,24.25254 25.51799,13.60154 36.83464,13.60154
	M 125.01895,1.44232 L 123.46568,9.31961 C 122.02337,6.43497 121.02483,5.32549 118.58398,3.66128 C 114.81177,1.22043 110.70669,0 105.49215,0 C 85.63253,0 67.88087,18.0845 67.88087,38.16602 C 67.88087,53.69868 78.19903,64.34968 93.2879,64.34968 C 100.83234,64.34968 108.1549,61.68693 113.48039,57.02713 L 108.59869,83.43269 L 123.35474,83.43269 L 138.55457,1.44232 L 125.01895,1.44232 M 104.82647,13.53562 C 113.48038,13.53562 119.24967,19.63776 119.24967,28.84641 C 119.24967,40.49591 109.15342,50.81406 97.72581,50.81406 C 88.73906,50.81406 83.08071,45.04477 83.08071,35.94706 C 83.08071,23.74283 93.06602,13.53562 104.82647,13.53562
	M 134.23682,83.39888 L 148.99287,83.39888 L 164.19271,1.40851 L 149.43666,1.40851 L 134.23682,83.39888
	M 162.31906,83.35811 L 177.07511,83.35811 L 185.95092,35.31774 L 194.82674,35.31774 L 197.37854,21.89307 L 188.50272,21.89307 L 192.27494,1.36774 L 177.5189,1.36774 L 173.74667,21.89307 L 166.53507,21.89307 L 163.98327,35.31774 L 171.19488,35.31774 L 162.31906,83.35811
	M 232.47984,19.008105037 C 211.98018,19.008105037 193.52356,37.042265037 193.52356,57.098685037 C 193.52356,72.501135037 205.11464,83.358105037 221.51437,83.358105037 C 235.36549,83.358105037 247.76224,75.924675037 254.41078,63.735685037 L 238.5397,63.735685037 C 234.32896,67.946425037 229.73962,69.795545037 223.53432,69.795545037 C 215.11284,69.795545037 209.10607,64.767595037 209.10607,57.675815037 L 256.7193,57.675815037 C 258.15982,54.240735037 258.73926,49.143525037 258.73926,43.824695037 C 258.73926,29.197915037 247.88228,19.008105037 232.47984,19.008105037 z M 292.78993,19.008105037 C 279.60366,19.008105037 268.2619,29.193295037 268.2619,40.939045037 C 268.2619,48.695675037 271.65312,52.176925037 282.40159,55.944425037 C 289.9366,58.603845037 291.3471,59.700395037 291.3471,62.581425037 C 291.3471,66.459735037 287.76659,69.795545037 283.55585,69.795545037 C 279.2343,69.795545037 276.7411,67.168455037 276.63029,62.292855037 L 261.91347,62.292855037 C 261.80266,63.954995037 261.62491,65.402435037 261.62491,66.621335037 C 261.62491,76.483335037 269.30073,83.358105037 280.38163,83.358105037 C 294.23275,83.358105037 306.64105,71.794725037 306.64105,58.830075037 C 306.64105,51.516685037 303.02821,47.836905037 292.50136,44.401825037 C 286.96091,42.628885037 287.19407,42.580405037 285.86437,41.804745037 C 284.53466,41.029075037 283.55585,39.849425037 283.55585,38.630525037 C 283.55585,35.417065037 286.4692,32.570665037 289.90428,32.570665037 C 293.00693,32.570665037 294.69907,34.262805037 294.80988,37.476265037 L 309.23813,37.476265037 C 309.45975,35.814135037 309.5267,34.255885037 309.5267,33.147795037 C 309.5267,25.501975037 301.87626,19.008105037 292.78993,19.008105037 z M 341.55742,19.008105037 C 328.37115,19.008105037 317.02939,29.193295037 317.02939,40.939045037 C 317.02939,48.695675037 320.13204,52.176925037 330.88051,55.944425037 C 338.41552,58.603845037 340.11459,59.700395037 340.11459,62.581425037 C 340.11459,66.459735037 336.53408,69.795545037 332.32334,69.795545037 C 328.00179,69.795545037 325.50858,67.168455037 325.39778,62.292855037 L 310.39239,62.292855037 C 310.28159,63.954995037 310.39239,65.402435037 310.39239,66.621335037 C 310.39239,76.483335037 318.06822,83.358105037 329.14912,83.358105037 C 343.00024,83.358105037 355.40854,71.794725037 355.40854,58.830075037 C 355.40854,51.516685037 351.7957,47.836905037 341.26885,44.401825037 C 335.7284,42.628885037 335.673,42.580405037 334.34329,41.804745037 C 333.01358,41.029075037 332.32334,39.849425037 332.32334,38.630525037 C 332.32334,35.417065037 335.23669,32.570665037 338.67177,32.570665037 C 341.77442,32.570665037 343.46656,34.262805037 343.57737,37.476265037 L 358.00562,37.476265037 C 358.22724,35.814135037 358.29419,34.255885037 358.29419,33.147795037 C 358.29419,25.501975037 350.64375,19.008105037 341.55742,19.008105037 z M 401.00181,19.008105037 C 380.50215,19.008105037 362.04553,37.042265037 362.04553,57.098685037 C 362.04553,72.501135037 373.34805,83.358105037 389.74778,83.358105037 C 403.5989,83.358105037 416.28421,75.924675037 422.93275,63.735685037 L 406.77311,63.735685037 C 402.56237,67.946425037 397.97303,69.795545037 391.76773,69.795545037 C 383.34625,69.795545037 377.62804,64.767595037 377.62804,57.675815037 L 424.95271,57.675815037 C 426.39322,54.240735037 427.26123,49.143525037 427.26123,43.824695037 C 427.26123,29.197915037 416.40426,19.008105037 401.00181,19.008105037 z M 230.17132,32.570665037 C 239.59008,32.570665037 244.73346,36.712145037 245.1767,44.690395037 L 211.12603,44.690395037 C 214.00706,37.487805037 221.63903,32.570665037 230.17132,32.570665037 z M 398.69329,32.570665037 C 408.11205,32.570665037 412.96687,36.712145037 413.41011,44.690395037 L 379.35943,44.690395037 C 382.24047,37.487805037 390.161,32.570665037 398.69329,32.570665037 z
	');

// whether the logo should be in "oqltesse" aka "girlfriend" mode.
define('ESSE', false);

// rounding factor of coil connectors (0 to 1).
define('CRF', ((ESSE)?(0.25):(0.35)));

// main color.
define('A', ((ESSE)?('#fc0fc0'):('#ccff00')));

switch (MODE) {
	case 'onblack':
		define('SHADOW', false);
		define('BGVIS', 'visible');
		define('BG', '#000000');
		define('B', '#ffffff');
		break;
	case 'onwhite':
		define('BGVIS', 'visible');
		define('SHADOW', true);
		define('BG', '#ffffff');
		define('B', '#000000');
		break;
	default:
		define('BGVIS', 'hidden');
		define('SHADOW', false);
		define('BG', '#BADA55');
		define('B', '#ffffff');
		break;
}

$oqlt = array_map('trim', explode("\n", trim(OQLT)));
$letters = array('o', 'q', 'l', 't', 'esse');

// calculate oqlt bounding box.
$oqltbb = bounding(implode(' ', array_slice($oqlt, 0, ((ESSE)?(5):(4)))));

echol('<'.'?xml version="1.0" ?'.'>');

echol('<svg
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:cc="http://creativecommons.org/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns:xlink="http://www.w3.org/1999/xlink"
   xmlns="http://www.w3.org/2000/svg"
   width="'.W.'"
   height="'.W.'"
>');

echol('<style type="text/css">
<![CDATA[
* { fill:none; }
#bgrect { fill:'.BG.'; visibility:'.BGVIS.'; }
#penta * { fill:'.A.'; }
#shadow * { fill:#cccccc; }
.symbol * { fill:'.B.'; }
.circ { stroke:'.B.'; stroke-width:'.CSW.'; }
.letter { fill:'.B.'; }
.q { fill:'.A.'; }
]]>
</style>');

echol('<clipPath id="pentaclip" clip-rule="evenodd">');
echol('<path d="M 0,0 l '.W.',0 0,'.W.' -'.W.',0 0,-'.W.' '.
	// cofc(1).rmov(1,-1.5*(SW+CUT),0).
	'" />');
echol('</clipPath>');

// the background
echol('<rect x="0" y="0" width="'.W.'" height="'.W.'" id="bgrect" />');

// the shadow?
if (SHADOW) {
	echol('<use xlink:href="#penta" transform="translate(3,4)" id="shadow" />');
}

echol('<g id="logo">');

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
// echol('<path d="'.$path.'" class="penta" clip-path="url(#pentaclip)" />');

$corn = explode(',', pcorn(198));
$len = (W/2)-(float)$corn[0];
echol('<g id="penta">');
for ($i = 0; $i < 5; $i++) {
	$corn = explode(',', pcorn((270+($i*72)%360)));
	echol('<circle cx="'.(float)$corn[0].'" cy="'.(float)$corn[1].'" r="'.(SW/2).'" />');
}
$away = (SD/2)+sqrt(pow(SCW+CUT,2)-pow(0.5*SW,2));
echol(bp(198).'l '.($len-$away).',0 a '.SCW.','.SCW.' 0 0,0 0,'.SW.' l -'.($len-$away).',0 z" />');
echol(bp(198).'l '.($len-$away).',0 a '.SCW.','.SCW.' 0 0,0 0,'.SW.' l -'.($len-$away).',0 z" transform="rotate(180,'.(W/2).','.tly().')" />');
echol(bp(342).'l '.($len-(RL/2)-CUT).',0 0,'.SW.' -'.($len-(RL/2)-CUT).',0 z" transform="rotate(144,'.pcorn(342).')" />');
echol(bp(126).'l '.($len-(RL/2)-CUT).',0 0,'.SW.' -'.($len-(RL/2)-CUT).',0 z" transform="rotate(324,'.pcorn(126).')" />');
echol(bp(126).'l '.($len-(2*SW)-CUT).',0 0,'.SW.' -'.($len-(2*SW)-CUT).',0 z" transform="rotate(288,'.pcorn(126).')" />');
echol(bp(270).'l '.($len-(2*SW)-CUT).',0 0,'.SW.' -'.($len-(2*SW)-CUT).',0 z" transform="rotate(108,'.pcorn(270).')" />');
echol(bp(270).'l '.($len-(1.5*SW)-CUT).',0 0,'.SW.' -'.($len-(1.5*SW)-CUT).',0 z" transform="rotate(72,'.pcorn(270).')" />');
echol(bp(54).'l '.($len-(1.5*SW)-CUT).',0 0,'.SW.' -'.($len-(1.5*SW)-CUT).',0 z" transform="rotate(252,'.pcorn(54).')" />');
echol(bp(54).'l '.($len-(5.5*SW)-CUT).',0 0,'.SW.' -'.($len-(5.5*SW)-CUT).',0 z" transform="rotate(216,'.pcorn(54).')" />');
echol(bp(198).'l '.($len-(5.5*SW)-CUT).',0 0,'.SW.' -'.($len-(5.5*SW)-CUT).',0 z" transform="rotate(36,'.pcorn(198).')" />');
echol('</g>');

// switch.
echol('<g class="symbol" id="switch" transform="'.symboltrans(0, SD).'">');
echol('    <circle cx="0" cy="0" r="'.SCW.'" />');
echol('    <circle cx="'.SD.'" cy="0" r="'.SCW.'" />');
echol('    <rect x="0" y="'.(0-(SW/2)).'" width="'.SD.'" height="'.SW.'" transform="rotate(38,'.SD.',0)" />');
echol('</g>');

// condensator.
echol('<g class="symbol condensator" transform="'.symboltrans(1,3*SW).'">');
echol('    <rect x="0" y="'.(0-(SW*2.5)).'" width="'.SW.'" height="'.(SW*5).'" />');
echol('    <rect x="'.(SW*2).'" y="'.(0-(SW*2.5)).'" width="'.SW.'" height="'.(SW*5).'" />');
echol('</g>');

// resistor.
echol('<g class="symbol resistor" transform="'.symboltrans(2,RL).'">');
echol('    <rect x="0" y="'.(-1.5*SW).'" width="'.RL.'" height="'.SW.'" />');
echol('    <rect x="0" y="'.(0.5*SW).'" width="'.RL.'" height="'.SW.'" />');
echol('    <rect x="0" y="'.(-1.5*SW).'" width="'.SW.'" height="'.(3*SW).'" />');
echol('    <rect x="'.(RL-SW).'" y="'.(-1.5*SW).'" width="'.SW.'" height="'.(3*SW).'" />');
echol('</g>');

// coil.
echol('<g class="symbol coil" transform="'.symboltrans(3,11*SW).'">');
echol('    <path d="M 0,'.(-0.5*SW).' l '.((1-CRF)*SW).',0 a '.(CRF*SW).','.(CRF*SW).' 0 0,1 '.(CRF*SW).','.(CRF*SW).' l 0,'.((1-CRF)*SW).' '.
	str_repeat('a '.(SW*0.75).','.(SW*0.75).' 0 0,0 '.(1.5*SW).',0 a '.(SW/2).','.(SW/2).' 0 0,1 '.SW.',0 ', 3).
	'a '.(SW*0.75).','.(SW*0.75).' 0 0,0 '.(1.5*SW).',0 l 0,'.(-(1-CRF)*SW).' a '.(CRF*SW).','.(CRF*SW).' 0 0,1 '.(CRF*SW).','.(-CRF*SW).' l '.((1-CRF)*SW).',0 l 0,'.(1*SW).
	str_repeat('a '.(SW*1.75).','.(SW*1.75).' 0 0,1 '.(-3.5*SW).',0 l '.SW.',0 ', 3).
	'a '.(SW*1.75).','.(SW*1.75).' 0 0,1 '.(-3.5*SW).',0 z'.
	'" />');
echol('</g>');

// diode.
echol('<g class="symbol diode" transform="'.symboltrans(4,4*SW).'">');
echol('    <rect x="'.(3*SW).'" y="'.(-2.5*SW).'" width="'.SW.'" height="'.(SW*5).'" />');
echol('    <path d="M 0,'.(-2.5*SW).' L '.(3.5*SW).',0 0,'.(2.5*SW).' z" />');
echol('</g>');

// the text (54° being the bottom-right pentagram corner)
$y = explode(',',pcorn(54));
$scale = ((ESSE)?(0.66):(1));
$y = (float)$y[1]-(($oqltbb[1][1])/(2/$scale));
echol('<g transform="translate('.((W/2)-(($oqltbb[1][0])/(2/$scale))).",$y) scale($scale)\">");
$max = ((ESSE)?(5):(4));
for ($i = 0; $i < $max; $i++) {
	echol('    <path d="'.$oqlt[$i].'" class="letter '.$letters[$i].'" />');
}
echol('</g>');

echol('</g>');

echol('</svg>');

?>
