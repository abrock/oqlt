<?php

if (!isset($_SERVER['argv'][1])) {
 die ('Nenne eine Gleitkommazahl, deren ganzzahlige Vielfache
auf Abweichung von ihrer Rundung überprüft werden sollen.');
}

$num = (float)$_SERVER['argv'][1];

for ($i = 1; $i <= 20; $i++) {
 echo $i."\r\n".($i * $num)."\r\n".
      (($i * $num)-round(($i * $num)))."\r\n\r\n";
}

?>