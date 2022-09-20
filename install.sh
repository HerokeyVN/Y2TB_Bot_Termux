echo + Setup storage:
echo 
echo y|termux-setup-storage
echo 
echo + Start upgrade package:
echo 
pkg upgrade -y
echo 
echo + Install PHP
echo 
echo y|pkg install php 
echo 
echo + Install Wget
echo 
pkg install Wget 
echo 
echo +Fetch install file
echo
wget https://raw.githubusercontent.com/HerokeyVN/VBLN_Bot_Termux/main/Termux/vbln.php
