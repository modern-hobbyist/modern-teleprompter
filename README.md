# Modern Prompter
## Background
If you've ever made a YouTube video before, or if you like watching videos on YouTube, then you've probably noticed that content creators all have different ways of recording the dialog for their videos. Some are able to make the dialog up off the top of their heads, some memorize their entire script, and some--like me-- memorize one line at a time before delivering it to the camera. All of these are great options if they work for you, but I didn't want to settle for that. I found that recording line by line was not only time consuming, but it makes for a lot of post-processing to get it playing back smoothly.

To fix this, I decided to make a one-way mirror teleprompter, an invention made popular by Hubert Schlafly during the 1956 Democratic National Convention, so I could easily and smoothly record the dialog for my videos. These types of prompters work by reflecting text off a piece of glass with a camera behind it. As long as the camera side of the glass is darker than the speaker side, then the camera will see right through the glass without any glare. 

## Overview

For the brains of this project, I used an old Raspberry Pi 2 and a 7" touchscreen display. With a background in Web Development, I decided to make a Laravel website to act as the prompter software. The pi hosts the website on post 8000, so I can connect to it using any computer that's on the same network as the pi. This allows you to upload transcripts, manage them, and play them back. The playback page reverses the text and scrolls it upside down so that it can be read as it reflects off the glass. There are a few controls on this page that allow you to adjust the scroll speed, pause the text, and just to the beginning or end of the script. I configured the Pi to boot into Chromium Kiosk mode and start up the web server, and put everything into a case that I designed in Fusion360.

The case serves multiple purposes. It holds the electronics and glass panel, it has a small hole that I can use to mount my iPhone to it--which I currently record with-- and it has a small square on the bottom that is compatible with my universal tripod quick-release mount. Once that finished printing, I mounted everything into the case and plugged it in--and that't it! I now have a fully functioning prompter that helps me to create better videos faster, with less post-processing.

## Instructions
### Raspberry Pi Setup
1. Download and install Raspbian Lite
 
   a. Raspbian Lite: https://www.raspberrypi.org/downloads/raspbian/
 
   b. Installation Instructions: https://www.raspberrypi.org/documentation/installation/installing-images/README.md

4. Add WPA_Supplicants.conf (to automatically connect to WiFi)
 
   a. With the SD card inserted into your computer, create a file called WPA_Supplicants.conf on the SD card
 
   b. Add the following to WPA_Supplicants (Replace SSID and PSK with your WiFi credentials):

```
country=us
update_config=1
ctrl_interface=/var/run/wpa_supplicant

network={
 scan_ssid=1
 ssid="YourNetworkID"
 psk="YourPassword"
}
```
5. Add extensionless ‘ssh’ file to your SD card
- a. Create a file called `ssh` with no extension on your SD card
- b. The file will have no contents, it will get deleted by the Pi on boot, but it will enable SSH.
6. Insert into RPi & Boot
7. SSH into Pi
```
ssh pi@<rpi ip address>
Sudo raspi-config
```

8. Change password
9. Expand file system
10. Exit Raspi-config
11. Update
```
Sudo apt-get update
```
12. Install PHP
```
Sudo apt-get install php
```
13. Install mbstring, dom (xml) and curl extensions
```
Sudo apt-get install php-mbstring php7.3-dom php-curl php-mysql
```
- 1. Y to continue
- 2. Might need to change the version of dom extension you install to match the newest version of php.
14. Install composer
```
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```
15. Install mysql
```
sudo apt-get install mariadb-server mariadb-client -y
```
16. Enter Mysql prompt
```
Sudo mysql -u root -p
```
- 1. Password: root
17. Configure Mysql user
```
CREATE USER 'homestead'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON * . * TO ‘homestead’@'localhost';
FLUSH PRIVILEGES;
Quit;
```
18. Setup Mysql databases
```
Mysql -u homestead -p 
```
- 1. Password: secret
```
CREATE SCHEMA teleprompter;
exit;
```
19. Install Git
```
Sudo apt-get install git
```
20. Clone the repo
```
Git clone https://github.com/csteamengine/teleprompter.git
```
21. Cd into the repo
22. Install Composer
```
Composer install
```
- 1. Composer update takes too much processing power and would take ages on the pi, so make sure to run this on your development machine and just do install on the pi.
23. Create the .env file
```
Cp .env.example to .env
```
24. Clear application cache
```
Php artisan config:clear
```
25. Migrate and seed databases
```
Php artisan migrate --seed
```
26. Get Pi IP address
```
Hostname -I
```
27. Run the server
```
php artisan serve --host=YOUR-PI-IP
```

## Auto Boot Setup
1. Update apt-get
```
sudo apt-get update
sudo apt-get upgrade
sudo apt-get dist-upgrade
sudo apt-get install chromium-browser unclutter lxde
```
- 1. Y to continue
2. Raspi config
```sudo raspi-config```
3. Boot options
- 1. Desktop/CLI
- 2. Desktop/Autologin
4. Edit ~/.config/lxsession/LXDE/autostart
```
Sudo nano ~/.config/lxsession/LXDE/autostart
```
- 1. Add the following to the autostart file
```
@xset s off
@xset -dpms
@xset s noblank
@sed -i 's/"exited_cleanly": false/"exited_cleanly": true/' ~/.config/chromium-browser Default/Preferences
@chromium-browser --noerrdialogs --kiosk https://blockdev.io --incognito --disable-translate
```
- 2. Set it to the correct url http://YOURIPADDRESS:8000
5. Configure the server to startup at boot
- 3. Edit the .bash_profile with the following
```
(cd ~/Documents/teleprompter && php artisan serve --host=10.0.1.37 &)
```
6. Reboot
```
Sudo reboot
```

