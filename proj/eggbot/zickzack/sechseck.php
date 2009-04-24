<?php

require_once('loop.class.php');

$l = new loop();

$res = '';

$size = 4;

$sechseck = '';

$right = "rcall moverr\r\n";
$left  = "rcall moverl\r\n";

$rightup = 
"rcall moverr\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverr\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverr\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverr\r\nrcall movetr\r\n";

$rightdown = 
"rcall moverr\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverr\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverr\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverr\r\nrcall movetl\r\n";


$leftup = 
"rcall moverl\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverl\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverl\r\nrcall movetr\r\nrcall movetr\r\n".
"rcall moverl\r\nrcall movetr\r\n";

$leftdown = 
"rcall moverl\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverl\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverl\r\nrcall movetl\r\nrcall movetl\r\n".
"rcall moverl\r\nrcall movetl\r\n";

$sechseck = 
       $l->makecode($left, 8 * $size).
       $l->makecode($leftup, $size).
       $l->makecode($rightup, $size).
       $l->makecode($right, 8 * $size).
       $l->makecode($rightdown, $size).
       $l->makecode($leftdown, $size).
       $l->makecode($rightdown, $size).
       $l->makecode($right, 8 * $size).
       $l->makecode($rightup, $size).
       $l->makecode($leftup, $size).
       $l->makecode($left, 8 * $size).
       $l->makecode($right, 8 * $size).
       $l->makecode($rightdown, $size).
       $l->makecode($right, 8 * $size)
       ;

//*
$res = $l->makecode($sechseck, 20);
$res .= 
       $l->makecode($rightup, $size).
       $l->makecode($right, 8 * $size).
       $l->makecode($rightup, $size).
       $l->makecode($leftup, $size);
//*/


file_put_contents('sechseck.txt', $res);

?>