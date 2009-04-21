<?php

$t = new plot();
$t->readImage('kreis.png');
$t->start(190,50);

class plot {
 /**
  * Enthält das Bild, das verarbeitet werden soll.
  */
 var $im = '';
 
 var $points = array();
 
 var $breakpoints = array();
 
 var $schritte = array();
 
 /**
  * Schwelle, oberhalb derer nicht gemalt wird.
  */
 var $schwelle = 200;

 /**
  * Liest eine PNG-Datei ein.
  */
 function readImage($file) {
  if (!file_exists($file) or !is_readable($file)) {
   echo 'File '.$file.' couldn\'t be read.'."\r\n";
   return false;
  }
  $this->im = imageCreateFromPNG($file);
  if ($this->im == '') {
   echo 'File '.$file.' seems to be broken.'."\r\n";
   return false;
  }
  $size = getImageSize($file);
  $this->width = $size[0];
  $this->height = $size[1];
  return true;
 }
 
 /*
  * Beginnt bei gegebenen Koordinaten zu plotten
  */
 function start($startx, $starty) {
  $time = time();
  $currentx = $startx;
  $currenty = $starty;
  $this->points[] = array($startx, $starty);
  $i = 0;
  while (true) {
   $point = $this->getdarkest($currentx, $currenty);
   
   # Wenn die Schwelle überschritten wird, wird ein Punkt gesucht, an dem 
   # sinnvoll weitergemalt werden kann.
   $schrittezurueck = 0;
   while ($this->greyat($point[0], $point[1]) > $this->schwelle and $schrittezurueck < count($this->points)) {
    $schrittezurueck++;
    list($currentx, $currenty) = $this->points[count($this->points)-$schrittezurueck];
    $this->schritte[] = $this->points[count($this->points)-$schrittezurueck];
    $point = $this->getdarkest($currentx, $currenty);
   }
   
   $this->points[] = $point;
   $this->schritte[] = $point;
   list($currentx, $currenty) = $point;
   echo $i.' : '.$schrittezurueck."\r\n";
   $i++;
   if ($i % 200 == 0) {
    $this->plotimage('kreis-'.(round($i/200)).'.png');
    $this->makesteps('kreis-'.(round($i/200)).'.txt');
   }
  }
 
 }
 
 function makesteps($file) {
  $fp = fopen($file, 'w');
  if ($fp === false) {
   echo 'File '.$file.' couldn\'t be written.'."\r\n";
   return false;
  }
  $last = $this->schritte[0];
  foreach ($this->schritte as $current) {
   $x = $current[0]-$last[0];
   $y = $current[1]-$last[1];
   if ($x < 0) {
    $x = abs($x);
    fwrite($fp, str_repeat("rcall moverl\r\n", $x));
   }
   else {
    fwrite($fp, str_repeat("rcall moverr\r\n", $x));
   }
   if ($y < 0) {
    $y = abs($y);
    fwrite($fp, str_repeat("rcall movetl\r\n", $y));
   }
   else {
    fwrite($fp, str_repeat("rcall movetr\r\n", $y));
   }
  }
  fclose($fp);
 }
 
 /**
  * Sucht den dunkelsten Punkt um einen gegebenen Punkt herum, der nicht in points steht.
  */
 function getdarkest($x, $y) {
  $points = array();
  $grey = array();
  foreach (array(-1,0,1) as $xversatz) {
   foreach (array(-1,0,1) as $yversatz) {
    #echo $xversatz.'.'.$yversatz."\r\n";
    if ($x+$xversatz < $this->width and $x+$xversatz >= 0 and $y+$yversatz < $this->height and $y+$yversatz >= 0) {
     if (!in_array(array($x+$xversatz, $y+$yversatz), $this->points)) {
      $points[] = array($x+$xversatz, $y+$yversatz);
      $grey[] = $this->greyat($x+$xversatz, $y+$yversatz);
     }
    }
   }
  }
  if (empty($points)) {
   return false;
  }
  $leastgrey = $grey[0];
  $least = 0;
  foreach ($grey as $key=>$gr) {
   if ($gr < $leastgrey) {
    $leastgrey = $gr;
    $least = $key;
   }
  }
  return $points[$least];
 }
 
 /**
  * Ermittelt den Grauwert des geladenen Bildes im Punkt (x,y)
  */
 function greyat($x, $y) {
  return $this->farbe2grey(imagecolorat($this->im, $x, $y));
 }
 
 /**
  * Wandelt einen Farbpunkt in einen Grauwert um.
  */
 function farbe2grey($farbe) {
  $farbe = (int)round(abs($farbe));
  $farbe = $this->farbe2array($farbe);
  return (int)floor(($farbe[0] + $farbe[1] + $farbe[2]) / 3);
 }
 
 /**
  * Wandelt einen Integer-Farbwert in ein RGB-Array um.
  */
 function farbe2array($farbe) {
  $ret = array();
  $ret[2] = $farbe % 256;
  $farbe = floor($farbe/256);
  $ret[1] = $farbe % 256;
  $farbe = floor($farbe/256);
  $ret[0] = $farbe % 256;
  return $ret;
 }
 
 /**
  * Erzeugt aus den Punkten in points ein Bild.
  */
 function plotimage($file) {
  $im = imagecreatetruecolor($this->width, $this->height);
  $schwarz = imagecolorallocate($im, 0, 0, 0);
  $weis = imagecolorallocate($im, 255, 255, 255);
  imagefill($im, 0, 0, $weis);
  foreach ($this->points as $point) {
   imagesetpixel($im, $point[0], $point[1], $schwarz);
  }
  imagepng($im, $file);
 }
 
}

?>