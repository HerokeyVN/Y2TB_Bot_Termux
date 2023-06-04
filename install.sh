clear
rm ./install.sh
echo + Setup storage:
echo 
echo y|termux-setup-storage 
echo 
echo + Start update package: 
echo 
pkg upgrade
echo
echo + Install PHP
echo
pkg install php -y
echo
echo + Install Wget
echo
pkg install wget -y
echo 
echo +Fetch install file
echo 
curl https://raw.githubusercontent.com/HerokeyVN/Y2TB_Bot_Termux/main/Termux/y2tb.php --output y2tb.php