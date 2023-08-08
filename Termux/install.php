<?php
	//color
	include("color.php");
	//main
	clear();
	$langLink = "https://raw.githubusercontent.com/HerokeyVN/Y2TB_Bot_Termux/main/lang/";
	echo $red."Please choose language:\n";
	echo $green."1. Tiếng Việt\n";
	echo "2. English";;
	//echo file_exists("./y2tb/lang/data.txt");
	$codel = "";
	if(!file_exists("./y2tb/lang/data.txt")){ ;
		$in = readline($yellow."\n\nYou choose: ");
		while((int) $in < 1 ||(int) $in > 2){
			$in = readline($yellow."Please choose \"1\" or \"2\": ");
		}
		switch ((int) $in) {
			case 1:
				$codel = "vi_VN";
				break;
			
			case 2:
				$codel = "en_US";
				break;
		}
		system("mkdir -p ./y2tb/lang && >./y2tb/lang/data.txt");
		file_put_contents("./y2tb/lang/data.txt", $codel);
	} else {
		$codel = file_get_contents("./y2tb/lang/data.txt");
		$in = readline($yellow."\n\nYou choose (you can continue with the language “".$codel."” by pressing “enter”): ");
		while(((int) $in < 1 ||(int) $in > 2) && $in != ""){
			$in = readline($yellow."Please choose \"1\", \"2\" or press “Enter”: ");
		}
		if((int) $in >= 1 && (int) $in <= 2){
			switch ((int) $in) {
				case 1:
					$codel = "vi_VN";
					break;
				
				case 2:
					$codel = "en_US";
					break;
			}
			@system("rm ./y2tb/lang/data.txt");
			file_put_contents("./y2tb/lang/data.txt", $codel);
		}
	}
	echo "\n".$green;
	@system("rm ./y2tb/lang/".$codel.".php");
	@system("wget -P ./y2tb/lang/ ".$langLink.$codel.".php", $exitcode);
	if($exitcode != 0) {
		echo $cyan.$line2.$red."Can't connect to Github.com. Please check the internet and try again late!\n";
		exit();
	}
	include_once("./y2tb/lang/".$codel.".php");
	//banner
	clear();
	$banner = "&&&&      &&             %    &&&&\n&&&&&&     &&           %   &&&&&&\n&&&&&&&&    &&         %  &&&&&&&&\n&&&&&&&&     &&       %   &&&&&&&&\n&&&&&&&&&&    &&     %  &&&&&&&&&&\n  &&&&&&&&     &&       &&&&&&&&\n    &&&&&&&&    &&    &&&&&&&&\n    &&&&&&&&%%  ..  %%&&&&&&&&\n      ########      &&&&&&&&\n        ########  ####&&&&\n        ########  ########\n          ######  ######\n          ######  ######\n          ######  ######\n          ######  ######\n          ######  ######\n          ######  ######\n";
	echo $cyan.$banner;
	//info
	$bot_ver = "1.1.2";
	$tool_ver = "0.0.3";
	boxe([$lang["version"].$tool_ver, $lang["sp_ver"].$bot_ver], $light_red, $light_cyan);
	boxe([$lang["cre"], $lang["product"], $lang["m_info"], $lang["thanks"]], $light_green, $white);
	echo $magenta.$line1;
	//menu
    $linkmn = "https://raw.githubusercontent.com/HerokeyVN/Y2TB_Bot_Termux/main/Termux/menu.php";
	if(file_exists("./menu.php")){
		
		print_delay($green.$lang["update_menu"]."", 250);
		print_delay("..", 500);
		@system("rm ./menu.php && curl -silent ".$linkmn." --output menu.php", $exitcode);
		echo(".\n");
		if($exitcode != 0)
			echo $magenta.$line1.$red."Can't connect to Github.com. Please check the internet and try again late!\n";
		else include("./menu.php");
		exit();
	}
	//install
	readline($white.$lang["please"].$lang["press_enter"]);
	clear();
	print_delay($white.$lang["hi"], 600);
	sleep(2);
	//clearLine();
	clear();
	print_delay($lang["thanks2"], 600); sleep(2); clear();
	print_delay($lang["noti_check_sys"], 600); sleep(1); echo "\n";
	echo $cyan.$line2;
	
	$minRam = 2.5;
	$minMem = 2.3;
	$check = true;
	$tcl = $light_green;
	echo $white." -Ram: ".$yellow.$lang["checking"];
	sleep(1);
	$t = getRam();
	if((($t[0]/1024)/1024)/1024 >= $minRam) echo $white."\r -Ram: ".$tcl.$lang["good"];
	else {
		$check = false;
		$tcl = $light_red;
		echo $white."\r -Ram: ".$tcl.$lang["bad"];
	}
	echo $white." (".$lang["require"].": ".$light_green.$minRam.$white."Gb, ".$lang["available"].": ".$tcl.round((($t[0]/1024)/1024)/1024, 1).$white."Gb)\n";
	
	$tcl = $light_green;
	echo $white." -Memory: ".$yellow.$lang["checking"];
	sleep(1);
	$t = getMem();
	if(($t[2]/1000)/1000 >= $minMem) echo $white."\r -Memory: ".$tcl.$lang["good"];
	else {
		$check = false;
		$tcl = $light_red;
		echo $white."\r -Memory: ".$tcl.$lang["bad"];
	}
	echo $white." (".$lang["require"].": ".$light_green.$minMem.$white."Gb, ".$lang["available"].": ".$tcl.round(($t[2]/1000)/1000, 1).$white."Gb)\n";
	echo $cyan.$line2;
	if($check){
		print_delay($green.$lang["check_done"], 600);
		sleep(2);
	} else {
		print_delay($white.$lang["check_bad1"]."\n", 600);
		print_delay($lang["check_bad2"], 600);
		$cf = readline();
		if(strtolower($cf) != "yes") {
			print_delay($white."\n\n".$lang["check_bad3"]."\n", 600);
			exit();
		}
	}
	
	//install
	clear();
	print_delay($lang["linux_install"]."\n", 500);
	print_delay($yellow."250mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	sleep(1);
//pkg update -y && pkg install wget curl proot tar -y && wget https://raw.githubusercontent.com/AndronixApp/AndronixOrigin/master/Installer/Ubuntu20/ubuntu20.sh -O ubuntu20.sh && chmod +x ubuntu20.sh && bash ubuntu20.sh
	@system("echo \"deb https://termux.mentality.rip/termux-main stable main\" > \$PREFIX/etc/apt/sources.list && cat \$PREFIX/etc/apt/sources.list && pkg update -y && pkg install wget curl proot tar -y && wget https://raw.githubusercontent.com/AndronixApp/AndronixOrigin/master/Installer/Ubuntu20/ubuntu20.sh -O ubuntu20.sh && chmod +x ubuntu20.sh && echo exit|bash ubuntu20.sh");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["update_pack"]."\n", 250);
	print_delay($yellow."25mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("echo \"dpkg --configure -a && apt update -y && apt upgrade -y\"|bash ./start-ubuntu20.sh");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["git_install"]."\n", 250);
	print_delay($yellow."87mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("./start-ubuntu20.sh apt -y install git");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	/*print_delay($green.$lang["php_install"]."\n", 250);
	print_delay($yellow."66mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("echo \"echo y|apt install php\"|bash ./start-ubuntu20.sh");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();*/
	
	print_delay($green.$lang["wget_install"]."\n", 250);
	//print_delay($yellow."5mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("./start-ubuntu20.sh apt -y install wget");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["curl_install"]."\n", 250);
	print_delay($yellow."143mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("./start-ubuntu20.sh apt -y install curl build-essential");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["nodejs_install"]."\n", 250);
	print_delay($yellow."124mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("echo \"curl -sL https://deb.nodesource.com/setup_18.x | bash && apt -y install nodejs\"|bash ./start-ubuntu20.sh");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["gcc_install"]."\n", 250);
	print_delay($yellow."143mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("./start-ubuntu20.sh \"apt -y install gcc g++ make zip\"");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["gnu_install"]."\n", 250);
	print_delay($yellow."156mb".$lang["use_mem"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("./start-ubuntu20.sh \"DEBIAN_FRONTEND=noninteractive TZ=Etc/UTC apt-get -y install tzdata\"");
	@system("./start-ubuntu20.sh \"apt -y install build-essential libcairo2-dev libpango1.0-dev libjpeg-dev libgif-dev librsvg2-dev\"");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["clone_bot"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("echo \"git clone https://github.com/VangBanLaNhat/Y2TB-Bot-lite-noPanel && mv ./Y2TB-Bot-lite-noPanel ./Y2TB\"|bash ./start-ubuntu20.sh");
	echo ("\n");
	while(!file_exists("./ubuntu20-fs/root/Y2TB/main.js")){
	    print_delay($green.$lang["botNotExit"]."\n", 250);
	    echo $cyan.$line3.$green;
	    @system("echo \"git clone https://github.com/VangBanLaNhat/Y2TB-Bot-lite-noPanel && mv ./Y2TB-Bot-lite-noPanel ./Y2TB\"|bash ./start-ubuntu20.sh");
	    echo ("\n");
	}
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["package_bot"]."\n", 250);
	echo $cyan.$line3.$green;
	//@system("echo \nrm ./Y2TB/package.json && echo rm ./Y2TB/package-lock.json\n|bash ./start-ubuntu20.sh");
	//@system("echo wget -P ./Y2TB https://raw.githubusercontent.com/VangBanLaNhat/Y2TB-Bot-lite-noPanel/main/package.json|bash ./start-ubuntu20.sh");
	//@system("echo wget -P ./Y2TB https://raw.githubusercontent.com/VangBanLaNhat/Package-for-VangBanLaNhatBot/main/Termux/package-lock.json|bash ./start-ubuntu20.sh");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["module_bot"]."\n", 250);
	echo $cyan.$line3.$green;
	@system("echo \"cd ./Y2TB && npm i\"|bash ./start-ubuntu20.sh");
	echo ("\n");
	print_delay($yellow.$lang["done"], 500);
	sleep(1);
	clear();
	
	print_delay($green.$lang["update_menu"], 250);
	print_delay("..", 500);
	@system("curl -silent ".$linkmn." --output menu.php", $exitcode);
	echo(".\n");
	if($exitcode != 0)
		echo $magenta.$line1.$red."Can't connect to Github.com. Please check the internet and try again late!\n";
	else include("./menu.php");
	//function
	function getRam(){
		$temp = array();
		exec("free --byte", $a);
		foreach(explode(" ", $a[1]) as $i) 
			if(((int) $i) > 0){
				array_push($temp, (int) $i);
			}
		return $temp;
	}
	function getMem(){
		$temp = array();
		$st = null;
		exec("df", $a);
		foreach($a as $i)
			if(strpos($i, "/storage") && strpos($i, "/emulate")){
				$st = $i;
				break;
			}
		foreach(explode(" ", $st) as $i) 
			if(((int) $i) > 0){
				array_push($temp, (int) $i);
			} else if(strpos($i, '%')) 
				array_push($temp, $i);
		return $temp;
	}
?>
