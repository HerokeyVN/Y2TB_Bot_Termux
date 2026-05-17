<?php
function readEnv($filePath) {
	if (!file_exists($filePath)) {
		return [];
	}
	$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$env = [];
	foreach ($lines as $line) {
		$line = trim($line);
		if ($line === '' || strpos($line, '#') === 0) {
			continue;
		}
		$parts = explode('=', $line, 2);
		if (count($parts) === 2) {
			$key = trim($parts[0]);
			$val = trim($parts[1]);
			if (preg_match('/^"([^"]*)"$/', $val, $matches) || preg_match('/^\'([^\']*)\'$/', $val, $matches)) {
				$val = $matches[1];
			}
			$env[$key] = $val;
		}
	}
	return $env;
}

function writeEnv($filePath, $newValues) {
	$examplePath = str_replace('config.env', 'config.env.example', $filePath);
	if (!file_exists($filePath)) {
		if (file_exists($examplePath)) {
			copy($examplePath, $filePath);
		} else {
			touch($filePath);
		}
	}
	$lines = file($filePath, FILE_IGNORE_NEW_LINES);
	$output = [];
	$keysUpdated = [];
	foreach ($lines as $line) {
		$trimmed = trim($line);
		if ($trimmed === '' || strpos($trimmed, '#') === 0) {
			$output[] = $line;
			continue;
		}
		$parts = explode('=', $line, 2);
		if (count($parts) === 2) {
			$key = trim($parts[0]);
			if (array_key_exists($key, $newValues)) {
				$val = $newValues[$key];
				if (is_bool($val)) {
					$val = $val ? 'true' : 'false';
				}
				$output[] = $key . '=' . $val;
				$keysUpdated[$key] = true;
			} else {
				$output[] = $line;
			}
		} else {
			$output[] = $line;
		}
	}
	foreach ($newValues as $key => $val) {
		if (!isset($keysUpdated[$key])) {
			if (is_bool($val)) {
				$val = $val ? 'true' : 'false';
			}
			$output[] = $key . '=' . $val;
		}
	}
	file_put_contents($filePath, implode("\n", $output) . "\n");
}

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

	$envPath = "./ubuntu20-fs/root/Y2TB/config/config.env";
	$envExamplePath = "./ubuntu20-fs/root/Y2TB/config/config.env.example";
	if (!file_exists($envPath)) {
		if (file_exists($envExamplePath)) {
			system("mkdir -p ./ubuntu20-fs/root/Y2TB/config");
			copy($envExamplePath, $envPath);
		} else {
			system("mkdir -p ./ubuntu20-fs/root/Y2TB/config && >" . $envPath);
		}
	}

	while (true) {
		$configEnv = readEnv($envPath);
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
			$currentBotname = isset($configEnv["Y2TB_CFG_BOT_INFO_BOTNAME"]) ? $configEnv["Y2TB_CFG_BOT_INFO_BOTNAME"] : "Y2TBbot";
			$currentPrefix = isset($configEnv["Y2TB_CFG_FACEBOOK_PREFIX"]) ? $configEnv["Y2TB_CFG_FACEBOOK_PREFIX"] : "/";
			$currentSelfListen = isset($configEnv["Y2TB_CFG_FACEBOOK_SELFLISTEN"]) ? $configEnv["Y2TB_CFG_FACEBOOK_SELFLISTEN"] : "false";

			echo($cyan.$lang["mn_4"]."\n");
			echo($magenta.$line4);
			$temp = readline($cyan.$lang["askName"]." (".$yellow.$currentBotname.$cyan."): ".$yellow);
			$newBotname = $temp != "" ? $temp : $currentBotname;
			echo($cyan."\n".$lang["yName"].": ".$yellow.$newBotname."\n");

			echo($magenta.$line4);
			$temp = readline($cyan.$lang["askPrefix"]." (".$yellow.$currentPrefix.$cyan."): ".$yellow);
			$newPrefix = $temp != "" ? $temp : $currentPrefix;
			echo($cyan."\n".$lang["yPrefix"].": ".$yellow.$newPrefix."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = ($currentSelfListen === "true" || $currentSelfListen === true) ? $lang["on"] : $lang["off"];
			$temp = strtolower(readline($cyan.$lang["askSelf"].$s." (".$yellow.$ss.$cyan."): ".$yellow));

			if ($temp == "y") {
				$newSelfListen = "true";
			} elseif ($temp == "n") {
				$newSelfListen = "false";
			} else {
				$newSelfListen = $currentSelfListen;
			}
			$s = ($newSelfListen === "true") ? $lang["on"] : $lang["off"];
			echo($cyan."\n".$lang["ySelf"].": ".$yellow.$s."\n");

			echo($magenta.$line4);
			$temp = strtolower(readline($cyan.$lang["sSave"]." (y/n): ".$yellow));
			if ($temp == "n") break;

			$configEnv["Y2TB_CFG_BOT_INFO_BOTNAME"] = $newBotname;
			$configEnv["Y2TB_CFG_FACEBOOK_PREFIX"] = $newPrefix;
			$configEnv["Y2TB_CFG_FACEBOOK_SELFLISTEN"] = $newSelfListen;
			$configEnv["Y2TB_CFG_BOT_INFO_LANG"] = $codel;

			writeEnv($envPath, $configEnv);
			print_delay($green."\n".$lang["saveSuccess"], 250);
			sleep(1);
			return;
		}
		if ($act == 2) {
			while(true) {
				$configEnv = readEnv($envPath);
				$adminVal = isset($configEnv["Y2TB_CFG_FACEBOOK_ADMIN"]) ? $configEnv["Y2TB_CFG_FACEBOOK_ADMIN"] : "[]";
				$adminArr = json_decode($adminVal, true);
				if (!is_array($adminArr)) {
					$adminArr = [];
				}
				clear();
				echo($cyan.$lang["admin_edit"]."\n");
				echo($magenta.$line4);
				echo($cyan.$lang["list_admin"].":\n".$yellow);
				foreach ($adminArr as $i => $admin) {
					echo(($i+1).". ".$admin."\n");
				}
				echo($magenta.$line4);
				echo($yellow."* ".$green.$lang["edit_admin"]."\n\n");
				$inp = (int) readline($cyan.$lang["edit_ad_in"].": ".$yellow);
				if ($inp == 0) break;
				if ($inp > 0 && $inp <= count($adminArr)) {
					echo($magenta.$line4);
					$temp = strtolower(readline($cyan.$lang["rm_ad_sure"].$yellow.$adminArr[$inp-1].$cyan." (y/n): ".$yellow));
					if ($temp == "y") {
						unset($adminArr[$inp-1]);
					}  
				}
				else if ($inp > count($adminArr)) {
					echo($magenta.$line4);
					$adm = readline($cyan.$lang["add_admin"].": ".$yellow);
					if (trim($adm) != "") {
						$adminArr[] = trim($adm);
					}
				}
				$configEnv["Y2TB_CFG_FACEBOOK_ADMIN"] = json_encode(array_values($adminArr));
				writeEnv($envPath, $configEnv);
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
				
				if (!file_exists("./ubuntu20-fs/root/Y2TB/config/fbstate.json")) {
					system("mkdir -p ./ubuntu20-fs/root/Y2TB/config && >./ubuntu20-fs/root/Y2TB/config/fbstate.json");
				}
				
				file_put_contents("./ubuntu20-fs/root/Y2TB/config/fbstate.json", json_encode($fbstate, JSON_PRETTY_PRINT));
				
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

	$envPath = "./ubuntu20-fs/root/Y2TB/config/config.env";
	$envExamplePath = "./ubuntu20-fs/root/Y2TB/config/config.env.example";
	if (!file_exists($envPath)) {
		if (file_exists($envExamplePath)) {
			system("mkdir -p ./ubuntu20-fs/root/Y2TB/config");
			copy($envExamplePath, $envPath);
		} else {
			system("mkdir -p ./ubuntu20-fs/root/Y2TB/config && >" . $envPath);
		}
	}

	while (true) {
		$configEnv = readEnv($envPath);
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
			$currentDataSaveTime = isset($configEnv["Y2TB_CORE_MAIN_BOT_DATASAVETIME"]) ? $configEnv["Y2TB_CORE_MAIN_BOT_DATASAVETIME"] : "5";
			$currentDevelopMode = isset($configEnv["Y2TB_CORE_MAIN_BOT_DEVELOPMODE"]) ? $configEnv["Y2TB_CORE_MAIN_BOT_DEVELOPMODE"] : "false";
			$currentToggleLog = isset($configEnv["Y2TB_CORE_MAIN_BOT_TOGGLELOG"]) ? $configEnv["Y2TB_CORE_MAIN_BOT_TOGGLELOG"] : "true";
			$currentToggleDebug = isset($configEnv["Y2TB_CORE_MAIN_BOT_TOGGLEDEBUG"]) ? $configEnv["Y2TB_CORE_MAIN_BOT_TOGGLEDEBUG"] : "false";
			$currentUserAgent = isset($configEnv["Y2TB_CORE_FACEBOOK_USERAGENT"]) ? $configEnv["Y2TB_CORE_FACEBOOK_USERAGENT"] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36";

			echo($cyan.$lang["mn_5"]."\n");
			echo($magenta.$line4);
			$temp = readline($cyan.$lang["askTimeSaveData"]." (".$yellow.$currentDataSaveTime.$cyan."): ".$yellow);
			$newDataSaveTime = $temp != "" ? $temp : $currentDataSaveTime;
			echo($cyan."\n".$lang["yTimeSaveData"].": ".$yellow.$newDataSaveTime."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = ($currentDevelopMode === "true" || $currentDevelopMode === true) ? $lang["on"] : $lang["off"];
			$temp = strtolower(readline($cyan.$lang["askDev"].$s." (".$yellow.$ss.$cyan."): ".$yellow));

			if ($temp == "y") {
				$newDevelopMode = "true";
			} elseif ($temp == "n") {
				$newDevelopMode = "false";
			} else {
				$newDevelopMode = $currentDevelopMode;
			}
			$s = ($newDevelopMode === "true") ? $lang["on"] : $lang["off"];
			echo($cyan."\n".$lang["yDev"].": ".$yellow.$s."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = ($currentToggleLog === "true" || $currentToggleLog === true) ? $lang["on"] : $lang["off"];
			$temp = strtolower(readline($cyan.$lang["askLog"].$s." (".$yellow.$ss.$cyan."): ".$yellow));

			if ($temp == "y") {
				$newToggleLog = "true";
			} elseif ($temp == "n") {
				$newToggleLog = "false";
			} else {
				$newToggleLog = $currentToggleLog;
			}
			$s = ($newToggleLog === "true") ? $lang["on"] : $lang["off"];
			echo($cyan."\n".$lang["yLog"].": ".$yellow.$s."\n");

			echo($magenta.$line4);
			$s = " [".$yellow."y ".$cyan."(".$lang["on"]."); ".$yellow."n ".$cyan."(".$lang["off"].")"."]";
			$ss = ($currentToggleDebug === "true" || $currentToggleDebug === true) ? $lang["on"] : $lang["off"];
			$temp = strtolower(readline($cyan.$lang["askDebug"].$s." (".$yellow.$ss.$cyan."): ".$yellow));

			if ($temp == "y") {
				$newToggleDebug = "true";
			} elseif ($temp == "n") {
				$newToggleDebug = "false";
			} else {
				$newToggleDebug = $currentToggleDebug;
			}
			$s = ($newToggleDebug === "true") ? $lang["on"] : $lang["off"];
			echo($cyan."\n".$lang["yDebug"].": ".$yellow.$s."\n");

			echo($magenta.$line4);
			echo($cyan.$lang["nUserAgent"].": ".$yellow.$currentUserAgent."\n\n");
			$temp = readline($cyan.$lang["askUserAgent"].": ".$yellow);
			$newUserAgent = $temp != "" ? $temp : $currentUserAgent;
			echo($cyan."\n".$lang["yUserAgent"].": ".$yellow.$newUserAgent."\n");

			echo($magenta.$line4);
			$temp = strtolower(readline($cyan.$lang["sSave"]." (y/n): ".$yellow));
			if ($temp == "n") break;

			$configEnv["Y2TB_CORE_MAIN_BOT_DATASAVETIME"] = $newDataSaveTime;
			$configEnv["Y2TB_CORE_MAIN_BOT_DEVELOPMODE"] = $newDevelopMode;
			$configEnv["Y2TB_CORE_MAIN_BOT_TOGGLELOG"] = $newToggleLog;
			$configEnv["Y2TB_CORE_MAIN_BOT_TOGGLEDEBUG"] = $newToggleDebug;
			$configEnv["Y2TB_CORE_FACEBOOK_USERAGENT"] = $newUserAgent;

			writeEnv($envPath, $configEnv);
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
				$temp = getListFile("./ubuntu20-fs/root/Y2TB", true, ["node_modules", ".git", "logs"]);
				$temp2 = getListFile("./ubuntu20-fs/root/Y2TB", false, ["node_modules", ".git", "logs"]);
				foreach ($temp2["folder"] as $i) system("cp -r ".$i." ".$dir."Y2TB/");
				echo($yellow.$lang["syncTotal"].": ".$cyan.(count($temp["file"])-7)." file & ".(count($temp["folder"]))." folder\n\n");
				readline($default.$lang["please"].$lang["press_enter"]);
			} else {
				echo($green.$lang["stSyncFrStorage"]."...\n\n");
				$temp = getListFile($dir."Y2TB/", true, ["node_modules", ".git", "logs"]);
				$temp2 = getListFile("./ubuntu20-fs/root/Y2TB", false, ["node_modules", ".git", "logs"]);
				foreach ($temp2["folder"] as $i) system("rm -r ".$i);
				$temp2 = getListFile($dir."Y2TB/", false, ["node_modules", ".git", "logs"]);
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
			$temp = getListFile("./ubuntu20-fs/root/Y2TB", true, ["node_modules", ".git", "logs"]);
			$temp2 = getListFile("./ubuntu20-fs/root/Y2TB", false, ["node_modules", ".git", "logs"]);
			foreach ($temp2["folder"] as $i) system("cp -r ".$i." ".$dir."Y2TB/");
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