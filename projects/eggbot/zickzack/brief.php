<?php
$res = '';

$size = 96;

$up = str_repeat("rcall movetr\r\n", $size);
$down = str_repeat("rcall movetl\r\n", $size);

$right = str_repeat("rcall moverr\r\n", $size);
$left = str_repeat("rcall moverl\r\n", $size);

$leftup = str_repeat("rcall moverl\r\nrcall movetr\r\n", $size);
$leftdown = str_repeat("rcall moverl\r\nrcall movetl\r\n", $size);

$rightup = str_repeat("rcall moverr\r\nrcall movetr\r\n", $size);
$rightdown = str_repeat("rcall moverr\r\nrcall movetl\r\n", $size);

$res = $up.$right.$down.$left.$rightup.$left.$rightdown;
       
file_put_contents('brief.txt', $res);

?>