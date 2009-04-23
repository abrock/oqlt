<?php

require_once('loop.class.php');
$l = new loop();

if (!isset($_SERVER['argv'][1])) {
 die ('Please name the file you want to read from.'."\r\n");
}

$file = $_SERVER['argv'][1];

if (!file_exists($file)) {
 die('The file '.$file.' doesn\'t exist.'."\r\n");
}

if (!is_readable($file)) {
 die('The file '.$file.' isn\'t readable.'."\r\n");
}

$res = file ($file);

$res = array_map('trim', $res);
$res = array_map('strtolower', $res);

# Erzeuge aus dem Array mit den rcalls einen String mit dem Alphabet [rlud]
# (right, left, up, down)

class autoshrink {
 
 var mc;
 var overhead = 12;
 var bites_per_step = 2;
 
 var $loopmark = 0;
 var $minreg = 25;
 

 function makecode($code, $times) {
  $register = $this->minreg;
  while (strpos($code, 'r'.$register) !== false) {
   $register++;
  }
  $res = "\r\n".'ldi r'.$register.', '.(255 - $times)."\r\n".
         'autogenloopmark'.$this->loopmark.":\r\n".
         
         'rjmp autogenloopmarkskip'.$this->loopmark."\r\n".
         'autogenloopmarkendstart'.$this->loopmark.":\r\n".
         'rjmp autogenloopmarkend'.$this->loopmark."\r\n".
         'autogenloopmarkskip'.$this->loopmark.":\r\n".
         
         'add r'.$register.", one\r\n".
         'brcs autogenloopmarkendstart'.$this->loopmark."\r\n".
         $code."\r\n".
         'rjmp autogenloopmark'.$this->loopmark."\r\n".
         'autogenloopmarkend'.$this->loopmark.":\r\n";
         
  $this->loopmark++;
  
  return $res;
 }


 function rcall2code($res) {
 
  $code = '';
  
  
  foreach ($res as $line) {
   switch ($line) {
    case 'rcall moverr': 
    $code .= 'r';
    break;
  
    case 'rcall moverl':
    $code .= 'l';
    break;
  
    case 'rcall movetr':
    $code .= 'u';
    break;
  
    case 'rcall movetl':
    $code .= 'd';
   }
  }
 
  return $code;
 }
 
 # Erzeugt aus einem String aus [rlud]
 function code2rcall($code) {
  $res = '';
  for ($i = 0; $i < strlen($code); $i++) {
   switch ($code{$i}) {
    case 'r':
    $res .= "rcall moverr\r\n";
    break;
    
    case 'l':
    $res .= "rcall moverl\r\n";
    break;
    
    case 'u':
    $res .= "rcall movetr\r\n";
    break;
    
    case 'd':
    $res .= "rcall movetl\r\n";
    break;
    
   }
  }
  return $res;
 }
 
 function shrink($res) {
 
  $code = rcall2code($res);
 
  $res = '';
  
  while (!empty($code)) {
   $bestloop = 0;
   $bestsaving = 0;
   $bestrepeats = 0;
   for ($i = 1; $i <= 20; $i++) {
    $word = substr($code, 0, $i);
    $repeats = $this->countRepeats($word, $code);
    $saving = $this->saving($i, $repeats);
    if ($saving > $bestsaving) {
     $bestsaving = $saving;
     $bestloop = $i;
     $bestrepeats = $repeats;
    }
   }
   if ($bestloop !== 0) {
    $res .= $this->makecode(
             $this->code2rcall(substr($code, 0, $bestloop)),
             $bestrepeats);
    $code = substr($code, $bestloop * $bestrepeats);
   }
   else {
    $res .= $this->code2rcall($word[0]);
   }
  }
  return $res;
 }
 
 /**
  * Counts the number of repeats of the word within the code.
  *
  * Starts at the beginning of the code and counts until something else is found.
  *
  */
 function countRepeats($word, $code) {
  $count = 1;
  if (empty($code) or !is_string($code)) {
   return 0;
  }
  while (substr($code, $count * strlen($word), strlen($word)) === $word) {
   $count++;
  }
  return $count;
 }
 
 /**
  * Finds the loop that saves most space.
  */
 function bestLoop($loops) {
  
 }
 
 /**
  * Tells how much space it saves to build a loop around a given
  * set of steps.
  */
 function saving($steps, $times) {
  return ($steps * $this->bytes_per_step * $times) - 
         ($this->overhead + $steps * $this->bytes_per_step);
 }
 
} 

$t = new autoshrink();
echo $t->countRepeats('a', 'aaaaab');
?>