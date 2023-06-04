<?php
@system("clear");
//cp -r /storage/0000-0000/Termux/* ~
//colors
$red = "\033[1;31m";
$green = "\033[1;32m";
$yellow = "\033[1;33m";
$blue = "\033[1;34m";
$res = "\033[1;35m";
$nau = "\033[1;36m";
$while = "\033[1;37m";
//program

$link = "https://raw.githubusercontent.com/HerokeyVN/Y2TB_Bot_Termux/main/Termux/install.php";
$colorl = "https://raw.githubusercontent.com/HerokeyVN/Y2TB_Bot_Termux/main/color.php";
if (file_exists("install.php") == false) {
	$content = file_get_contents("../usr/etc/bash.bashrc");
	if(!strpos($content, "php y2tb.php") && !strpos($content, "php vbln.php"))
		file_put_contents("../usr/etc/bash.bashrc", "\necho \"".$yellow."Type “php y2tb.php” to launch the bot menu\"", FILE_APPEND);
	if(strpos($content, "php vbln.php"))
		file_put_contents("../usr/etc/bash.bashrc", str_replace("vbln", "y2tb", $content));
	echo $red,
	("Start the installer download...\n");
	//sleep(2);
	echo $nau,
	("-------------------------\n"),
	$green;
	@system("curl ".$link." --output install.php", $exitcode);
	@system("curl ".$colorl." --output color.php", $exitcode);
	//echo $exitcode;
} else {
	echo $red,
	("Start the installer update...\n");
	echo $nau,
	("-------------------------\n"),
	$green;
	//sleep(2);
	@system("rm ./install.php && curl ".$link." --output install.php", $exitcode);
	@system("curl ".$colorl." --output color.php", $exitcode);
	//echo $exitcode;
};
if ($exitcode != 0)
	echo $nau, ("-------------------------\n").$red."Can't connect to Github.com. Please check the internet and try again late!\n";
else include("./install.php");
?>