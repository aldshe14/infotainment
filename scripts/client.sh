#!/bin/bash
sudo apt-get update 
#sudo echo "deb file:///var/rep ./" > /etc/apt/sources.list
#sudo mkdir /var/rep
#sudo mount 10.2.7.10:/var/local/rep /var/rep
#sudo apt-key add /var/rep/PublicKey
sudo apt-get upgrade 
sudo apt-get install -y vim
sudo apt-get install -y git
sudo apt-get install -y apache2
sudo apt-get install -y mariadb-server-10.0
sudo apt-get install -y php php-mbstring php-mysqldb
sudo chown -R pi /var/www/html/
sudo chown -R 770 /var/www/html/
cd /var/www/html
git clone https://github.com/aldshe14/infotainment
sudo chown pi ./*
sudo sh ./installation_opencv_raspberry.sh
sudo mysql -e "create database infotainment_system"
sudo mysql -e "create user 'infotainment'@'localhost' identified by '1nf0tainment'"
sudo mysql -e "grant all privileges on infotainment_system.* to 'infotainment_system'@'10.%' identified by '1nf0tainment'"
sudo mysql -e "flush privileges";
echo "[mysqld] bind-address=0.0.0.0" >> /etc/mysql/my.cnf

sudo apt-get install -y unclutter
echo "@xscreensaver -no-splash  # comment to disable screensaver
@xset s off
@xset -dpms
@xset s noblank
@unclutter -idle 0
@chromium-browser –incognito –kiosk http://localhost/infotainment/display" >> /etc/xdg/lxsession/LXDE-pi/autostart

sudo reboot
