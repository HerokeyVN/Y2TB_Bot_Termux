clear
rm ./install.sh
echo + Setup storage:
echo 
echo y|termux-setup-storage
echo 
echo + Start upgrade package:
echo 
apt update -y
echo 
echo + Install PHP
echo 
apt install php -y
echo 
echo +Fetch install file
echo
curl https://raw.githubusercontent.com/HerokeyVN/VBLN_Bot_Termux/main/Termux/vbln.php --output vbln.php
