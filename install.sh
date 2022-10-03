clear
rm ./install.sh
echo + Setup storage:
echo 
echo y|termux-setup-storage
echo 
echo + Start update package:
echo 
pkg upgrade -n
echo 
echo + Install PHP
echo 
pkg install php -y
pkg install openssl -y
echo 
echo +Fetch install file
echo
curl https://raw.githubusercontent.com/HerokeyVN/VBLN_Bot_Termux/main/Termux/vbln.php --output vbln.php
