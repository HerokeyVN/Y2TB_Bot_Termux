<?php
function ex() {
        global $yellow;
	clear();
	echo("\033[39m\nWelcome to Termux!

Community forum: https://termux.com/community
Gitter chat:     https://gitter.im/termux/termux
IRC channel:     #termux on libera.chat

Working with packages:

 * Search packages:   pkg search <query>
 * Install a package: pkg install <package>
 * Upgrade packages:  pkg upgrade

Subscribing to additional repositories:

 * Root:     pkg install root-repo
 * X11:      pkg install x11-repo

Report issues at https://termux.com/issues\n\033[1;33m\nType “php y2tb.php” to launch the bot menu\n");

}

function start() {
	global $lang;
	global $yellow;
	global $line2;
	global $default;
	clear();
	print_delay($yellow.$lang["stop"]."\n", 250);
	echo($line2.$default);
	@system("bash ./start-ubuntu20.sh \"cd Y2TB && npm start\"");
	readline($yellow.$lang["press_enter"]);
}
$id = 0;
function printList($n) {
	global $yellow,
	$codel,
	$lang,
	$id;
	$list = $GLOBALS["listInstall"];


	//echo($list." ".$n["file"]);
	if (!array_key_exists($n["file"], $list)) {
		array_push($GLOBALS["LNI"], [$n["file"], $n["ver"]]);
		$l = (explode('_', $codel))[0];
		$id += 1;
		echo($yellow."\n".$lang["id"].": ".$id."\n");
		echo($lang["plgName"]." ".str_replace(".js", '', $n["file"]."\n"));
		echo($lang["plgAuth"]." ".$n["author"]."\n");
		echo($lang["plgDest"]." ".$n[$l]."\n");
		echo($lang["plgVer"]." ".$n["ver"]."\n");
	}
}

function store() {
	global $lang,
	$yellow,
	$green,
	$cyan,
	$magenta,
	$red,
	$line4,
	$id;
	$GLOBALS["LNI"] = [];
	$id = 0;
	$check = true;
	$page = 1;
	$total = 0;
	$totalList = [];
	$GLOBALS["listInstall"] = json_decode(file_get_contents("./ubuntu20-fs/root/Y2TB/plugins/pluginList.json"), true);
	while ($check) {
		$GLOBALS["LNI"] = [];
		$id = 0;
		clear();
		echo($green.$lang["pageLoad"]);
		if (!array_key_exists($page, $totalList)) {
			$json = file_get_contents("https://raw.githubusercontent.com/VangBanLaNhat/Y2TB-data/main/PluginInfo.json?page=".$page);
			$json = json_decode($json, true);
			print_r($json);
			//readline();
			//$total = $json["status"]["pages"];
			//$totalList[$page] = $json["status"]["data"];
			$total = 2;
			$totalList[$page] = $json;
		}
		clear();
		echo($cyan.$lang["listPlugins"]."\n");
		array_map("printList", $totalList[$page]);
		echo($cyan."(".$lang["page"]." ".$page."/".$total.")\n");
		echo($magenta.$line4);
		echo($green.$lang["changePage"]."\n");
		$in = (int)(readline($cyan.$lang["idIn"]." ".$yellow));
		if ($in == 0) break;
		if (array_key_exists($in-1, $GLOBALS["LNI"])) {
			$GLOBALS["listInstall"] += [$GLOBALS["LNI"][$in-1][0] => $GLOBALS["LNI"][$in-1][1]];
			if (file_put_contents("./ubuntu20-fs/root/Y2TB/plugins/pluginList.json", json_encode($GLOBALS["listInstall"]))) {
				echo($magenta.$line4);
				//echo($yellow.$lang["PID"]."\n");
				//sleep(2);
			} else {
				echo($magenta.$line4);
				echo($red.$lang["PIF"]."\n");
				sleep(2);
			}
		} else {
			echo($magenta.$line4);
			$in = (int)(readline($cyan.$lang["toPage"]." ".$yellow));
			if ($in > 0) $page = $in;
		}
	}
}

function manager() {
	global $lang,
	$default,
	$red,
	$yellow,
	$green,
	$cyan,
	$magenta,
	$red,
	$line4,
	$id;
	$GLOBALS["listInstall"] = json_decode(file_get_contents("./ubuntu20-fs/root/Y2TB/plugins/pluginList.json"), true);
	$chID = 0;
	$page = 1;
	$maxValue = 5;
	while (true) {
		clear();
		echo($cyan.$lang["LBH"]."\n".$yellow);
		$listkey = array_keys($GLOBALS["listInstall"]);
		for ($i = ($page*$maxValue)-$maxValue; $i < $page*$maxValue && $i < count($listkey); $i++) {
			$key = $listkey[$i];
			echo("\n".$lang["id"].": ".$i+1);
			echo("\n".$lang["plgName"]." ".$key);
			echo("\n".$lang["plgVer"]." ".$GLOBALS["listInstall"][$key]."\n");
			if ($chID-1 == $i) {
				echo($default."║\n╚═ 0. ".$lang["return"]."\n");
				echo("║\n╚═ ".$red."1. ".$lang["delPlg"]."\n".$yellow);
			}
		}
		echo($cyan."(".$lang["page"]." ".$page."/".ceil(count($listkey)/$maxValue).")\n");
		echo($magenta.$line4);
		if ($chID != 0) {
			$in = (int) (readline($cyan.$lang["choose"].$yellow));

			if ($in == 0) $chID = 0;
			else if ($in == 1) {
				echo($magenta.$line4);
				$inp = strtolower(readline($cyan.$lang["surPlg"].$yellow));
				if ($inp == "y") {
					unset($GLOBALS["listInstall"][$listkey[$chID-1]]);
					unset($listkey[$chID-1]);
					file_put_contents("./ubuntu20-fs/root/Y2TB/plugins/pluginList.json", json_encode($GLOBALS["listInstall"]));
					$chID = 0;
				}
			}
			continue;
		}

		echo($green.$lang["changePage"]."\n\n");
		$in = (readline($cyan.$lang["idIn2"]." ".$yellow));

		if ($in == "0" || (int) $in < 0) return;
		$in = (int) $in;
		if ($in == 0) continue;
		if ($in <= ($page*$maxValue)-$maxValue || $in > $page*$maxValue) {
			echo($magenta.$line4);
			$in = (int)(readline($cyan.$lang["toPage"]." ".$yellow));
			if ($in > 0) $page = $in;
		} else {
			$chID = $in;
		}
	}
}

function ncf() {
	global $lang,
	$default,
	$red,
	$yellow,
	$green,
	$cyan,
	$magenta,
	$red,
	$line4,
	$codel,
	$id;
	if (!file_exists("./ubuntu20-fs/root/Y2TB/udata/config.json")) {
		$dfcf = file_get_contents("./ubuntu20-fs/root/Y2TB/core/util/defaultConfig.js");
		$dfcf = (explode("return", $dfcf))[1];
		$dfcf = (explode("}\n\nfunction", $dfcf))[0];
		system("mkdir -p ./ubuntu20-fs/root/Y2TB/udata/ && >./ubuntu20-fs/root/Y2TB/udata/config.json");
		file_put_contents("./ubuntu20-fs/root/Y2TB/udata/config.json", $dfcf);
	}
	$config = json_decode(file_get_contents("./ubuntu20-fs/root/Y2TB/udata/config.json"), true);
	while (true) {
		clear();
		echo($cyan.$lang["mn_4"]."\n");
		echo($magenta.$line4);
		echo($cyan.$lang["continue"]."\n\n".$yellow);
		echo("1. ".$lang["normal_edit"]."\n");
		echo("2. ".$lang["admin_edit"]."\n");
		echo("3. ".$lang["add_fbstate"]."\n");
		echo("4. ".$lang["advan_edit"]."\n");
		echo("0. ".$lang["return"]."\n");
		echo($magenta.$line4);
		$act = (int) (readline($cyan.$lang["choose"].$yellow));
		if ($act == 0) return;
		if ($act == 1) {
			clear();
			$config["bot_info"]["lang"] = $codel;
			echo($cyan.$lang["mn_4"]."\n");
			echo($magenta.$line4);
			$temp = readline($cyan.$lang["askName"]." (".$yellow.$config["bot_info"]["botname"].$cyan."): ".$yellow);
			$config["bot_info"]["botname"] = $temp != ""?$temp:$config["bot_info"]["botname"];
			echo($cyan."\n".$lang["yName"].": ".$yellow.$config["bot_info"]["botname"]."\n");

			echo($magenta.$line4);
			$temp = readline($cyan.$lang["askPrefix"]." (".$yellow.$config["facebook"]["prefix"].$cyan."): ".$yellow);
			$config["facebook"]["prefix"] = $temp != ""?$temp:$config["facebook"]["prefix"];
			echo($cyan."\n".$lang["yPrefix"].": ".$yellow.$config["facebook"]["prefix"]."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = $config["facebook"]["selfListen"] == true?$lang["on"]:$lang["off"];
			$temp = strtolower(readline($cyan.$lang["askSelf"].$s." (".$yellow.$ss.$cyan."): ".$yellow));

			$ch = $temp == "y"?true:false; $temp != ""?$config["facebook"]["selfListen"] = $ch:"";
			$s = $config["facebook"]["selfListen"] == true?$lang["on"]:$lang["off"];
			echo($cyan."\n".$lang["ySelf"].": ".$yellow.$s."\n");
			echo($magenta.$line4);
			$temp = strtolower(readline($cyan.$lang["sSave"]." (y/n): ".$yellow));
			if ($temp == "n") break;

			file_put_contents("./ubuntu20-fs/root/Y2TB/udata/config.json", json_encode($config, JSON_PRETTY_PRINT));
			print_delay($green."\n".$lang["saveSuccess"], 250);
			sleep(1);
			return;
		}
		if ($act == 2) {
            $config = json_decode(file_get_contents("./ubuntu20-fs/root/Y2TB/udata/config.json"), true);
            while(true) {
                clear();
    			echo($cyan.$lang["admin_edit"]."\n");
    			echo($magenta.$line4);
    			echo($cyan.$lang["list_admin"].":\n".$yellow);
    			//for($i = 0; $i<array_count_values($config["facebook"]["admin"])
    		    foreach ($config["facebook"]["admin"] as $i => $admin) {
    		        echo(($i+1).". ".$admin."\n");
    		    }
    			echo($magenta.$line4);
    			echo($yellow."* ".$green.$lang["edit_admin"]."\n\n");
    			$inp = (int) readline($cyan.$lang["edit_ad_in"].": ".$yellow);
    			if ($inp == 0) break;
    			if ($inp > 0 && $inp <= count($config["facebook"]["admin"])) {
    			    echo($magenta.$line4);
    			    $temp = strtolower(readline($cyan.$lang["rm_ad_sure"].$yellow.$config["facebook"]["admin"][$inp-1].$cyan." (y/n): ".$yellow));
			        if ($temp == "y") {
			            unset($config["facebook"]["admin"][$inp-1]);
			        }  
    			}
    			else if ($inp > count($config["facebook"]["admin"])) {
    			    echo($magenta.$line4);
    			    $adm = readline($cyan.$lang["add_admin"].": ".$yellow);
    			    $config["facebook"]["admin"][] = $adm;
    			}
    			file_put_contents("./ubuntu20-fs/root/Y2TB/udata/config.json", json_encode($config, JSON_PRETTY_PRINT));
            }
		}
		if ($act == 3) {
			clear();
			echo($cyan.$lang["add_fbstate"]."\n");
			echo($magenta.$line4);
			
			$fbs = readline($cyan.$lang["paste_fbstate"].$lang["press_enter"].": \n".$yellow);
			
			$fbstate = json_decode($fbs, true);
			
			if($fbstate) {
			    clear();
			    echo($yellow.$fbs."\n");
			    echo($magenta.$line4);
			    $temp = strtolower(readline($cyan.$lang["sure_fbstate"].": ".$yellow));
			    
			    echo($magenta.$line4);

			    if ($temp == "n") {
			        print_delay($yellow.$lang["cancelled"], 250); break;
			        sleep(1);
			    }
			    
			    if (!file_exists("./ubuntu20-fs/root/Y2TB/udata/fbstate.json")) {
			        system(">./ubuntu20-fs/root/Y2TB/udata/fbstate.json");
			    }
			    
			    file_put_contents("./ubuntu20-fs/root/Y2TB/udata/fbstate.json", json_encode($fbstate, JSON_PRETTY_PRINT));
			    
			    print_delay($yellow.$lang["done"], 250);
			    sleep(1);
			} else {
			    echo($magenta.$line4);
			    print_delay($red.$lang["illegal_fbstate"], 250);

			    sleep(1);
			}
			
			
			return;
		};
		if ($act == 4) {
			clear();
			echo($cyan.$lang["mn_4"]."\n");
			echo($magenta.$line4);
			print_delay($green.$lang["comming_soon"]."\n", 250);
			readline($yellow."\n".$lang["press_enter"]);
			return;
		};
		
	}
}


function avcf() {
	global $lang,
	$default,
	$red,
	$yellow,
	$green,
	$cyan,
	$magenta,
	$red,
	$line4,
	$id;
	if (!file_exists("./ubuntu20-fs/root/Y2TB/core/coreconfig.json")) {
		$dfcf = file_get_contents("./ubuntu20-fs/root/Y2TB/core/util/defaultConfig.js");
		$dfcf = (explode("return", $dfcf))[2];
		$dfcf = (explode("}\n\nmodule", $dfcf))[0];
		system("mkdir -p ./ubuntu20-fs/root/Y2TB/udata/ && >./ubuntu20-fs/root/Y2TB/core/coreconfig.json");
		file_put_contents("./ubuntu20-fs/root/Y2TB/core/coreconfig.json", $dfcf);
	}
	$config = json_decode(file_get_contents("./ubuntu20-fs/root/Y2TB/core/coreconfig.json"), true);
	while (true) {
		clear();
		echo($cyan.$lang["mn_5"]."\n");
		echo($magenta.$line4);
		echo($cyan.$lang["continue"]."\n\n".$yellow);
		echo("1. ".$lang["normal_edit"]."\n");
		echo("2. ".$lang["advan_edit"]."\n");
		echo("0. ".$lang["return"]."\n");
		echo($magenta.$line4);
		$act = (int) (readline($cyan.$lang["choose"].$yellow));
		if ($act == 0) return;
		if ($act == 1) {
			clear();
			echo($cyan.$lang["mn_5"]."\n");
			echo($magenta.$line4);
			echo($cyan.$lang["linkCl"].": ".$green."https://upload.wikimedia.org/wikipedia/commons/3/34/ANSI_sample_program_output.png\n\n");
			$tcl = "\033[".$config["main_bot"]["consoleColor"]."m";
			$temp = (int) readline($cyan.$lang["askCColor"]." (".$tcl.$config["main_bot"]["consoleColor"].$cyan."): ".$yellow);
			$config["main_bot"]["consoleColor"] = $temp != 0?$temp:$config["main_bot"]["consoleColor"];
			echo($cyan."\n".$lang["yCColor"].": "."\033[".$config["main_bot"]["consoleColor"]."m".$config["main_bot"]["consoleColor"]."\n");

			echo($magenta.$line4);
			$temp = readline($cyan.$lang["askTimeSaveData"]." (".$yellow.$config["main_bot"]["dataSaveTime"].$cyan."): ".$yellow);
			$config["main_bot"]["dataSaveTime"] = $temp != ""?$temp:$config["main_bot"]["dataSaveTime"];
			echo($cyan."\n".$lang["yTimeSaveData"].": ".$yellow.$config["main_bot"]["dataSaveTime"]."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = $config["main_bot"]["developMode"] == true?$lang["on"]:$lang["off"];
			$temp = strtolower(readline($cyan.$lang["askDev"].$s." (".$yellow.$ss.$cyan."): ".$yellow));

			$ch = $temp == "y"?true:false; $temp != ""? $config["main_bot"]["developMode"] = $ch:"";
			$s = $config["main_bot"]["developMode"] == true?$lang["on"]:$lang["off"];
			echo($cyan."\n".$lang["yDev"].": ".$yellow.$s."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = $config["main_bot"]["toggleLog"] == true?$lang["on"]:$lang["off"];
			$temp = strtolower(readline($cyan.$lang["askLog"].$s." (".$yellow.$ss.$cyan."): ".$yellow));
			$ch = $temp == "y"?true:false; $temp != ""?$config["main_bot"]["toggleLog"] = $ch:"";
			$s = $config["main_bot"]["toggleLog"] == true?$lang["on"]:$lang["off"];
			echo($cyan."\n".$lang["yLog"].": ".$yellow.$s."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = $config["main_bot"]["toggleDebug"] == true?$lang["on"]:$lang["off"];
			$temp = strtolower(readline($cyan.$lang["askDebug"].$s." (".$yellow.$ss.$cyan."): ".$yellow));
			$ch = $temp == "y"?true:false; $temp != ""?$config["main_bot"]["toggleDebug"] = $ch:"";
			$s = $config["main_bot"]["toggleDebug"] == true?$lang["on"]:$lang["off"];
			echo($cyan."\n".$lang["yDebug"].": ".$yellow.$s."\n");

			echo($magenta.$line4);
			echo($cyan.$lang["nUserAgent"].": ".$yellow.$config["facebook"]["userAgent"]."\n\n");
			$temp = readline($cyan.$lang["askUserAgent"].": ".$yellow);
			$config["facebook"]["userAgent"] = $temp != ""?$temp:$config["facebook"]["userAgent"];
			echo($cyan."\n".$lang["yUserAgent"].": ".$yellow.$config["facebook"]["userAgent"]."\n");

			echo($magenta.$line4);
			$temp = strtolower(readline($cyan.$lang["sSave"]." (y/n): ".$yellow));
			if ($temp == "n") break;

			file_put_contents("./ubuntu20-fs/root/Y2TB/core/coreconfig.json", json_encode($config, JSON_PRETTY_PRINT));
			print_delay($green."\n".$lang["saveSuccess"], 250);
			sleep(1);
			return;
		}
		if ($act == 2) {
			clear();
			echo($cyan.$lang["mn_5"]."\n");
			echo($magenta.$line4);
			print_delay($green.$lang["comming_soon"]."\n", 250);
			readline($yellow."\n".$lang["press_enter"]);
			return;
		};
		break;
	}
}

function sync() {
	global $lang,
	$default,
	$red,
	$yellow,
	$green,
	$cyan,
	$magenta,
	$red,
	$line4,
	$id;
	clear();
	$dir = "/storage/emulated/0/";
	if (!file_exists($dir)) {
		echo($red.$lang["err"].": ".$cyan.$lang["dirNotExit"].": ".$green.$dir."\n");
		$dir = "/sdcard/";
	}
	if (file_exists("./y2tb/dirData.txt"))
		$dir = file_get_contents("./y2tb/dirData.txt");
	while (!file_exists($dir) || !is_writable($dir)) {
		echo($red.$lang["err"].": ".$cyan.$lang["dirNotExit"].": ".$green.$dir."\n");
		$dir = readline($cyan.$lang["askDirSync"].": \n".$yellow);
		if ($dir[strlen($dir)-1] != '/') $dir = $dir."/";
		echo("\n");
	}
	file_put_contents("./y2tb/dirData.txt", $dir);

	clear();

	while (true) {
		clear();
		//echo(is_writable("/storage/emulated/0/"));
		echo($yellow.$lang["dirFolderSync"].": ".$green.$dir."Y2TB/\n");
		echo($magenta.$line4);
		echo($cyan.$lang["continue"]."\n".$yellow);
		echo("1. ".$lang["startSync"]."\n");
		echo("2. ".$lang["startSyncU"]."\n");
		echo("3. ".$lang["changeDirSync"]."\n");
		echo("0. ".$lang["return"]."\n");
		$act = (int) (readline($cyan.$lang["choose"].$yellow."\n"));
		if ($act == 0) return;
		if ($act == 1) {
			clear();
			echo($yellow.$lang["dirFolderSync"].": ".$green.$dir."Y2TB/\n");
			echo($magenta.$line4);
			if (!file_exists($dir."Y2TB/")) {
				echo($green.$lang["crFdSync"].": ".$green.$dir."\n");
				echo($green.$lang["stSyncFrUbuntu"]."...\n");
				system("mkdir -p ".$dir."Y2TB/");
				$temp = getListFile("./ubuntu20-fs/root/Y2TB", true, ["node_modules", "core", ".git"]);
				$temp2 = getListFile("./ubuntu20-fs/root/Y2TB", false, ["node_modules", "core", ".git"]);
				foreach ($temp2["folder"] as $i) system("cp -r ".$i." ".$dir."Y2TB/");
				system("cp -r ./ubuntu20-fs/root/Y2TB/core/coreconfig.json ".$dir."Y2TB/udata");
				echo($yellow.$lang["syncTotal"].": ".$cyan.(count($temp["file"])-7)." file & ".(count($temp["folder"]))." folder\n\n");
				readline($default.$lang["please"].$lang["press_enter"]);
			} else {
				echo($green.$lang["stSyncFrStorage"]."...\n\n");
				$temp = getListFile($dir."Y2TB/", true, ["node_modules", "core", ".git"]);
				$temp2 = getListFile("./ubuntu20-fs/root/Y2TB", false, ["node_modules", "core", ".git"]);
				foreach ($temp2["folder"] as $i) system("rm -r ".$i);
				$temp2 = getListFile($dir."Y2TB/", false, ["node_modules", "core", ".git"]);
				foreach ($temp2["folder"] as $i) system("cp -r ".$i." ./ubuntu20-fs/root/Y2TB");
				//echo("                                                 \r");
				foreach ($temp["file"] as $i) echo($yellow.$lang["syncDataFile"].": ".$green.$i."\n");
				echo("\n".$yellow.$lang["syncTotal"].": ".$cyan.(count($temp["file"]))." file & ".(count($temp["folder"]))." folder\n\n");
				readline($default.$lang["please"].$lang["press_enter"]);
			}
			//$temp = getListFile($dir."Y2TB/");

		} elseif ($act == 2) {
			clear();
			echo($yellow.$lang["dirFolderSync"].": ".$green.$dir."Y2TB/\n");
			echo($magenta.$line4);

			echo($green.$lang["crFdSync"].": ".$green.$dir."\n");
			echo($green.$lang["stSyncFrUbuntu"]."...\n");
			if (file_exists($dir."Y2TB/")) system("rm -r ".$dir."Y2TB/");
			system("mkdir -p ".$dir."Y2TB/");
			$temp = getListFile("./ubuntu20-fs/root/Y2TB", true, ["node_modules", "core", ".git"]);
			$temp2 = getListFile("./ubuntu20-fs/root/Y2TB", false, ["node_modules", "core", ".git"]);
			foreach ($temp2["folder"] as $i) system("cp -r ".$i." ".$dir."Y2TB/");
			system("cp -r ./ubuntu20-fs/root/Y2TB/core/coreconfig.json ".$dir."Y2TB/udata/");
			echo($yellow.$lang["syncTotal"].": ".$cyan.(count($temp["file"])-7)." file & ".(count($temp["folder"]))." folder\n\n");
			readline($default.$lang["please"].$lang["press_enter"]);
		} elseif ($act == 3) {
			$dir = readline($cyan.$lang["askDirSync"].": \n".$yellow);
			$dir = $dir?$dir:file_get_contents("./y2tb/dirData.txt");
			if ($dir[strlen($dir)-1] != '/') $dir = $dir."/";

			while (!file_exists($dir) || !is_writable($dir)) {
				echo($red.$lang["err"].": ".$cyan.$lang["dirNotExit"].": ".$green.$dir."\n");
				$dir = readline($cyan.$lang["askDirSync"].": \n".$yellow);
				$dir = $dir?$dir:file_get_contents("./y2tb/dirData.txt");
				if ($dir[strlen($dir)-1] != '/') $dir = $dir."/";

				echo("\n");
			}
			file_put_contents("./y2tb/dirData.txt", $dir);
		}
	}
}

$check = true;
while ($check) {
	clear();
	echo($light_cyan);
	print_delay($lang["wellcome"]."\n", 100);
	echo($yellow.$lang["continue"]."\n\n");
	$i = 1;
	while (array_key_exists('mn_'.(string)$i, $lang)) {
		echo($i.". ".$lang['mn_'.(string)$i]."\n");
		$i++;
	}
	echo($red."0. ".$lang["mn_exit"]."\n".$magenta.$line4);
	$choose = readline($light_cyan.$lang["choose"].$yellow);
	switch ($choose) {
		case "0":
			ex();
			$check = false;
			break;
		case "1":
			start();
			break;
		case "2":
			store();
			break;
		case "3":
			manager();
			break;
		case "4":
			ncf();
			break;
		case "5":
			avcf();
			break;
		case "6":
			sync();
			break;
	}
}
?>