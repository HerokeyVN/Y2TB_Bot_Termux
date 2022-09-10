<?php
	$stcl = "\033["; $edcl = "m";
	
	$default = $stcl.'39'.$edcl;
	$black = $stcl.'30'.$edcl;
	$red = $stcl.'31'.$edcl;
	$green = $stcl.'32'.$edcl;
	$yellow = $stcl.'33'.$edcl;
	$blue = $stcl.'34'.$edcl;
	$magenta = $stcl.'35'.$edcl;
	$cyan = $stcl.'36'.$edcl;
	$light_grayk = $stcl.'37'.$edcl;
	
	$dark_gray = $stcl.'90'.$edcl;
	$light_red = $stcl.'91'.$edcl;
	$light_green = $stcl.'92'.$edcl;
	$light_yellow = $stcl.'93'.$edcl;
	$light_blue = $stcl.'94'.$edcl;
	$light_magenta = $stcl.'95'.$edcl;
	$light_cyan = $stcl.'96'.$edcl;
	$white = $stcl.'97'.$edcl;
	
	$bg_default = $stcl.'49'.$edcl;
	$bg_black = $stcl.'40'.$edcl;
	$bg_red = $stcl.'41'.$edcl;
	$bg_green = $stcl.'42'.$edcl;
	$bg_yellow = $stcl.'43'.$edcl;
	$bg_blue = $stcl.'44'.$edcl;
	$bg_magenta = $stcl.'45'.$edcl;
	$bg_cyan = $stcl.'46'.$edcl;
	$bg_light_gray = $stcl.'47'.$edcl;
	
	$bg_dark_gray = $stcl.'100'.$edcl;
	$bg_light_red = $stcl.'101'.$edcl;
	$bg_light_green = $stcl.'102'.$edcl;
	$bg_light_yellow = $stcl.'103'.$edcl;
	$bg_light_blue = $stcl.'104'.$edcl;
	$bg_light_magenta = $stcl.'105'.$edcl;
	$bg_light_cyan = $stcl.'106'.$edcl;
	$bg_white = $stcl.'107'.$edcl;
	//line
	$line1 = "===============================================\n";
	$line2 = "_______________________________________________\n";
	$line3 = "-----------------------------------------------\n";
	$line4 = "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n";
	//function
	function clear(){
		@system("clear");
	};
	function clearLine(){
		echo "\r".str_repeat(" ", 50)."\r";
	};
	function boxe($a, $c1, $c2){
		$ml = 0;
		$i = "";
		foreach ($a as $i) {
			if(strlen($i) > $ml) $ml = strlen($i);
		};
		for ($i = 0; $i < count($a); $i++) {
			while(strlen($a[$i])<$ml) $a[$i] .= " ";
			if(strpos($a[$i], "℠")) $a[$i] .= "  ";
		};
		echo $c1."╔";
		for ($i = 0; $i <= $ml+1; $i++) 
			echo "═";
		echo "╗\n";
		foreach ($a as $i) {
			echo $c1."║ ".$c2.$i;
			echo $c1." ║\n";
		};
		echo "╚";
		for ($i = 0; $i <= $ml+1; $i++) 
			echo "═";
		echo "╝\n";
	};
	function print_delay($s, $t){
		for($i=0; $i<strlen($s); $i++){
			echo $s[$i];
			if($i == strlen($s)-1) break;
			usleep($t*100);
		}
	}
?>