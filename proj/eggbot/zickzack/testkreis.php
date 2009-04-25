<?php
include("kreis.php");

$res = '';

$res .= kreis(50);
$res .= kreis(50, 1);
$res .= kreis(50, 4);
$res .= kreis(50);
$res .= teilkreis(50, 90, 30);

file_put_contents("kreis.txt", $res);

?>
