<?php

error_reporting(E_ALL);

$t = new oqltLogo();

class oqltLogo {
 private $outer_circle = 50,
  $circle_thickness = 4.5,
  $outer_pentagramm = 50,
  $outer_pentagramm_rounding = 1,
  $inner_pentagramm_rounding = 5,
  $pentagramm_circle_distance = 1.5,
  $pentagramm_pentagramm_distance = 0.75,
  $pentagramm_symbol_distance = 0.75,
  $pentagramm_thickness = 3,
  $round_holes_in_circle = false,
  $elements_width = array(5, 5, 5, 5, 5),
  $coil_outer_radius = 2.5,
  $coil_inner_radius = 0.8,
  $resistor_thickness = 1.4,
  $resistor_width = 10,
  $resistor_inner_thickness = 1.4,
  $capacitor_thickness = 1.4,
  $capacitor_distance = 1.4,
  $capacitor_length = 8,
  $diode_angle = 60,
  $diode_thickness = 1.4,
  $diode_height = 8,
  $diode_intersection = 1
  ;
  

 function oqltLogo() {
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

  $this->out($svg);
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
  $pentagramm_symbol_distance = $this->pentagramm_pentagramm_distance;
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
  $diode  = $this->diode();
  
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
   
   
   $path = 'M '.$top->out().' L '.$left_out->out().' L '.$left_in->out().
           ' L '.$second_peak->out().' L '.$right_in->out().' L '.$right_out->out();
   $svg .= '<path d="'.$path.'" class="pentacle" />'."\n";
   #break;


   $horizontal_distance = ($pentagramm_thickness + $pentagramm_pentagramm_distance) / cos(18 * PI() / 180);
   $horizontal_distance2 = $pentagramm_thickness * tan(18 * PI() / 180);
   
   $top_right = new Point($outer_circle - $width, $outer_circle - $incircle);
   $top_left = new Point($outer_circle - $pentagramm_edge_length / 2 + $horizontal_distance, $outer_circle - $incircle);
   $bottom_right = new Point($outer_circle - $width, $outer_circle - $incircle + $pentagramm_thickness);
   $bottom_left = new Point($outer_circle - $pentagramm_edge_length / 2 + $horizontal_distance - $horizontal_distance2, $outer_circle - $incircle + $pentagramm_thickness);
   
   if ($bottom_right->getX() <= $bottom_left->getX()) {
    continue;
   }
   if ($top_right->getX() <= $top_left->getX()) {
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
   
   $svg .= '<path d="M '.$top_right->out().' L '.$top_left->out().' L '.$bottom_left->out().' L '.$bottom_right->out().' z" class="pentacle" />'."\r\n";
   
  }
  
  $svg .= $coil.$resistor.$capacitor.$diode;
  

  
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
 
 function out($svg) {
  file_put_contents('neu.svg', '<?xml version="1.0" standalone="yes"?>
<svg
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:cc="http://creativecommons.org/ns#"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:svg="http://www.w3.org/2000/svg"
	xmlns:xlink="http://www.w3.org/1999/xlink"
	xmlns="http://www.w3.org/2000/svg"
	width="102"
	height="102"
>
<style type="text/css">
<![CDATA[
* { fill:none; }
.main { stroke:#000000; stroke-width:0; }
.pentacle { stroke:#00f; stroke-width:0; fill:#0000ff;}
.coil { stroke:#00f; stroke-width:0.0; fill:#000000}
.symbol { stroke:#00f; stroke-width:0.0; fill:#000000}
.resistor { stroke:#00f; stroke-width:0.0;}
.outer {stroke:#00f; stroke-width:0; fill:#00bb00;}
circle {fill:#ff0000;}
]]>
</style><g transform="translate(1,1)">'.$svg.'</g></svg>');
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
  
  foreach ($points as $point) {
   $point->translate($outer_circle, $outer_circle + $incircle - $pentagramm_thickness / 2 + 0.25 * $outer + 0.25 * $inner);
   $point->rotate(72 * 0.5, $outer_circle, $outer_circle);   
  }
  
  $i = 0;
  
  
  $path = 'M '.$points[$i++]->out();
  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');
  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');
  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');

  $path .= ' A '.$outer.','.$outer.' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.$inner.','.$inner.' 0 1,0 '.$points[$i++]->out(',');

  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.$inner.','.$inner.' 0 0,0 '.$points[$i++]->out(',');

  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.$inner.','.$inner.' 0 0,0 '.$points[$i++]->out(',');

  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  $path .= ' A '.$inner.','.$inner.' 0 0,0 '.$points[$i++]->out(',');

  $path .= ' A '.($thickness / 2).','.($thickness / 2).' 0 0,1 '.$points[$i++]->out(',');
  
  
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