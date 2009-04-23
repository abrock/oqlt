<?php

/*
$t = new loop();

$res = '';

$up = $t->makecode("rcall moverr\r\nrcall movetr\r\n", 40);
$down = $t->makecode("rcall moverr\r\nrcall movetl\r\n", 40);

$repeat = $t->makecode($up.$down, 10);

$res = $repeat;

echo $res;

*/

# Erzeugt Assembler-Schleifen
class loop {
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
}


?>