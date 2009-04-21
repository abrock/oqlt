<?php

# Erwartet als Argument eine Textdatei, in der Schrittfolgen stehen (rcall move(t|r)(r|l)). Erzeugt eine PNG-Datei mit dem erwarteten Bild.

if (!isset($_SERVER['argv'][1])) {
 die ('Please name the file you want to read from.'."\r\n");
}

$file = $_SERVER['argv'][1];

if (!file_exists($file)) {
 die('The file '.$file.' doesn\'t exist.'."\r\n");
}

if (!is_readable($file)) {
 die('The file '.$file.' isn\'t readable.'."\r\n");
}

$res = file ($file);

$res = array_map('trim', $res);
$res = array_map('strtolower', $res);

$res = array_merge($res, $res);

$points = array();

$x = $y = $xmin = $xmax = $ymin = $ymax = 0;

# Gehe die Zeilen der Datei durch, suche nach Steuerungsbefehlen und schreibe die resultierenden Punkte in ein Array.
# Ermittle Breite und Höhe des notwendigen Bildes.
foreach ($res as $line) {
 if ($line == 'rcall moverr') {
  $x++;
  $xmax = max($xmax, $x);
 }
 elseif ($line == 'rcall moverl') {
  $x--;
  $xmin = min($xmin, $x);
 }
 elseif ($line == 'rcall movetr') {
  $y++;
  $ymax = max($ymax, $y);
 }
 elseif ($line == 'rcall movetl') {
  $y--;
  $ymin = min($ymin, $y);
 }
 else {
  continue;
 }
 $points[] = array($x, $y);
}

$width = $xmax-$xmin+10;
$height = $ymax-$ymin+10;

$im = imagecreatetruecolor($width, $height);

$weis = imagecolorallocate($im, 255,255,255);
$schwarz = imagecolorallocate($im, 0,0,0);

imagefill($im, 0, 0, $weis);

foreach ($points as $point) {
 imagesetpixel($im, $point[0]-$xmin+5, $height-$point[1]+$ymin-5, $schwarz);
}

imagepng($im, $file.'.png');

?>