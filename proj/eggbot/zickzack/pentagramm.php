<?php
$res = '';

/*
 Höhe des Pentagrammes:
 h = a * 1/2 * sqrt(5+2*sqrt(5)) =~ a * 1,53884176859

 längste Gerade:
 g = sqrt(a^2/4+h^2)
 
 Winkel gegen den "Boden"
 sin(alpha) = h/g
 
*/

require_once('kreis.php');

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
$res .= kreis($size/1.9, 4);

# Nach rechts unten fahren
$res .= str_repeat("rcall moverr\r\nrcall movetl\r\nrcall movetl\r\nrcall movetl\r\n",
 round($size/(sqrt(3*3+1*1))));
echo '(1r,2d) '.round($size/(sqrt(3*3+1*1)))."\r\n";

# Nach links auf halbe Höhe fahren
$res .= str_repeat("rcall moverl\r\nrcall movetr\r\nrcall moverl\r\nrcall movetr\r\nrcall moverl\r\n",
 round($size/(sqrt(3*3+2*2))));
echo '(3l,2u) '.round($size/(sqrt(3*3+2*2)))."\r\n";

# Waagrecht nach rechts fahren
$res .= str_repeat("rcall moverr\r\n",
 $size);
echo '(1r) '.$size."\r\n";

# Rundungsfehler Fehler ausgleichen
$error = 2 * round($size/(sqrt(3*3+1*1))) - 3 * round($size/(sqrt(3*3+2*2))) + $size - 3 * round($size/(sqrt(3*3+2*2)));
if ($error < 0) {
 $res .= str_repeat("rcall moverr\r\n", -$error);
}
else {
 $res .= str_repeat("rcall moverl\r\n", $error);
}

# Nach links unten fahren
$res .= str_repeat("rcall moverl\r\nrcall movetl\r\nrcall moverl\r\nrcall movetl\r\nrcall moverl\r\n",
 round($size/(sqrt(3*3+2*2))));
echo '(3l, 2d)  '.round($size/(sqrt(3*3+2*2)))."\r\n";


# Nach rechts oben fahren
$res .= str_repeat("rcall moverr\r\nrcall movetr\r\nrcall movetr\r\nrcall movetr\r\n",
 round($size/(sqrt(3*3+1*1))));
echo '(1r,2u) '.round($size/(sqrt(3*3+1*1)))."\r\n";

# Kreis fahren
$res .= kreis($size/1.9);

# Viertelkreis 12-3 Uhr
$res .= kreis($size/1.9, 1);



file_put_contents('pentagramm.txt', $res);
?>
