<?php

function kreis ($radius, $teilviertel = 0) {

 $res = '';

 $radius = (int)(abs(round($radius)));

 $viertel = array();

 # 12-3 Uhr
 $yversatz = $radius;
 for ($x = 0; $x <= $radius; $x++) {
  #$res .= "rcall moverr\r\n";
  $y = round(sqrt($radius * $radius - $x * $x));
  #echo $y."  ".($yversatz-$y)."\r\n";
  if ($y < $yversatz) {
   #$res .= str_repeat("rcall movetl\r\n", $yversatz-$y);
  }
  $viertel[$x] = $yversatz-$y;
  $yversatz = $y;
 }

 if ($teilviertel == 0 or $teilviertel == 1) {
  for ($x = 0; $x < count($viertel); $x++) {
   $res .= "rcall moverr\r\n";
   if ($viertel[$x] > 0) {
    $res .= str_repeat("rcall movetl\r\n", $viertel[$x]);
   }
  }
 }

 if ($teilviertel == 0 or $teilviertel == 2) {
  for ($x = count($viertel); $x > 0; $x--) {
   if ($viertel[$x-1] > 0) {
    $res .= str_repeat("rcall movetl\r\n", $viertel[$x-1]);
   }
   $res .= "rcall moverl\r\n";
  }
 }

 if ($teilviertel == 0 or $teilviertel == 3) {
  for ($x = 0; $x < count($viertel); $x++) {
   $res .= "rcall moverl\r\n";
   if ($viertel[$x] > 0) {
    $res .= str_repeat("rcall movetr\r\n", $viertel[$x]);
   }
  }
 }

 if ($teilviertel == 0 or $teilviertel == 4) {
  for ($x = count($viertel); $x > 0; $x--) {
   if ($viertel[$x-1] > 0) {
    $res .= str_repeat("rcall movetr\r\n", $viertel[$x-1]);
   }
   $res .= "rcall moverr\r\n";
  }
 }

 return $res;

}

?>