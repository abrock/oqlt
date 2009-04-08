<?php
$res = '';

//*
$lastsin = 0;
$periode = 192;
for ($i = 0; $i < $periode; $i++) {
 $res .= "rcall moverr\r\n";
 $sin = (sin($i*2*pi()/$periode)*120);
 echo $i.'   '.($lastsin-$sin)."\r\n";
 for ($j = 0; $j < round(abs($lastsin-$sin)); $j++) {
  if ($lastsin-$sin < 0) {
   $res .= "rcall movetl\r\n";
  }
  else {
   $res .= "rcall movetr\r\n";
  }
 }
 if ($lastsin-$sin < 0) {
  echo str_repeat(' ',10+$lastsin-$sin).str_repeat('#', $sin-$lastsin)."\r\n";
 }
 else {
  echo str_repeat(' ',10).str_repeat('#', $lastsin-$sin)."\r\n";
 }
 $res .= ";###########\r\n";
 $lastsin = $sin;
}

//*/

/*
for ($i = 0; $i < 120; $i++) {
 $res .= "rcall movetl\r\n";
}
for ($i = 0; $i < 120; $i++) {
 $res .= "rcall moverr\r\n";
}
for ($i = 0; $i < 120; $i++) {
 $res .= "rcall movetr\r\n";
}
for ($i = 0; $i < 120; $i++) {
 $res .= "rcall moverr\r\n";
}
//*/


file_put_contents('zickzack.txt', $res);
?>
