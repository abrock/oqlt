<?php

error_reporting(E_ALL);

$t = new oqltLogo();

# make standard logo
$t->make('neu.svg');

# make girlfriend logo
$t->oqltesse = true;
$t->make('oqltesse.svg');

# make slim
$t->circle_thickness = 1.5;
$t->pentagramm_thickness = 1.5;
$t->coil_inner_radius = 1;
$t->coil_outer_radius = 2.3;

# make slim girlfriend logo
$t->oqltesse = false;
$t->make('schlank.svg');
$t->oqltesse = true;
$t->make('schlank-esse.svg');


# Reset settings
$t = new oqltLogo();

# make bigger distances for printing small logos

$t->min_width = 1;
$t->pentagramm_pentagramm_distance = 2;
$t->pentagramm_symbol_distance = 2;
$t->pentagramm_circle_distance = 2;
$t->make('kleines.svg');

# make bigger distances for printing small girlfriend logos
$t->oqltesse = true;
$t->make('kleines-esse.svg');

class oqltLogo {
 public $outer_circle = 50,
  $circle_thickness = 4.5,
  $outer_pentagramm = 50,
  $outer_pentagramm_rounding = 1,
  $inner_pentagramm_rounding = 5,
  $pentagramm_circle_distance = 1.5,
  $pentagramm_pentagramm_distance = 1.00,
  $pentagramm_symbol_distance = 1.00,
  $pentagramm_thickness = 3,
  $round_holes_in_circle = false,
  $elements_width = array(5, 5, 5, 5, 5),
  $coil_outer_radius = 2.5,
  $coil_inner_radius = 0.7,
  $coil_flat_end = true,
  $resistor_thickness = 1.6,
  $resistor_width = 10,
  $resistor_inner_thickness = 1.6,
  $capacitor_thickness = 1.6,
  $capacitor_distance = 1.6,
  $capacitor_length = 8,
  $diode_angle = 60,
  $diode_thickness = 1.6,
  $diode_height = 8,
  $diode_intersection = 1,
  $switch_width = 15,
  $switch_radius = 2,
  $switch_thickness = 1.6,
  $switch_offset = 0,
  $switch_angle = 40,
  $oqltesse = false,
  $essefactor = 2,
  $min_width = 1
  ;

 function make($filename) {
  $svg = '';
  
  // Import values from object for better handling
  $outer_circle = $this->outer_circle;
  $circle_thickness = $this->circle_thickness;
  $outer_pentagramm = $this->outer_pentagramm;
  $outer_pentagramm_rounding = $this->outer_pentagramm_rounding;
  $inner_pentagramm_rounding = $this->inner_pentagramm_rounding;
  $pentagramm_circle_distance = $this->pentagramm_circle_distance;
  $pentagramm_thickness = $this->pentagramm_thickness;
  $round_holes_in_circle = $this->round_holes_in_circle;

  # Calculate some values
  $inner_circle = $this->inner_circle = $outer_circle - $circle_thickness;
  
  $distance_pentagramm_rounding = $this->distance_pentagramm_rounding =  $outer_pentagramm_rounding / sin(PI()*18/180) - $outer_pentagramm_rounding;

  $outer_pentagramm_rounding_r = $this->outer_pentagramm_rounding_r =  $outer_pentagramm - $distance_pentagramm_rounding + $outer_pentagramm_rounding;
  
  if ($round_holes_in_circle) {
   $svg .= $this->outer_circle_with_round_holes();
  }
  else {
   $svg .= $this->outer_circle_with_straigh_holes();
  }
  
  $svg .= $this->pentagramm();

  $this->out($svg, $filename);
 }
 
 function both_circles_complete() {
  $outer_circle = $this->outer_circle;
  $circle_thickness = $this->circle_thickness;
  $inner_circle = $this->inner_circle;

  $svg = '';
  echo 'outer and inner circle complete'."\r\n";
  $outer_circle_path = 'M 0 '.$outer_circle.' ';
  $outer_circle_path .= ' a '.$outer_circle.','.$outer_circle.' 1 1,1 '.(2*$outer_circle).',0 '."\n";
  $outer_circle_path .= 'l '.-$circle_thickness.' 0 ';
  $outer_circle_path .= ' a '.$inner_circle.','.$inner_circle.' 0 0,0 '.(-2*$inner_circle).',0 '."\n";
  $svg .= '<path d="'.$outer_circle_path.' z" class="outer" />'."\n";
  $outer_circle_path = 'M 0 '.$outer_circle.' ';
  $outer_circle_path .= ' a '.$outer_circle.','.$outer_circle.' 0 0,0 '.(2*$outer_circle).',0 '."\n";
  $outer_circle_path .= ' l '.-$circle_thickness.' 0 ';
  $outer_circle_path .= ' a '.$inner_circle.','.$inner_circle.' 0 0,1 '.(-2*$inner_circle).',0 '."\n";
  $svg .= '<path d="'.$outer_circle_path.' z" class="outer" />'."\n";
 
  return $svg;
 }

 function outer_circle_with_round_holes() {
  $outer_circle = $this->outer_circle;
  $circle_thickness = $this->circle_thickness;
  $outer_pentagramm = $this->outer_pentagramm;
  $outer_pentagramm_rounding = $this->outer_pentagramm_rounding;
  $inner_pentagramm_rounding = $this->inner_pentagramm_rounding;
  $pentagramm_circle_distance = $this->pentagramm_circle_distance;
  $pentagramm_thickness = $this->pentagramm_thickness;
  $round_holes_in_circle = $this->round_holes_in_circle;
  $inner_circle = $this->inner_circle;
  $distance_pentagramm_rounding = $this->distance_pentagramm_rounding;
  $outer_pentagramm_rounding_r = $this->outer_pentagramm_rounding_r;
  
  # Check if outer circle must be brocken
  $outer_circle_complete = (($outer_circle - $outer_pentagramm + $distance_pentagramm_rounding) > $pentagramm_circle_distance);
  
  # Check if inner circle must be broken
  $inner_circle_complete = (($inner_circle - $outer_pentagramm + $distance_pentagramm_rounding) > $pentagramm_circle_distance);

  
  $svg = '';

  if ($outer_circle_complete) {
   if ($inner_circle_complete) {
    $svg .= $this->both_circles_complete();
   }
   else {
    $inner_hole_arc = 180-$this->abc2gamma($inner_circle, $outer_pentagramm - $distance_pentagramm_rounding - $outer_pentagramm_rounding, $outer_pentagramm_rounding + $pentagramm_circle_distance);

    $tempcircle = $outer_pentagramm_rounding + $pentagramm_circle_distance;

    $inner = new Point($outer_circle, $circle_thickness);
    $inner->rotate($inner_hole_arc, $outer_circle, $outer_circle);
    $outer = new Point($outer_circle, 0);
    
    $top_of_rounding = new Point($outer_circle, $outer_circle - $outer_pentagramm_rounding_r - $pentagramm_circle_distance);
    
    for ($i = 0; $i < 5; $i++) {
     $outer_circle_path = 'M '.$inner->out();
     $outer_circle_path .= ' A '.$tempcircle.','.$tempcircle.' 0 0,0 '.$top_of_rounding->out(',');
     $outer_circle_path .= ' L '.$outer->out();

     $top_of_rounding->rotate(72, $outer_circle, $outer_circle);
     $outer->rotate(72, $outer_circle, $outer_circle);
     $outer_circle_path .= ' A '.$outer_circle.','.$outer_circle.' 0 0,1 '.$outer->out(',');

     $outer_circle_path .= ' L '.$top_of_rounding->out();
     
     $inner->rotate(72-2*$inner_hole_arc, $outer_circle, $outer_circle);
     $outer_circle_path .= ' A '.$tempcircle.','.$tempcircle.' 0 0,0 '.$inner->out(',');

     $inner->rotate(-72+2*$inner_hole_arc, $outer_circle, $outer_circle);
     $outer_circle_path .= ' A '.$inner_circle.','.$inner_circle.' 0 0,0 '.$inner->out(',');
     $inner->rotate(72, $outer_circle, $outer_circle);

     $svg .= '<path d="'.$outer_circle_path.'" class="outer" />'."\r\n";
     
    }
    
   }
  }
  else {
   $outer_hole_arc = 180-$this->abc2gamma($outer_circle, $outer_pentagramm - $distance_pentagramm_rounding - $outer_pentagramm_rounding, $outer_pentagramm_rounding + $pentagramm_circle_distance);
   
   $inner_hole_arc = 180-$this->abc2gamma($inner_circle, $outer_pentagramm - $distance_pentagramm_rounding - $outer_pentagramm_rounding, $outer_pentagramm_rounding + $pentagramm_circle_distance);
   echo $inner_hole_arc;

   $rounding_center = new Point($outer_circle, $outer_circle - $outer_pentagramm + $distance_pentagramm_rounding + $outer_pentagramm_rounding);
   
   $start = new Point($outer_circle,0);
   $start->rotate(-$outer_hole_arc, $outer_circle, $outer_circle);

   $inner = new Point($outer_circle, $circle_thickness);
   $inner->rotate(-$inner_hole_arc, $outer_circle, $outer_circle);
   echo $inner->out();

   for ($i = 0; $i < 5; $i++) {
    $rounding_center->rotate(72, $outer_circle, $outer_circle);
    $svg .= $rounding_center->circle();
    echo 'Rounding Center: '.$rounding_center->out();
    $start->rotate(2*$outer_hole_arc, $outer_circle, $outer_circle);
    $save = $start->klon();
    $outer_circle_path = 'M '.$start->out().' ';
    $start->rotate(72-2*$outer_hole_arc, $outer_circle, $outer_circle);
    $outer_circle_path .= ' A '.$outer_circle.','.$outer_circle.' 0 0,1 '.$start->out(',');
    
    $inner->rotate(72, $outer_circle, $outer_circle);
    $tempcircle = $outer_pentagramm_rounding + $pentagramm_circle_distance;
    $outer_circle_path .= ' A '.$tempcircle.','.$tempcircle.' 0 0,0 '.$inner->out(',');
    
    $inner->rotate(-(72-2*$inner_hole_arc), $outer_circle, $outer_circle);
    $outer_circle_path .= ' A '.$inner_circle.','.$inner_circle.' 0 0,0 '.$inner->out(',');

    $tempcircle = $outer_pentagramm_rounding + $pentagramm_circle_distance;
    $outer_circle_path .= ' A '.$inner_circle.','.$inner_circle.' 0 0,0 '.$save->out(',');
    
    $inner->rotate(72-2*$inner_hole_arc, $outer_circle, $outer_circle);
    
    $svg .= '<path d="'.$outer_circle_path.'" class="outer" />'."\r\n";
   }
  }
  return $svg;
 }
 
 function outer_circle_with_straigh_holes(){
  $outer_circle = $this->outer_circle;
  $circle_thickness = $this->circle_thickness;
  $outer_pentagramm = $this->outer_pentagramm;
  $outer_pentagramm_rounding = $this->outer_pentagramm_rounding;
  $inner_pentagramm_rounding = $this->inner_pentagramm_rounding;
  $pentagramm_circle_distance = $this->pentagramm_circle_distance;
  $pentagramm_thickness = $this->pentagramm_thickness;
  $round_holes_in_circle = $this->round_holes_in_circle;
  $inner_circle = $this->inner_circle;
  $distance_pentagramm_rounding = $this->distance_pentagramm_rounding;
  $outer_pentagramm_rounding_r = $this->outer_pentagramm_rounding_r;

  $distance_top_peak = $pentagramm_circle_distance / sin(18 * PI() / 180);
  
  $outer_circle_complete = $outer_pentagramm + $distance_top_peak < $outer_circle;
  
  $inner_circle_complete = $outer_pentagramm + $distance_top_peak < $inner_circle;
  
  if ($inner_circle_complete) {
   return $this->both_circles_complete();
  }
  echo 'Inner circle must be broken'."\r\n";
    
  $inner_arc = $this->bra2gamma($outer_pentagramm + $distance_top_peak - $inner_circle, $inner_circle, 18);
  
  $svg = '';
  if (!$outer_circle_complete) {
   echo 'Outer circle must be broken'."\r\n";
   $outer_arc = $this->bra2gamma($outer_pentagramm + $distance_top_peak - $outer_circle, $outer_circle, 18);
   
   $outer = new Point($outer_circle, 0);
   $outer->rotate($outer_arc, $outer_circle, $outer_circle);
   
   $inner = new Point($outer_circle, $circle_thickness);
   $inner->rotate($inner_arc, $outer_circle, $outer_circle);
   
   echo '$outer_arc = '.$outer_arc."\r\n".'$inner_arc = '.$inner_arc."\r\n";

   for ($i = 0; $i < 5; $i++) {
    $path = ' M '.$inner->out();
    $path .= ' L '.$outer->out();

    $outer->rotate(72-2*$outer_arc, $outer_circle, $outer_circle);
    $path .= ' A '.$outer_circle.','.$outer_circle.' 0 0,1 '.$outer->out(',');

    $inner->rotate(72-2*$inner_arc, $outer_circle, $outer_circle);
    $path .= ' L '.$inner->out();
    
    $inner->rotate(-72+2*$inner_arc, $outer_circle, $outer_circle);
    $path .= ' A '.$inner_circle.','.$inner_circle.' 0 0,0 '.$inner->out(',');
    
    $inner->rotate(72, $outer_circle, $outer_circle);
    
    $outer->rotate(2*$outer_arc, $outer_circle, $outer_circle);
    
    $svg .= '<path d="'.$path.'" class="outer" />';
   }

   return $svg;
  }
  
  $outer_arc = $this->bra2gamma($outer_pentagramm + $distance_top_peak - $outer_circle, $outer_circle, 18);
   
  if (is_infinite($outer_arc) or is_nan($outer_arc) or $outer_arc < 0) {
   $outer_arc = 0;
  }

  if (is_infinite($inner_arc) or is_nan($inner_arc) or $inner_arc < 0) {
   $inner_arc = 0;
  }
   
  $outer = new Point($outer_circle, 0);
  $outer->rotate($outer_arc, $outer_circle, $outer_circle);
   
  $inner = new Point($outer_circle, $circle_thickness);
  $inner->rotate($inner_arc, $outer_circle, $outer_circle);
  
  $top_of_peak = new Point($outer_circle, $outer_circle - $outer_pentagramm - $distance_top_peak);
   
  echo '$outer_arc = '.$outer_arc."\r\n".'$inner_arc = '.$inner_arc."\r\n";

  for ($i = 0; $i < 5; $i++) {
   $path = ' M '.$inner->out();
   $path .= ' L '.$top_of_peak->out();
   $path .= ' L '.$outer->out();

   $outer->rotate(72-2*$outer_arc, $outer_circle, $outer_circle);
   $path .= ' A '.$outer_circle.','.$outer_circle.' 0 0,1 '.$outer->out(',');

   $top_of_peak->rotate(72, $outer_circle, $outer_circle);
   $path .= ' L '.$top_of_peak->out();

   $inner->rotate(72-2*$inner_arc, $outer_circle, $outer_circle);
   $path .= ' L '.$inner->out();
    
   $inner->rotate(-72+2*$inner_arc, $outer_circle, $outer_circle);
   $path .= ' A '.$inner_circle.','.$inner_circle.' 0 0,0 '.$inner->out(',');
    
   $inner->rotate(72, $outer_circle, $outer_circle);
    
   $outer->rotate(2*$outer_arc, $outer_circle, $outer_circle);
    
   $svg .= '<path d="'.$path.'" class="outer" />';
  }
  
  
  return $svg;
 }
 
 function pentagramm() {
  $outer_circle = $this->outer_circle;
  $circle_thickness = $this->circle_thickness;
  $outer_pentagramm = $this->outer_pentagramm;
  $outer_pentagramm_rounding = $this->outer_pentagramm_rounding;
  $inner_pentagramm_rounding = $this->inner_pentagramm_rounding;
  $pentagramm_circle_distance = $this->pentagramm_circle_distance;
  $pentagramm_pentagramm_distance = $this->pentagramm_pentagramm_distance;
  $pentagramm_symbol_distance = $this->pentagramm_symbol_distance;
  $pentagramm_thickness = $this->pentagramm_thickness;
  $round_holes_in_circle = $this->round_holes_in_circle;
  $inner_circle = $this->inner_circle;
  $distance_pentagramm_rounding = $this->distance_pentagramm_rounding;
  $outer_pentagramm_rounding_r = $this->outer_pentagramm_rounding_r;
  
  
  $incircle = $this->outer_pentagramm * sin(18 * PI() / 180);
  $pentagramm_edge_length = 2 * $incircle * tan(36 * PI() / 180);
  
  $half_side = $outer_pentagramm * cos(18 * PI() / 180);
  echo '$half_side = '.$half_side."\r\n";
  
  $distance_second_peak = $pentagramm_thickness / sin(18 * PI() / 180);

  $svg = '';
  
  $coil = $this->coil();
  $resistor  = $this->resistor();
  $capacitor  = $this->capacitor();
  $switch  = $this->myswitch();
  $diode  = $this->diode();
  $text = $this->text();
  
  $elements_width = $this->elements_width;
  
  foreach ($elements_width as $key=>$width) {
   $width = $width / 2 + $pentagramm_symbol_distance;
   echo 'Width of element '.$key.' = '.$width."\r\n";
   $current_side = $half_side - $width;
   
   
   $top = new Point($outer_circle, $outer_circle - $outer_pentagramm);
   
   $left_out = new Point($outer_circle, $outer_circle - $outer_pentagramm + $current_side);
   $left_in = $left_out->klon();
   $left_in->translateX($pentagramm_thickness);
   $left_out->rotate(18, $top->getX(), $top->getY());
   $left_in->rotate(18, $top->getX(), $top->getY());
   
   $second_peak = $top->klon();
   $second_peak->translateY($distance_second_peak);

   $right_length = $this->rd2l($outer_pentagramm, $pentagramm_pentagramm_distance);
   echo '$right_length = '.$right_length."\r\n";
   $right_out = new Point($outer_circle, $outer_circle - $outer_pentagramm + $right_length);
   #$svg .= $right_out->circle();
   $right_out->rotate(-18, $top->getX(), $top->getY());
   $right_in = $right_out->klon();
   $right_in->translateX( - $pentagramm_thickness * cos(18 * PI() / 180));
   

   
   $top->rotate(($key + 1) * 72, $outer_circle, $outer_circle);
   $left_out->rotate(($key + 1) * 72, $outer_circle, $outer_circle);
   $left_in->rotate(($key + 1) * 72, $outer_circle, $outer_circle);
   $second_peak->rotate(($key + 1) * 72, $outer_circle, $outer_circle);
   $right_in->rotate(($key + 1) * 72, $outer_circle, $outer_circle);
   $right_out->rotate(($key + 1) * 72, $outer_circle, $outer_circle);
   
   
   if ($key == 0) {
    # Calculate circle for switch
    $temp = $this->pentagramm_symbol_distance + $this->switch_radius;
    $offset = $temp - sqrt($temp * $temp - $pentagramm_thickness * $pentagramm_thickness / 4);
    $left_out->translateX(-$offset);
    $left_in->translateX(-$offset);
    $path = 'M '.$top->out().' L '.$left_out->out().' A '.$temp.','.$temp.' 0 0,1 '.$left_in->out().
            ' L '.$second_peak->out().' L '.$right_in->out().' L '.$right_out->out();
    $svg .= '<path d="'.$path.'" class="pentacle" />'."\n";
   }
   else {
    $path = 'M '.$top->out().' L '.$left_out->out().' L '.$left_in->out().
            ' L '.$second_peak->out().' L '.$right_in->out().' L '.$right_out->out();
    $svg .= '<path d="'.$path.'" class="pentacle" />'."\n";
   }


   $horizontal_distance = ($pentagramm_thickness + $pentagramm_pentagramm_distance) / cos(18 * PI() / 180);
   $horizontal_distance2 = $pentagramm_thickness * tan(18 * PI() / 180);
   
   $top_right = new Point($outer_circle - $width, $outer_circle - $incircle);
   $top_left = new Point($outer_circle - $pentagramm_edge_length / 2 + $horizontal_distance, $outer_circle - $incircle);
   $bottom_right = new Point($outer_circle - $width, $outer_circle - $incircle + $pentagramm_thickness);
   $bottom_left = new Point($outer_circle - $pentagramm_edge_length / 2 + $horizontal_distance - $horizontal_distance2, $outer_circle - $incircle + $pentagramm_thickness);
   
   if ($bottom_right->getX() <= $bottom_left->getX() + $this->min_width) {
    continue;
   }
   if ($top_right->getX() <= $top_left->getX() + $this->min_width) {
    continue;
   }
   
   $top_right->rotate($key * 72, $outer_circle, $outer_circle);
   $top_left->rotate($key * 72, $outer_circle, $outer_circle);
   $bottom_right->rotate($key * 72, $outer_circle, $outer_circle);
   $bottom_left->rotate($key * 72, $outer_circle, $outer_circle);
   
   /*
   $svg .= $top_right->circle();
   $svg .= $bottom_right->circle();
   $svg .= $top_left->circle();
   $svg .= $bottom_left->circle();
   //*/
   
   if ($key == 0) {
    # Calculate circle for switch
    $temp = $this->pentagramm_symbol_distance + $this->switch_radius;
    $offset = $temp - sqrt($temp * $temp - $pentagramm_thickness * $pentagramm_thickness / 4);
    $top_right->translateX($offset);
    $bottom_right->translateX($offset);
    $svg .= '<path d="M '.$top_right->out().' A '.$temp.','.$temp.' 0 0,0 '.$bottom_right->out(',').' L '.$bottom_left->out().' L '.$top_left->out().' z" class="pentacle" />'."\r\n";
    #echo "\r\n\r\n".'ddddd'."\r\n\r\n";
    
   }
   else {
   
    $svg .= '<path d="M '.$top_right->out().' L '.$top_left->out().' L '.$bottom_left->out().' L '.$bottom_right->out().' z" class="pentacle" />'."\r\n";
   }
   
  }
  
  $svg .= $coil.$resistor.$capacitor.$diode.$switch.$text;
  

  
  return $svg;  
 }
 
 /**
  * Berechnet aus den drei Seitenlängen eines Dreiecks den Winkel
  * der der dritten Seite gegenüberliegenden Seite.
  *
  * @param double Seite a
  * @param double Seite b
  * @param double Seite c
  * @return double Gamma, der Winkel gegenüber von c. c² = a² + b² + 2ab*cos(gamma)
  */
 function abc2gamma($a, $b, $c) {
  echo 'a: '.$a."\r\n".'b: '.$b."\r\n".'c: '.$c."\r\n";
  echo '($c*$c - $a*$a - $b*$b) / (2*$a*$b) = '.($c*$c - $a*$a - $b*$b) / (2*$a*$b)."\r\n";
  return 180*acos(($c*$c - $a*$a - $b*$b) / (2*$a*$b))/PI();
 }
 
 /**
  * Berechnet die Halbwinkel der Lücken im äußeren Kreis bei geradlinigen Lücken.
  *
  * @param double Strecke, die die Spitze des Pentagrammes über den Kreis hinaussteht
  * @param double Radius des Kreises
  * @param double Öffnungswinkel des Pentagrammes in Grad
  * @return double Haldwinkel der Lücke in Grad
  */
 function bra2gamma($b, $r, $alpha) {
  $a = $b + $r;
  $c = $a * sin($alpha * PI() / 180);
  $d = $a * cos($alpha * PI() / 180);
  $beta = 180 * acos($c / $r) / PI();
  $gamma = 90 - $alpha - $beta;
  return $gamma;
 }
 
 /**
  * Berechnet die Länge der rechten Seite des Pentagrammes
  *
  * @param double Umkreis-Radius des Pentagrammes
  * @param double Abstand zwischen den Teilen des Pentagrammes
  * @return double Länge der rechten Seite
  */
 function rd2l($r, $d) {
  $a = $r * cos(18 * PI() / 180);
  $b = $r * sin(18 * PI() / 180);
  $l = ($r - $d - $b) / cos(18 * PI() / 180);
  return $l;
 }
 
 function out($svg, $filename) {
  file_put_contents($filename, '<?xml version="1.0" standalone="yes"?>
<svg
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:cc="http://creativecommons.org/ns#"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:svg="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink"
	xmlns="http://www.w3.org/2000/svg"
	width="'.(2 * $this->outer_circle + 2).'"
	height="'.(2 * $this->outer_circle + 2).'"
>
<style type="text/css">
<![CDATA[
.pentacle {stroke-width:0; fill:#390;}
.symbol {stroke-width:0; fill:#fff}
.outer {stroke-width:0; fill:#fff;}
.main {fill:#000}
.char {fill:#fff}
.q {fill:#390}
]]>
</style><rect  x="0" y="0" width="'.(2 * $this->outer_circle + 2).'" height="'.(2 * $this->outer_circle + 2).'" class="main" />
<g transform="translate(1,1)" class="main">'.$svg.'</g></svg>');
 }
 
 function coil () {
  $svg = '';
  // Import values from object for better handling
  $outer = $this->coil_outer_radius;
  $inner = $this->coil_inner_radius;
  $outer_circle = $this->outer_circle;
  $pentagramm_thickness = $this->pentagramm_thickness;

  $incircle = $this->outer_pentagramm * sin(18 * PI() / 180);
  
  $thickness = $outer - $inner;
  
  $distance_of_main_centers = $outer + $inner;
  $intersectionY = sqrt($outer * $outer - $distance_of_main_centers * $distance_of_main_centers / 4);
  
  echo '$intersectionY = '.$intersectionY."\r\n";
  
  $width = 5 * $outer + 3 * $inner;
  
  $this->elements_width[3] = $width;
  
  
  $points = array(
   new Point( - 2.5 * $outer - 1.5 * $inner, 0),
   new Point( - $outer - $inner, - $intersectionY),
   new Point( 0, -$intersectionY),
   new Point( $outer + $inner, -$intersectionY),
   new Point( + 2.5 * $outer + 1.5 * $inner, 0),
   new Point( + 1.5 * $outer + 2.5 * $inner, 0),
   new Point( + 1.5 * $outer + 0.5 * $inner, 0),
   new Point( + 0.5 * $outer + 1.5 * $inner, 0),
   new Point( + 0.5 * $outer - 0.5 * $inner, 0),
   new Point( - 0.5 * $outer + 0.5 * $inner, 0),
   new Point( - 0.5 * $outer - 1.5 * $inner, 0),
   new Point( - 1.5 * $outer - 0.5 * $inner, 0),
   new Point( - 1.5 * $outer - 2.5 * $inner, 0),
   new Point( - 2.5 * $outer - 1.5 * $inner, 0),
   
  );
  
  $flatpoints = array(
   new Point( + 2.5 * $outer + 1.5 * $inner, $thickness / 1.5),
   new Point( + 1.5 * $outer + 2.5 * $inner, $thickness / 1.5),
   new Point( - 1.5 * $outer - 2.5 * $inner, $thickness / 1.5),
   new Point( - 2.5 * $outer - 1.5 * $inner, $thickness / 1.5),
  );
  
  foreach ($points as $point) {
   #$point->translate($outer_circle, $outer_circle + $incircle - $pentagramm_thickness / 2 + 0.25 * $outer + 0.25 * $inner);
   $point->translate($outer_circle, $outer_circle + $incircle - $thickness / 1.5);
   $point->rotate(72 * 0.5, $outer_circle, $outer_circle);   
  }

  foreach ($flatpoints as $point) {
   #$point->translate($outer_circle, $outer_circle + $incircle - $pentagramm_thickness / 2 + 0.25 * $outer + 0.25 * $inner);
   $point->translate($outer_circle, $outer_circle + $incircle - $thickness / 1.5);
   $point->rotate(72 * 0.5, $outer_circle, $outer_circle);   
  }
  
  $i = 0;
  
  
  $path = 'M '.$points[$i++]->out();
  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');
  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');
  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');

  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');
  
  if ($this->coil_flat_end) {
   $path .= ' L '.$flatpoints[0]->out();
   $path .= ' L '.$flatpoints[1]->out();
   $path .= ' L '.$points[$i++]->out();
  }
  else {
   $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  }
  
  $path .= ' A '.$inner.','.$inner.' 0 1,0 '.$points[$i++]->out(',');

  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.$inner.','.$inner.' 0 0,0 '.$points[$i++]->out(',');

  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.$inner.','.$inner.' 0 0,0 '.$points[$i++]->out(',');

  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.$inner.','.$inner.' 0 0,0 '.$points[$i++]->out(',');

  if ($this->coil_flat_end) {
   $path .= ' L '.$flatpoints[2]->out();
   $path .= ' L '.$flatpoints[3]->out();
   $path .= ' L '.$points[$i++]->out();
  }
  else {
   $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  }
  
  
  $svg .= '<path d="'.$path.'" class="symbol coil" />';
  return $svg;
 }
 
 function resistor() {
  $svg = '';
  $thickness = $this->resistor_thickness;
  $width = $this->resistor_width;
  $inner = $this->resistor_inner_thickness;
  $outer_circle = $this->outer_circle;
  
  $height = $inner + 2 * $thickness;
  
  $incircle = $this->outer_pentagramm * sin(18 * PI() / 180);
  
  $this->elements_width[2] = $width;
  
  $points = array (
   new Point(0, + $inner / 2 + $thickness),
   new Point($width / 2, + $inner / 2 + $thickness),
   new Point($width / 2, - $inner / 2 - $thickness),
   new Point(0, - $inner / 2 - $thickness),
   new Point(0, - $inner / 2),
   new Point($width / 2 - $thickness, - $inner / 2),
   new Point($width / 2 - $thickness, + $inner / 2),
   new Point(0, + $inner / 2),
  );
  
  $path = '';
  $i = 0;
  
  foreach ($points as $point) {
   $point->translate($outer_circle, $outer_circle - $incircle + $this->pentagramm_thickness / 2);
   $point->rotate(72 * 2, $outer_circle, $outer_circle);
  }
  
  $path .= 'M '.$points[$i]->out();
  while (isset($points[++$i])) {
   $path .= 'L '.$points[$i]->out();
  }
  
  $svg .= '<path d="'.$path.' z" class="symbol resistor" />';
  
  $points = array (
   new Point(0, + $inner / 2 + $thickness),
   new Point( - $width / 2, + $inner / 2 + $thickness),
   new Point( - $width / 2, - $inner / 2 - $thickness),
   new Point(0, - $inner / 2 - $thickness),
   new Point(0, - $inner / 2),
   new Point( - $width / 2 + $thickness, - $inner / 2),
   new Point( - $width / 2 + $thickness, + $inner / 2),
   new Point(0, + $inner / 2),
  );
  
  $path = '';
  $i = 0;
  
  foreach ($points as $point) {
   $point->translate($outer_circle, $outer_circle - $incircle + $this->pentagramm_thickness / 2);
   $point->rotate(72 * 2, $outer_circle, $outer_circle);
  }
  
  $path .= 'M '.$points[$i]->out();
  while (isset($points[++$i])) {
   $path .= 'L '.$points[$i]->out();
  }
  
  $svg .= '<path d="'.$path.' z" class="symbol resistor" />';
  
  return $svg;
 }
 
 function capacitor() {
  $svg = '';
  $thickness = $this->capacitor_thickness;
  $distance = $this->capacitor_distance;
  $length = $this->capacitor_length; 
  $outer_circle = $this->outer_circle;
  
  $incircle = $this->outer_pentagramm * sin(18 * PI() / 180);
  
  $width = 2 * $thickness + $distance;
  $this->elements_width[1] = $width;
  
  $points = array (
   new Point($distance / 2, $length / 2),
   new Point($distance / 2 + $thickness, $length / 2),
   new Point($distance / 2 + $thickness, - $length / 2),
   new Point($distance / 2, - $length / 2)
  );
  
  foreach ($points as $point) {
   $point->translate($outer_circle, $outer_circle - $incircle + $this->pentagramm_thickness / 2);
   $point->rotate(72 * 1, $outer_circle, $outer_circle);
  }
  
  $i = 0;
  $path = ' M '.$points[$i]->out();
  
  while (isset($points[++$i])) {
   $path .= ' L '.$points[$i]->out();
  }
  
  $svg .= '<path d="'.$path.' z" class="symbol capacitor" />';

  $points = array (
   new Point(- $distance / 2, $length / 2),
   new Point(- $distance / 2 - $thickness, $length / 2),
   new Point(- $distance / 2 - $thickness, - $length / 2),
   new Point(- $distance / 2, - $length / 2)
  );
  
  foreach ($points as $point) {
   $point->translate($outer_circle, $outer_circle - $incircle + $this->pentagramm_thickness / 2);
   $point->rotate(72 * 1, $outer_circle, $outer_circle);
  }
  
  $i = 0;
  $path = ' M '.$points[$i]->out();
  
  while (isset($points[++$i])) {
   $path .= ' L '.$points[$i]->out();
  }
  
  $svg .= '<path d="'.$path.' z" class="symbol capacitor" />';


  echo 'memem';
  
  return $svg;

 }
 
 function diode() {
  $svg = '';
  $thickness = $this->diode_thickness;
  $height = $this->diode_height;
  $angle = $this->diode_angle; 
  $intersection = $this->diode_intersection;
  $outer_circle = $this->outer_circle;
  
  $incircle = $this->outer_pentagramm * sin(18 * PI() / 180);
  
  
  $leg = ($height / 2) / tan(($angle / 2) * PI() / 180);
  $offset = $leg * ($intersection / 2) / ($height / 2);
  
  $width = $leg - $offset + $thickness;
  $this->elements_width[4] = $width;
  
  $points = array (
   new Point(0, $height / 2),
   new Point($leg - $offset, $intersection / 2),
   new Point($leg - $offset, $height / 2),
   new Point($leg - $offset + $thickness, $height / 2),
   new Point($leg - $offset + $thickness, - $height / 2),
   new Point($leg - $offset, - $height / 2),
   new Point($leg - $offset, - $intersection / 2),
   new Point(0, - $height / 2)
  );
  
  foreach ($points as $point) {
   $point->translate($outer_circle - $width / 2, $outer_circle - $incircle + $this->pentagramm_thickness / 2);
   $point->rotate(72 * 4, $outer_circle, $outer_circle);
  }
  
  $i = 0;
  $path = ' M '.$points[$i]->out();
  
  while (isset($points[++$i])) {
   $path .= ' L '.$points[$i]->out();
  }
  
  $svg .= '<path d="'.$path.' z" class="symbol diode" />';

  
  return $svg;

 }
 
 function myswitch() {
  $svg = '';
  
  $width = $this->switch_width;
  $radius = $this->switch_radius;
  $thickness = $this->switch_thickness;
  $offset = $this->switch_offset;
  $angle = $this->switch_angle;
  $outer_circle = $this->outer_circle;
  
  $incircle = $this->outer_pentagramm * sin(18 * PI() / 180);
  
  $this->elements_width[0] = $width;
  
  $points = array (
   new Point(- $width / 2, 0),
   new Point(- ($width / 2 - 2* $radius) , 0),
  );
  
  foreach ($points as $point) {
   $point->translate($outer_circle, $outer_circle - $incircle + $this->pentagramm_thickness / 2);
   #$svg .= $point->circle();
  }
  
  $path = ' M '.$points[0]->out();
  $path .= ' A '.$radius.','.$radius.' 0 0,0 '.$points[1]->out(',');
  $path .= ' A '.$radius.','.$radius.' 0 1,0 '.$points[0]->out(',');
  
  $svg .= '<path d="'.$path.' z" class="symbol switch" />';

  $points = array (
   new Point(- ($width / 2 - $radius + $offset) , $thickness / 2),
   new Point($width / 2 - $radius - sqrt($radius * $radius - $thickness * $thickness / 4), + $thickness / 2),
   new Point($width / 2 - $radius - sqrt($radius * $radius - $thickness * $thickness / 4), - $thickness / 2),
   new Point(- ($width / 2 - $radius + $offset) , - $thickness / 2)
  );
  
  foreach ($points as $point) {
   $point->rotate($angle, $width / 2 - $radius, 0);
   $point->translate($outer_circle, $outer_circle - $incircle + $this->pentagramm_thickness / 2);
   #$svg .= $point->circle();
  }
  
  $path = ' M '.$points[0]->out();
  $path .= ' L '.$points[1]->out();
    
  $path .= ' A '.$radius.','.$radius.' 0 1,0 '.$points[2]->out(',');
  $path .= ' L '.$points[3]->out();
  
  $svg .= '<path d="'.$path.' z" class="symbol switch" />';

  
  return $svg;

 }
 
 function text() {
  $chars = array('	M 39.38644,0.06592 C 18.52829,0.06592 0,18.15042 0,38.45383 C 0,53.20986 11.64953,64.4156 26.84935,64.4156 C 47.7075,64.4156 66.45769,46.553 66.45769,26.69337 C 66.45769,11.27166 55.141,0.06592 39.38644,0.06592 M 36.83464,13.60154 C 45.15571,13.60154 51.25785,20.03652 51.25785,28.69043 C 51.25785,40.11804 40.9397,50.87998 29.84494,50.87998 C 21.41292,50.87998 15.19984,44.55595 15.19984,35.90203 C 15.19984,24.25254 25.51799,13.60154 36.83464,13.60154
','	M 125.01895,1.44232 L 123.46568,9.31961 C 122.02337,6.43497 121.02483,5.32549 118.58398,3.66128 C 114.81177,1.22043 110.70669,0 105.49215,0 C 85.63253,0 67.88087,18.0845 67.88087,38.16602 C 67.88087,53.69868 78.19903,64.34968 93.2879,64.34968 C 100.83234,64.34968 108.1549,61.68693 113.48039,57.02713 L 108.59869,83.43269 L 123.35474,83.43269 L 138.55457,1.44232 L 125.01895,1.44232 M 104.82647,13.53562 C 113.48038,13.53562 119.24967,19.63776 119.24967,28.84641 C 119.24967,40.49591 109.15342,50.81406 97.72581,50.81406 C 88.73906,50.81406 83.08071,45.04477 83.08071,35.94706 C 83.08071,23.74283 93.06602,13.53562 104.82647,13.53562
','	M 134.23682,83.39888 L 148.99287,83.39888 L 164.19271,1.40851 L 149.43666,1.40851 L 134.23682,83.39888
','	M 162.31906,83.35811 L 177.07511,83.35811 L 185.95092,35.31774 L 194.82674,35.31774 L 197.37854,21.89307 L 188.50272,21.89307 L 192.27494,1.36774 L 177.5189,1.36774 L 173.74667,21.89307 L 166.53507,21.89307 L 163.98327,35.31774 L 171.19488,35.31774 L 162.31906,83.35811
');
  if ($this->oqltesse) {
   $chars[] = '	M 232.47984,19.008105037 C 211.98018,19.008105037 193.52356,37.042265037 193.52356,57.098685037 C 193.52356,72.501135037 205.11464,83.358105037 221.51437,83.358105037 C 235.36549,83.358105037 247.76224,75.924675037 254.41078,63.735685037 L 238.5397,63.735685037 C 234.32896,67.946425037 229.73962,69.795545037 223.53432,69.795545037 C 215.11284,69.795545037 209.10607,64.767595037 209.10607,57.675815037 L 256.7193,57.675815037 C 258.15982,54.240735037 258.73926,49.143525037 258.73926,43.824695037 C 258.73926,29.197915037 247.88228,19.008105037 232.47984,19.008105037 z M 292.78993,19.008105037 C 279.60366,19.008105037 268.2619,29.193295037 268.2619,40.939045037 C 268.2619,48.695675037 271.65312,52.176925037 282.40159,55.944425037 C 289.9366,58.603845037 291.3471,59.700395037 291.3471,62.581425037 C 291.3471,66.459735037 287.76659,69.795545037 283.55585,69.795545037 C 279.2343,69.795545037 276.7411,67.168455037 276.63029,62.292855037 L 261.91347,62.292855037 C 261.80266,63.954995037 261.62491,65.402435037 261.62491,66.621335037 C 261.62491,76.483335037 269.30073,83.358105037 280.38163,83.358105037 C 294.23275,83.358105037 306.64105,71.794725037 306.64105,58.830075037 C 306.64105,51.516685037 303.02821,47.836905037 292.50136,44.401825037 C 286.96091,42.628885037 287.19407,42.580405037 285.86437,41.804745037 C 284.53466,41.029075037 283.55585,39.849425037 283.55585,38.630525037 C 283.55585,35.417065037 286.4692,32.570665037 289.90428,32.570665037 C 293.00693,32.570665037 294.69907,34.262805037 294.80988,37.476265037 L 309.23813,37.476265037 C 309.45975,35.814135037 309.5267,34.255885037 309.5267,33.147795037 C 309.5267,25.501975037 301.87626,19.008105037 292.78993,19.008105037 z M 341.55742,19.008105037 C 328.37115,19.008105037 317.02939,29.193295037 317.02939,40.939045037 C 317.02939,48.695675037 320.13204,52.176925037 330.88051,55.944425037 C 338.41552,58.603845037 340.11459,59.700395037 340.11459,62.581425037 C 340.11459,66.459735037 336.53408,69.795545037 332.32334,69.795545037 C 328.00179,69.795545037 325.50858,67.168455037 325.39778,62.292855037 L 310.39239,62.292855037 C 310.28159,63.954995037 310.39239,65.402435037 310.39239,66.621335037 C 310.39239,76.483335037 318.06822,83.358105037 329.14912,83.358105037 C 343.00024,83.358105037 355.40854,71.794725037 355.40854,58.830075037 C 355.40854,51.516685037 351.7957,47.836905037 341.26885,44.401825037 C 335.7284,42.628885037 335.673,42.580405037 334.34329,41.804745037 C 333.01358,41.029075037 332.32334,39.849425037 332.32334,38.630525037 C 332.32334,35.417065037 335.23669,32.570665037 338.67177,32.570665037 C 341.77442,32.570665037 343.46656,34.262805037 343.57737,37.476265037 L 358.00562,37.476265037 C 358.22724,35.814135037 358.29419,34.255885037 358.29419,33.147795037 C 358.29419,25.501975037 350.64375,19.008105037 341.55742,19.008105037 z M 401.00181,19.008105037 C 380.50215,19.008105037 362.04553,37.042265037 362.04553,57.098685037 C 362.04553,72.501135037 373.34805,83.358105037 389.74778,83.358105037 C 403.5989,83.358105037 416.28421,75.924675037 422.93275,63.735685037 L 406.77311,63.735685037 C 402.56237,67.946425037 397.97303,69.795545037 391.76773,69.795545037 C 383.34625,69.795545037 377.62804,64.767595037 377.62804,57.675815037 L 424.95271,57.675815037 C 426.39322,54.240735037 427.26123,49.143525037 427.26123,43.824695037 C 427.26123,29.197915037 416.40426,19.008105037 401.00181,19.008105037 z M 230.17132,32.570665037 C 239.59008,32.570665037 244.73346,36.712145037 245.1767,44.690395037 L 211.12603,44.690395037 C 214.00706,37.487805037 221.63903,32.570665037 230.17132,32.570665037 z M 398.69329,32.570665037 C 408.11205,32.570665037 412.96687,36.712145037 413.41011,44.690395037 L 379.35943,44.690395037 C 382.24047,37.487805037 390.161,32.570665037 398.69329,32.570665037 z';
  }
  
  $outer_circle = $this->outer_circle;

  $svg = '';
  
  if ($this->oqltesse) {
   $chars = $this->scale($chars, $outer_circle / 720);
  }
  else {
   $chars = $this->scale($chars, $outer_circle * $this->essefactor / 720);
  }
  $classes = array('o', 'q', 'l', 't', 'esse');
  
  $oqltbb = $this->bounding(implode(' ', array_slice($chars, 0, (($this->oqltesse)?(5):(4)))));
  

  $width = $oqltbb[1][0];
  $height = $oqltbb[1][1];
  
  foreach ($chars as $key=>$char) {
   $svg .= '<path class="char '.$classes[$key].'" d="'.$this->untranslate($outer_circle - $width / 2 - 1, 1.72 * $outer_circle - $height / 2, $char).'" />';
  }
  return $svg;
 }
 
// given translate() transformation coords, returns the translated path coords
// great to remove inkscape's stupid translate() madness
// coords can be "12,34" or a complete path like "M 1,2 L 3,4 C 5,6,7 z".
function untranslate($x, $y, $coords) {
	return (preg_replace('/([0-9]+\.?[0-9]*),([0-9]+\.?[0-9]*)/e', "oqltLogo::translate1($x, $y, '\\1', '\\2')", $coords));
}

// translate the coordinate $a,$b with $x,$y
function translate1($x, $y, $a, $b) {
	$a = (double)$a; $b = (double)$b;
	return (($a+$x).','.($b+$y));
}
 
// calculate the bounding box of a path, not including bezier control points
function bounding($path) {
	$bound = array(array(null, null), array(null, null));
	preg_match_all('/([a-zA-Z])((?: *[0-9]+\.?[0-9]*,[0-9]+\.?[0-9]*)+)/', $path, $cmds, PREG_SET_ORDER);
	foreach ($cmds as $cmd) {
		$coords = array_map('trim', explode(' ', trim($cmd[2])));
		switch ($cmd[1]) {
		case 'M':
		case 'L':
			$bound = $this->rebound($bound, $coords[0]);
			break;
		case 'C':
			$bound = $this->rebound($bound, $coords[2]);
			break;
		}
	}
	return ($bound);
}

function rebound($bound, $coord) {
	$coord = explode(',', $coord);
	$x = (float)$coord[0]; $y = (float)$coord[1];
	// bound: ((xmin, ymin), (xmax, ymax))
	// echol("rebound: (".$bound[0][0].','.$bound[0][1].')-('.$bound[1][0].','.$bound[1][1].") with ($x,$y)");
	if ($bound[0][0] === null || $x < $bound[0][0])
		$bound[0][0] = $x;
	if ($bound[0][1] === null || $y < $bound[0][1])
		$bound[0][1] = $y;
	if ($bound[1][0] === null || $x > $bound[1][0])
		$bound[1][0] = $x;
	if ($bound[1][1] === null || $y > $bound[1][1])
		$bound[1][1] = $y;
	return ($bound);
}
 
 function scale($svg, $factor) {
  if (is_array($svg)) {
   foreach ($svg as $key=>$val) {
    $svg[$key] = $this->scale($val, $factor);
   }
   return $svg;
  }
  $svg = explode(' ', $svg);
  foreach ($svg as $key=>$part) {
   $part = explode(',', $part);
   foreach ($part as $key2=>$part2) {
    if (strpos($part2, '.') !== false) {
     $part2 = (double)$part2;
     $part[$key2] = $part2 * $factor;
     #echo $part[$key2]."\r\n";
    }
   }
   $svg[$key] = implode(',', $part);
  }
  $svg = implode(' ', $svg);
  
  return $svg;
 }
 
}

class Point {
 private $x, $y;
 
 function Point($x, $y) {
  $this->x = $x;
  $this->y = $y;
 }
 
 function translate($x, $y) {
  $this->x += $x;
  $this->y += $y;
 }
 
 function translateX($x) {
  $this->x += $x;
 }
 function translateY($y) {
  $this->y += $y;
 }
 
 function rotate($alpha, $x = 0, $y = 0) {
  $alpha /= (180/PI());
  $this->translate(-$x, -$y);
  $newx = $this->x * cos($alpha) - $this->y * sin($alpha);
  $newy = $this->x * sin($alpha) + $this->y * cos($alpha);
  $this->x = $newx;
  $this->y = $newy;
  $this->translate(+$x, +$y);
 }
 
 function klon() {
  return new Point($this->x, $this->y);
 }
 
 function out($space = ' ') {
  return ' '.$this->x.$space.$this->y." \n";
 }
 
 function circle() {
  return '<circle cx="'.$this->x.'" cy="'.$this->y.'" r="0.5" />'."\r\n";
 }
 
 function getX() {
  return $this->x;
 }
 function getY() {
  return $this->y;
 }
}

class Circle {
 private $radius, $width;
 function Circle($radius, $width) {
  $this->radius = abs($radius);
  $this->width = abs($width);
 }
 function out() {
  return " a ".$this->radius.",".$this->radius." 0 0,1 51,0 \r\n";
 }
}

?>