#!/bin/bash
sudo passwd pi
#echo "1nf0tainment" | passwd --stdin 1nf0tainment
sudo sh -c 'ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev
update_config=1
country=GB

network={
	ssid="HTL-WiFi"
	psk="L3hrk0rp3r!"
	key_mgmt=WPA-PSK
}" > /etc/wpa_supplicant/wpa_supplicant.conf'

sudo apt-get update 
sudo apt-get upgrade 

sudo timedatectl set-timezone "Europe/Tirane"
sudo timedatectl

sudo apt-get install -y vim
sudo apt-get install -y git
sudo apt-get install -y apache2
sudo apt-get install -y mariadb-server-10.0
sudo apt-get install -y php php-mbstring php-mysqldb

sudo chown -R pi:pi /var/www/html/
sudo chown -R 770 /var/www/html/

cd /var/www/html
git clone https://github.com/aldshe14/infotainment
#sudo chown pi ./*
sudo mysql -e "create database infotainment_system"
sudo mysql -e "create user 'infotainment'@'localhost' identified by '1nf0tainment'"
sudo mysql -e "grant all privileges on infotainment_system.* to 'infotainment'@'localhost' identified by '1nf0tainment'"
sudo mysql -e "flush privileges";
echo "[mysqld] bind-address=0.0.0.0" >> /etc/mysql/my.cnf

sudo apt-get install -y unclutter
sudo sh -c '@xset s off
@xset -dpms
@xset s noblank
@chromium-browser –incognito –kiosk http://localhost/infotainment/display" > /etc/xdg/lxsession/LXDE-pi/autostart'

sudo sh -c '[Seat:*]
xserver-command=X -nocursor" >> /etc/lightdm/lightdm.conf'

sudo reboot
