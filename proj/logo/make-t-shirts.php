<?php

/**
 * Removes head and foot from svgs and translates and scales them
 */
function process($data, $scale = 1, $translatex = 0, $translatey = 0) {
 $data = str_replace("\r", ' ', $data);
 $data = str_replace("\n", ' ', $data);
 $data = str_replace('  ', ' ', $data);


 $data = preg_replace('#<\?.*?\?>#', '', $data);
 
 $data = preg_replace('#<rect.*?>#', '', $data);
 $data = preg_replace('#<(/|)svg.*?>#', '', $data);
 $data = preg_replace('#transform=".*?"#', 'transform="translate('.$translatex.', '.$translatey.') scale('.$scale.')"', $data);
 
 $data = str_replace('<', "\n<", $data);
 /*
 $data = preg_replace('##', '', $data);
 $data = preg_replace('##', '', $data);
 $data = preg_replace('##', '', $data);
 $data = preg_replace('##', '', $data);
 $data = preg_replace('##', '', $data);
 $data = preg_replace('##', '', $data);
 //*/
 
 return $data;
}

$vorlage = file_get_contents('male t-shirt.svg');

$svgs = array(
 array(
  'front' => 'kleines',
  'back' => 'neu'
 )
);

# x-center of back:
$backcenter = 402;
$backscale = 1.1;

foreach ($svgs as $svg) {
 $front = file_get_contents($svg['front'].'.svg');
 $back = file_get_contents($svg['back'].'.svg');
 $neu = str_replace('<!--oqlt-->',
  process($front, 0.5, 150, 70).' '.
  process($back,  $backscale, $backcenter - 25*$backscale, 90 - 25*$backscale),
 $vorlage);
 file_put_contents('shirt-'.$svg['front'].'-'.$svg['back'].'.svg', $neu);
 echo 'made shirt-'.$svg['front'].'-'.$svg['back'].'.svg'."\r\n";
}



?>