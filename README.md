# Modern Prompter
If you've ever made a YouTube video before, or if you like watching videos on YouTube, then you've probably noticed that content creators all have different ways of recording the dialog for their videos. Some are able to make the dialog up off the top of their heads, some memorize their entire script, and some--like me-- memorize one line at a time before delivering it to the camera. All of these are great options if they work for you, but I didn't want to settle for that. I found that recording line by line was not only time consuming, but it makes for a lot of post-processing to get it playing back smoothly.

To fix this, I decided to make a one-way mirror teleprompter, an invention made popular by Hubert Schlafly during the 1956 Democratic National Convention, so I could easily and smoothly record the dialog for my videos. These types of prompters work by reflecting text off a piece of glass with a camera behind it. As long as the camera side of the glass is darker than the speaker side, then the camera will see right through the glass without any glare. 

YouTube Video: https://www.youtube.com/watch?v=d-wVgWHPzgA

**Don't forget to subscribe to my channel so you don't miss any new videos!**

**[Modern Hobbyist](https://www.youtube.com/channel/UCjgA1ehfjkZ4WMa5Cw9f1LA)**

## Parts
### 3d Prints
**3d Printable Case:**

https://www.thingiverse.com/thing:4225551

**Bracket to hold display to Case**

...

### Purchased
**Raspberry Pi 4:** (You don't need a pi 4, but that is the most recent model)

https://amzn.to/2U5ZZwF


**7" Touchscreen Display:**

https://amzn.to/2xxYY8P

**USB Wifi Dongle:** (If you're RPi doesn't have built-in wifi)

https://amzn.to/2QhAUNU

**D-Rings for phone mount:**

https://amzn.to/2wY3EVi

**SmartPhone mount:**

https://amzn.to/2U8QZqC

**My Cheap Tripod:**

https://amzn.to/2IKQKwu

## Overview

For the brains of this project, I used an old Raspberry Pi 2 and a 7" touchscreen display. With a background in Web Development, I decided to make a Laravel website to act as the prompter software. The pi hosts the website on post 8000, so I can connect to it using any computer that's on the same network as the pi. This allows you to upload transcripts, manage them, and play them back. The playback page reverses the text and scrolls it upside down so that it can be read as it reflects off the glass. There are a few controls on this page that allow you to adjust the scroll speed, pause the text, and just to the beginning or end of the script. I configured the Pi to boot into Chromium Kiosk mode and start up the web server, and put everything into a case that I designed in Fusion360.

The case serves multiple purposes. It holds the electronics and glass panel, it has a small hole that I can use to mount my iPhone to it--which I currently record with-- and it has a small square on the bottom that is compatible with my universal tripod quick-release mount. Once that finished printing, I mounted everything into the case and plugged it in--and that't it! I now have a fully functioning prompter that helps me to create better videos faster, with less post-processing.

## Instructions
### Raspberry Pi Setup
1. Download and install Raspbian Lite
    1. Raspbian Lite: https://www.raspberrypi.org/downloads/raspbian/
    1. Installation Instructions: https://www.raspberrypi.org/documentation/installation/installing-images/README.md
1. Add WPA_Supplicants.conf (to automatically connect to WiFi)
    1. With the SD card inserted into your computer, create a file called WPA_Supplicants.conf on the SD card
    1. Add the following to WPA_Supplicants (Replace SSID and PSK with your WiFi credentials):
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
1. Add extensionless ‘ssh’ file to your SD card
    1. Create a file called `ssh` with no extension on your SD card
    1. The file will have no contents, it will get deleted by the Pi on boot, but it will enable SSH.
1. Insert SD Card into RPi & Boot
    1. This will boot the Pi into a headless version of Raspbian, connect to the WiFi and enable SSH.

### Web Server & MySQL Setup
1. SSH into the Pi
    ```
    $ ssh pi@<rpi ip address>
    ```
1. Configure Raspbian
    ```
    $ sudo raspi-config
    ```
    1. Change password
    1. Expand file system
    1. Exit Raspi-config
1. Update apt-get
    ```
    $ sudo apt-get update
    ```
1. Install PHP
    ```
    $ sudo apt-get install php
    ```
1. Install mbstring, dom (xml), curl and mysql extensions
    ```
    $ sudo apt-get install php-mbstring php7.3-dom php-curl php-mysql
    ```
    1. Y to continue
    1. Might need to change the version of dom extension you install to match the newest version of php.
1. Install composer
    ```
    $ curl -sS https://getcomposer.org/installer | php
    $ sudo mv composer.phar /usr/local/bin/composer
    ```
1. Install mysql
    ```
    $ sudo apt-get install mariadb-server mariadb-client -y
    ```
1. Enter Mysql prompt
    ```
    $ sudo mysql -u root -p
    ```
    1. Password: root
1. Configure Mysql user
    ```
    CREATE USER 'teleprompter_u'@'localhost' IDENTIFIED BY 'secret';
    GRANT ALL PRIVILEGES ON * . * TO ‘teleprompter_u’@'localhost';
    FLUSH PRIVILEGES;
    Quit;
    ```
1. Log in to new 
    ```
    $ mysql -u teleprompter_u -p 
    ```
    1. Password: secret
    ```
    CREATE SCHEMA teleprompter;
    exit;
    ```
1. Install Git
    ```
    $ sudo apt-get install git
    ```
1. Clone the repo
    ```
    $ git clone https://github.com/csteamengine/teleprompter.git
    ```
1. Cd into the repo
    ```
    $ cd ~/ModernPrompter
    ```
1. Install Composer
    ```
    $ composer install
    ```
    1. Composer update takes too much processing power and would take ages on the pi, so make sure to run this on your development machine and just do install on the pi.
1. Create the .env file
    ```
    $ cp .env.example to .env
    ```
1. Set the MySQL User credentials
    ```
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=teleprompter
    DB_USERNAME=teleprompter_u
    DB_PASSWORD=secret
    ```
1. Clear application cache
    ```
    $ php artisan config:clear
    ```
1. Migrate and seed databases
    ```
    $ php artisan migrate --seed
    ```

### Auto Boot Setup
1. Install chromium-browser
    ```
    $ sudo apt-get update
    $ sudo apt-get upgrade
    $ sudo apt-get dist-upgrade
    $ sudo apt-get install chromium-browser unclutter lxde
    ```
    1. Y to continue
1. Raspi config
    ```
    $ sudo raspi-config
    ```
    1. Enable auto login
    ```
    3. Boot Options -> Desktop/CLI -> Desktop/Autologin
    ```
    1. Finish raspi-config
1. Edit ~/.config/lxsession/LXDE/autostart
    ```
    $ sudo nano ~/.config/lxsession/LXDE/autostart
    ```
    1. Add the following to the file
    ```
    @xset s off
    @xset -dpms
    @xset s noblank
    @sed -i 's/"exited_cleanly": false/"exited_cleanly": true/' ~/.config/chromium-browser Default/Preferences
    @sh /home/pi/.start_server.sh
    ```
1. Add ".start_server.sh" Script to /home/pi/
    1. Create the new .sh file
    ```
    $ sudo nano ~/.start_server.sh
    ```
    1. Add the following to .start_server.sh
    ```
    #! /bin/bash
    # Starts the php server for the teleprompter
    IPADDR=$(hostname -I | awk '{print $1}')
    (cd ~/teleprompter && php artisan serve --host=$IPADDR &)
    
    # Starts chrome in Kiosk mode and navigates to the local server
    URL="http://${IPADDR}:8000"
    DISPLAY=:0 chromium-browser --noerrdialogs --kiosk "$URL" --incognito --disable-translate
    ```
1. Configure the server to startup at boot
    1. Edit the .bash_profile
    ```
    $ sudo nano ~/.bash_profile
    ```
    1. Add the following:
    ```
    IPADDR=$(hostname -I | awk '{print $1}')
    URL="http://${IPADDR}:8000"
    ```
1. Reboot
```
Sudo reboot
```

That's it! It should now auto login to chromium and display the local laravel application hosted on port 8000.

### Use the thing!
1. To use this as a teleprompter, navigate to `<yourpi'sipaddress>:8000` in your computer's browser.
    1. The ip address of the Pi will be displayed at the bottom of the webpage, so if you have a touchscreen connected, you can see it.
    1. Otherwise, you can find it by checking which IP's are connected to your WiFi router or Modem.
1. Upload files and name them
1. Play back files by clicking the play button on the touchscreen
1. Delete files by clicking the delete button
### Playback
1. The playback screen has a few limited controls that allow you to change the speed, jump to the beggining or end of the script, and pause the script.
1. Return to the homepage by clicking the `Home` button.


