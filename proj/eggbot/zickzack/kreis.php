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
  $res .= "\r\n";
 }
 

 if ($teilviertel == 0 or $teilviertel == 2) {
  for ($x = count($viertel); $x > 0; $x--) {
   if ($viertel[$x-1] > 0) {
    $res .= str_repeat("rcall movetl\r\n", $viertel[$x-1]);
   }
   $res .= "rcall moverl\r\n";
  }
  $res .= "\r\n";
 }

 if ($teilviertel == 0 or $teilviertel == 3) {
  for ($x = 0; $x < count($viertel); $x++) {
   $res .= "rcall moverl\r\n";
   if ($viertel[$x] > 0) {
    $res .= str_repeat("rcall movetr\r\n", $viertel[$x]);
   }
  }
  $res .= "\r\n";
 }

 if ($teilviertel == 0 or $teilviertel == 4) {
  for ($x = count($viertel); $x > 0; $x--) {
   if ($viertel[$x-1] > 0) {
    $res .= str_repeat("rcall movetr\r\n", $viertel[$x-1]);
   }
   $res .= "rcall moverr\r\n";
  }
  $res .= "\r\n";
 }

 return "\r\n".$res."\r\n";

}

function reverseCode($res) {
 $res = explode("\n", $res);
 $res = array_reverse($res);
 $res = implode("\n", $res);
 return "\r\n".$res."\r\n";
}



function teilkreis($radius, $start, $stop) {
 if ($start > $stop) {
  echo '$start > $stop'."\r\n";
  return reverseCode(teilkreis($radius, $stop+180, $start+180));
 }
 
 $start = $start % 360;
 $stop  = $stop  % 360;

 while ($start < 0) {
  $start += 360;
 }

 while($stop < 0) {
  $stop += 360;
 }

 $start = $start * PI() / 180;
 $stop  = $stop  * PI() / 180;

 echo 'Start: '.$start.' Stop: '.$stop."\r\n";

 $kreis = kreis($radius);
 #return $kreis;
 $kreis = explode("\n", $kreis);
 $kreis = array_map('trim', $kreis);
 #echo 'Kreis1: '.count($kreis)."\r\n";
 foreach ($kreis as $key=>$value) {
  if (empty($value) or strpos($value, 'rcall') === false) {
   unset($kreis[$key]);
  }
 }
 #echo 'Kreis2: '.count($kreis)."\r\n";
 $kreis = array_values($kreis);
 #echo 'Kreis3: '.count($kreis)."\r\n";

 $length = count($kreis);

 for ($i = 0; $i < round($length * $start / (2 * PI())); $i++) {
  unset($kreis[$i]);
 }
 echo round($length * $start / (2 * PI()))."\r\n";

 for ($i = round($length * $stop / (2 * PI())); $i < $length; $i++) {
  unset($kreis[$i]);
  #echo $i."\r\n";
 }
 echo round($length * $stop / (2 * PI()))."\r\n";
 #print_r($kreis);
 $kreis = implode("\r\n", $kreis);
 return "\r\n".$kreis."\r\n";
}
?>
