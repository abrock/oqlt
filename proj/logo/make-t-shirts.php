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
  'front' => 'beschlossen',
  'back' => 'beschlossen'
 ),
 array(
  'front' => 'oqltesse',
  'back' => 'oqltesse'
 )
);

# x-center of back:
$backcenter = 402;
$backscale = 1.1;
$frontscale = 0.40;

foreach ($svgs as $svg) {
 $front = file_get_contents($svg['front'].'.svg');
 $back = file_get_contents($svg['back'].'.svg');
 $neu = str_replace('<!--oqlt-->',
  process($front, $frontscale, 162 - 25*$frontscale, 82 - 25*$frontscale).' '.
  process($back,  $backscale, $backcenter - 25*$backscale, 90 - 25*$backscale),
 $vorlage);
 file_put_contents('shirt-'.$svg['back'].'.svg', $neu);
 echo 'made shirt-'.$svg['front'].'-'.$svg['back'].'.svg'."\r\n";
}



?>