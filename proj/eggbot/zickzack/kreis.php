<?php

function kreis ($radius) {

 $res = '';

 $radius = (int)(abs(round($radius)));

 $viertel = array();

 # 12-3 Uhr
 $yversatz = $radius;
 for ($x = 0; $x <= $radius; $x++) {
  $res .= "rcall moverr\r\n";
  $y = round(sqrt($radius * $radius - $x * $x));
  echo $y."  ".($yversatz-$y)."\r\n";
  if ($y < $yversatz) {
   $res .= str_repeat("rcall movetl\r\n", $yversatz-$y);
  }
  $viertel[$x] = $yversatz-$y;
  $yversatz = $y;
 }

 for ($x = count($viertel); $x > 0; $x--) {
  if ($viertel[$x-1] > 0) {
   $res .= str_repeat("rcall movetl\r\n", $viertel[$x-1]);
  }
  $res .= "rcall moverl\r\n";
 }

 for ($x = 0; $x < count($viertel); $x++) {
  $res .= "rcall moverl\r\n";
  if ($viertel[$x] > 0) {
   $res .= str_repeat("rcall movetr\r\n", $viertel[$x]);
  }
 }


 for ($x = count($viertel); $x > 0; $x--) {
  if ($viertel[$x-1] > 0) {
   $res .= str_repeat("rcall movetr\r\n", $viertel[$x-1]);
  }
  $res .= "rcall moverr\r\n";
 }

 return $res;

}

?>