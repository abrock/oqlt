<?php

$res = '';

$radius = 100;

# 12-3 Uhr
$yversatz = $radius;
for ($x = 0; $x <= $radius; $x++) {
 $res .= "rcall moverr\r\n";
 $y = round(sqrt($radius * $radius - $x * $x));
 echo $y."  ".($yversatz-$y)."\r\n";
 if ($y < $yversatz) {
  $res .= str_repeat("rcall movetl\r\n", $yversatz-$y);
 }
 $yversatz = $y;
}

# 3-6 Uhr
$yversatz = $radius;
for ($x = $radius; $x >= 0; $x--) {
 $res .= "rcall moverl\r\n";
 $y = round(sqrt($radius * $radius - $x * $x));
 echo $y."  ".($y-$yversatz)."\r\n";
 if ($y > $yversatz) {
  $res .= str_repeat("rcall movetl\r\n", $y-$yversatz);
 }
 $yversatz = $y;
}

# 6-9 Uhr
$yversatz = $radius;
for ($x = 0; $x <= $radius; $x++) {
 $res .= "rcall moverl\r\n";
 $y = round(sqrt($radius * $radius - $x * $x));
 echo $y."  ".($yversatz-$y)."\r\n";
 if ($y < $yversatz) {
  $res .= str_repeat("rcall movetr\r\n", $yversatz-$y);
 }
 $yversatz = $y;
}

#9-12 Uhr
$yversatz = $radius;
for ($x = $radius; $x >= 0; $x--) {
 $res .= "rcall moverr\r\n";
 $y = round(sqrt($radius * $radius - $x * $x));
 echo $y."  ".($y-$yversatz)."\r\n";
 if ($y > $yversatz) {
  $res .= str_repeat("rcall movetr\r\n", $y-$yversatz);
 }
 $yversatz = $y;
}

file_put_contents('kreis.txt', $res);

?>