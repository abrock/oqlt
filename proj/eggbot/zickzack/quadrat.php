<?php
$res = '';

$size = 48;

$up = str_repeat("rcall movetr\r\n", $size);
$down = str_repeat("rcall movetl\r\n", $size);

$right = str_repeat("rcall moverr\r\n", $size);
$left = str_repeat("rcall moverl\r\n", $size);

$res = $right.$up.$left.$right.
       $right.$down.$left.$right;
       
file_put_contents('quadrat.txt', $res);

?>