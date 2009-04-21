<?php

require_once('loop.class.php');

$l = new loop();

$res = '';

$size = 4;

$sechseck = '';

$right = $l->makecode("rcall moverr\r\n", 8 * $size);
$left  = $l->makecode("rcall moverl\r\n", 8 * $size);

$rightup = $l->makecode(
"rcall moverr\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverr\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverr\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverr\r\nrcall movetr\r\n", $size);

$rightdown = $l->makecode(
"rcall moverr\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverr\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverr\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverr\r\nrcall movetl\r\n", $size);


$leftup = $l->makecode(
"rcall moverl\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverl\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverl\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverl\r\nrcall movetr\r\n", $size);

$leftdown = $l->makecode(
"rcall moverl\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverl\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverl\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverl\r\nrcall movetl\r\n", $size);

$sechseck = $left.$leftup.$rightup.$right.$rightdown.$leftdown.
       $rightdown.$right.$rightup.$leftup.$left.$right.$rightdown.$right;

$res = $l->makecode($sechseck, 16);
$res .= $rightup.$right.$rightup;

file_put_contents('sechseck.txt', $res);

?>