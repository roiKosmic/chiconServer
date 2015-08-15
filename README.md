# chiconServer

This repository contains code for the Chicon application cloud and the chicon Web Server. It can be used by any Chicon compatible smartlamp. To build your chicon Smart Lamp, you can visit the chiconCube repository and wiki.

## 1- Chicon Server pre-requisite
 * You must have a web server (apache for example) running with php 5.5 enabled.
 * You must have a SMTP server configured in your php.ini to have mail() php function enabled
 * You must have a MySQL server running

## 2 - Chicon Server installation
### Web Server configuration
Copy the Chicon folder to your www folder
Create a symlink index.html to chicon/webSite/home.html or copy index.php to your root folder.
Copy h.php to your route folder.
h.php is equivalent to /chicon/webServices/hdwWS.php. It is the web service managing the Chic'on device communication with the Chic'on application cloud. As first device is based on arduino Uno +wifi shield, I needed to use less memoray as possible. Making a GET request to /h.php consumes less memory than same request to /chicon/webServices/hdwWS.php !

### MySQL configuration
Create a database named chicon_db
Update the chicon/class/settings.ini.php file with the MySQL credantials. MySQL user must have SELECT, INSERT, UPDATE rights on the chicon_db database
import the chicon/chicon_db-update2.sql dump file to create db structure and relevant data.

### E-mail configuration
E-mail are sent to a contact mailbox, you must update line 7, line 11, line 12 of the chicon/misc/mailFunc.php function with a e-mail address belonging to you.

## 3 - Test your Server
Go to your website, click on the application page, you should see 6 applications:
-Weather
-Demo
-Trafic forecast
-Reminder
-air quality
-tweeter

Your are know able to create a user on the register page.

## 4 - Licenses 
The add-on folder use the twiteroauth library made by Abraham Williams https://github.com/abraham/twitteroauth


