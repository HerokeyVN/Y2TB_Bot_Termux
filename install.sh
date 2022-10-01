clear
rm ./install.sh
echo + Setup storage:
echo 
echo y|termux-setup-storage
echo 
echo + Start upgrade package:
echo 
echo y|pkg update --forcd_overwrite
echo y|pkg upgrade --forcd_overwrite
echo 
echo + Install PHP
echo 
echo y|pkg install php 
echo 
echo + Install Wget
echo 
pkg install wget 
echo 
echo +Fetch install file
echo
wget https://raw.githubusercontent.com/HerokeyVN/VBLN_Bot_Termux/main/Termux/vbln.php
