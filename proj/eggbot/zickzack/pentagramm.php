<?php

/*
 Höhe des Pentagrammes:
 h = a * 1/2 * sqrt(5+2*sqrt(5)) =~ a * 1,53884176859

 längste Gerade:
 g = sqrt(a^2/4+h^2)
 
 Winkel gegen den "Boden"
 sin(alpha) = h/g
 
*/

require_once('kreis.php');
require_once('loop.class.php');
$l = new loop();

$a = 300;

echo 'Höhe des Pentagrammes h = '.($a/2*sqrt(5+2*sqrt(5)))."\r\n";
$h = ($a/2*sqrt(5+2*sqrt(5)));

echo 'Längste Gerade: g = '.sqrt($a*$a/4 + $h*$h)."\r\n";
$g = sqrt($a*$a/4 + $h*$h);

echo 'Winkel gegen den Boden: alpha = '.asin($h/$g)*180/PI()."\r\n";
echo 'Steigung: '.tan(asin($h/$g))."\r\n";
echo 'zweite Steigung: '.tan(36/180*PI())."\r\n";
for ($i = 1; $i < 20; $i++) {
 echo 'zweite Steiung: '.(tan(36/180*PI())*$i).'/'.$i."\r\n";
 echo 'Abweichung: '.(abs(round((tan(36/180*PI())*$i))-(tan(36/180*PI())*$i))/(tan(36/180*PI())*$i))."\r\n\r\n";
}

$size = 120;

for ($i = 1; $i <= 40; $i++) {
 echo $i.'  '.(1536*1.9/$i)."\r\n";
 echo (round(1536*1.9/$i)-(1536*1.9/$i))."\r\n\r\n";
}


#erstmal weit nach rechts
#$res .= str_repeat("rcall moverr\r\n", $size*2);

# Viertelkreis 9-12 Uhr
$res = '';
$res .= "; Viertelkreis 9-12 Uhr\r\n";
$res .= kreis($size/1.9, 4);

# Nach rechts unten fahren
$res .= "; Nach rechts unten fahren\r\n";
$res .= $l->makecode("rcall moverr\r\nrcall movetl\r\nrcall movetl\r\nrcall movetl\r\n",
 round($size/(sqrt(3*3+1*1))));
echo '(1r,2d) '.round($size/(sqrt(3*3+1*1)))."\r\n";

$res .= ";# Nach links auf halbe Höhe fahren\r\n";
$res .= $l->makecode("rcall moverl\r\nrcall movetr\r\nrcall moverl\r\nrcall movetr\r\nrcall moverl\r\n",
 round($size/(sqrt(3*3+2*2))));
echo '(3l,2u) '.round($size/(sqrt(3*3+2*2)))."\r\n";

$res .= ";# Waagrecht nach rechts fahren\r\n";
$res .= $l->makecode("rcall moverr\r\n",
 $size);
echo '(1r) '.$size."\r\n";

$res .= ";# Rundungsfehler Fehler ausgleichen\r\n";
$error = 2 * round($size/(sqrt(3*3+1*1))) - 3 * round($size/(sqrt(3*3+2*2))) + $size - 3 * round($size/(sqrt(3*3+2*2)));
if ($error < 0) {
 $res .= $l->makecode("rcall moverr\r\n", -$error);
}
else {
 $res .= $l->makecode("rcall moverl\r\n", $error);
}

$res .= ";# Nach links unten fahren\r\n";
$res .= $l->makecode("rcall moverl\r\nrcall movetl\r\nrcall moverl\r\nrcall movetl\r\nrcall moverl\r\n",
 round($size/(sqrt(3*3+2*2))));
echo '(3l, 2d)  '.round($size/(sqrt(3*3+2*2)))."\r\n";


$res .= ";# Nach rechts oben fahren\r\n";
$res .= $l->makecode("rcall moverr\r\nrcall movetr\r\nrcall movetr\r\nrcall movetr\r\n",
 round($size/(sqrt(3*3+1*1))));
echo '(1r,2u) '.round($size/(sqrt(3*3+1*1)))."\r\n";

$res .= ";# Kreis fahren\r\n";
$res .= kreis($size/1.9);

$res .= ";# Viertelkreis 12-3 Uhr\r\n";
$res .= kreis($size/1.9, 1);

$pentagramme = $l->makecode($res, 20);
#$pentagramme .= "; Viertel 2\r\n";
#$pentagramme .= kreis($size/1.9, 2);
#$pentagramme .= "; Viertel 3\r\n";
#$pentagramme .= kreis($size/1.9, 3);
#$pentagramme .= "; Viertel 4\r\n";
#$pentagramme .= kreis($size/1.9, 4);
#$pentagramme .= "; 0-27 Grad\r\n";
#$pentagramme .= teilkreis($size/1.9, 0, 27);
#$pentagramme .= "; 210-272 Grad\r\n";
#$pentagramme .= teilkreis($size/1.9, 210, 272);
$pentagramme .= teilkreis($size/1.9, 90, 27);
$pentagramme .= teilkreis($size/1.9, 210, 272);


file_put_contents('pentagramm.txt', $pentagramme);
?>
