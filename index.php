<html>
	<head>
		<title>NewSeven in PHP</title>
	</head>
	<body>
		<h1>NewSeven in PHP</h1>
		<br>
<?php

define("L",0);
define("R",1);

function get_position($field = array()) {
	
	$len = count($field);
	
	$q = 0;
	
	for($a = 0 ; $a < $len ; $a++){
		
		$b = 0;
		
		for($c = 0 ; $c < $len ; $c++){
			
			if($field[$c] == $a)break;
			
			if($field[$c] > $a) $b++;
		
		}
		
		$q = $q * ($len - $a) + $b;
	}
	
	return $q;

}

function set_position($pos = null) {
	
	$field = array(-1,-1,-1,-1,-1,-1,-1);
	
	$q = $pos;
	
	for( $a = 1 ; $a <= 7 ; $a++){
		
		$b = $q % $a;
		
		$q = ( $q - $b ) / $a;
		
		for($c = $a - 1 ; $c >= $b ; $c--) { 
		 $field[$c+1] = $field[$c];
		}
		
		$field[$b] = 7 - $a;
	}
	
	array_pop($field);
	
	return $field;
}

function move_r($field = array() ) {
	
	$c = $field[3];
	
	$field[3] = $field[4];
	$field[4] = $field[5];
	$field[5] = $field[6];
	$field[6] = $c;
	
	return $field;

}

function move_l($field = array() ) {
		
	$c = $field[3];
	
	$field[3] = $field[2];
	$field[2] = $field[1];
	$field[1] = $field[0];
	$field[0] = $c;
	
	return $field;

}

function get_position_move($pos = null,$move = null) {
	
	$field = set_position($pos);
	
	if($move === L) {
		
		$field = move_l($field);
	
	} else if($move === R) {
		
		$field = move_r($field);	
	
	}
	
	$pos = get_position($field);
	
	return $pos;

}

function str_solution($solution = array()) {
	
	$str = "";
	
	foreach($solution as $val) {
		
		switch($val) {
			case L:
				$str .= " L ";
				break;
			case R:
				$str .= " R ";
				break;
		}
	
	}
	
	return $str;
}

function writeln($str = null) {
	
	echo "<h1>" . $str . "</h1>";

}

function print_puzzle($field = array(),$move = null) {
	
	$str = "";
	
	foreach($field as $val) {
		$str .= $val+1 . " ";
	}
	
	writeln($move . " Field: " . $str . " Position: " . get_position($field) );
}

function step_by_step($temp = array(),$seq = array()) {

	writeln("Step By Step: ");

	foreach($seq as $val) {
		switch($val) {
			case L:
				$temp = move_l($temp);
				print_puzzle($temp,"Move: L ");
				break;
			case R:
				$temp = move_r($temp);
				print_puzzle($temp,"Move: R ");
				break;
		}

	}

}


function calctrans() {
	
	$permmv = array();
	
	for($m = 0 ; $m < 2 ; $m++){
		
		$prm = array();
		
		for($p = 0 ; $p < 5040 ; $p++){
			
			$prm[$p] = get_position_move($p,$m);
		}
		
		$permmv[$m] = $prm;
	
	}
	
	return $permmv;
}

function calcprun(){
	
	global $permmv;
	
	$perm = array();
	
	for( $p = 0 ; $p < 5040 ; $p++ ) { 
		
		$perm[$p] = -1; 
	
	}

	$perm[0] = 0;

	$n = 1;
	
	for( $l = 0 ; $n > 0 ; $l++){
		
		$n = 0;
		
		for( $p = 0 ; $p < 5040 ; $p++ ){
			
			if( $perm[$p] == $l ){
				
				for( $m = 0 ; $m < 2 ; $m++ ){
					
					$q = $permmv[$m][$permmv[$m][$permmv[$m][$p]]];
					
					if( $perm[$q] == -1 ) { 
						
						$perm[$q] = $l+1; 
						
						$n++; 
					}
				
				}
			}
		}
	}
	
	return $perm;
}


$seq = array();

function solution_search( $pos, $depth, $remaining, $lastmove, $repeats ){
	
	global $seq;
	global $permmv;
	global $perm;
	
	if( $perm[$pos] > $remaining ) return false;

	if( $remaining == 0 ){
		return true;
	}

	for( $m = 0 ; $m < 2 ; $m++ ){
		
		if( $m!=$lastmove || $repeats<3 ){
			
			$pos2 = $permmv[$m][$pos];
			
			$seq[$depth] = $m;
			
			if( solution_search( $pos2, $depth+1, $remaining-1, $m, $m==$lastmove ? $repeats+1 : 1 ) ) return true;
		
		}
	
	}
}

function solve_position( $pos ){
	$maxdp = -1;
	do{
		$maxdp++;
	}while( !solution_search( $pos, 0, $maxdp, -1, 1 ) );
}




$permmv = calctrans();
$perm = calcprun();

$pos = rand(1,5040-1);


$puzzle = set_position($pos);

//$puzzle = array(6,2,1,0,3,5,4);

$pos = get_position($puzzle);

solve_position($pos);


print_puzzle($puzzle);
writeln("Solution: " . str_solution($seq) );


step_by_step($puzzle,$seq);


//echo "<pre>";
//var_dump(str_solution($seq));
//echo "</pre>";

//echo "<pre>";
//var_dump($perm);
//echo "</pre>";

/*
$puzzle = array(0,1,2,3,4,5,6);

$pos = 864;

$pos = 0;

$pos = get_position_move($pos,R);

$puzzle = set_position($pos);

print_puzzle($puzzle);
*/

//$puzzle = move_r($puzzle);



//print_puzzle($puzzle);


//$puzzle = array(3,0,1,2,4,5,6);

//$pos = 864;
/*
$pos = 3393;

$puzzle = set_position($pos);

print_puzzle($puzzle);
*/
/*
for($i=0;$i<7;$i++){
	
	$puzzle = set_position($i);

	print_puzzle($puzzle);
}
*/


?>		
	</body>
</html>