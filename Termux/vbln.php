<?php
@system("clear");
//colors
$red="\033[1;31m";
$green="\033[1;32m";
$yellow="\033[1;33m";
$blue="\033[1;34m";
$res="\033[1;35m";
$nau="\033[1;36m";
$while="\033[1;37m";
//program
$ggid="1-9v-_3Ye9SXAMvvTvlMSi-NzuQl6vfAp";
$link = "https://raw.githubusercontent.com/HerokeyVN/VBLN_Bot_Termux/main/Termux/install.php";
if (file_exists("install.php") == false){
  echo $red,("Start the installer download...\n");
  //sleep(2);
  echo $nau,("-------------------------\n"),$green;
  @system("wget ".$link." && php install.php", $exitcode);
  //echo $exitcode;
}
else {
  echo $red,("Start the installer update...\n");
  echo $nau,("-------------------------\n"),$green;
  //sleep(2);
  @system("rm ./install.php && wget ".$link." && php install.php", $exitcode);
};
if($exitcode != 0)
	echo $nau,("-------------------------\n").$red."Can't connect to Github.com. Please check the internet and try again late!\n";
?>
