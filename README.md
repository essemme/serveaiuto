ServeAiuto.org README
=====================
Abstract
--------
ServeAiuto.org (Need Help) is a Web site built with CakePHP for coordinating requests and offers of aid in Emilia-Romagna, Italy area which has been recently hit by earthquake.

Getting Started
---------------
### Setting up a local development copy
#### Download Source Code
+ Download Latest Stable Version of CakePHP from http://cakephp.org/ and extract it
+ Get in the extracted directory (e.g. ~/cakephp-cakephp-4b81775) and clone this repository
  ``git clone git://github.com/stefanomanfredini/serveaiuto.git``
+ Clone needed plugin in ~/cakephp-cakephp-4b81775/plugins as shown in https://gist.github.com/2941536

#### Configuration
##### Web Server
+ Configure your web server with ~/cakephp-cakephp-4b81775/serveaiuto/webroot/ as DocumentRoot
+ Enable mod\_rewrite If using Apache

##### MySQL
+ Create a new empty database
+ Run SQL files contained in ~/cakephp-cakephp-4b81775/serveaiuto/Config/Schema/ on it

##### CakePHP configuration files
+ cd ~/cakephp-cakephp-4b81775/serveaiuto/Config
+ copy database.php.default to database.php and change settings as needed
+ do the same with core.php.default, email.php.default and facebook.php.default

#### Logs and Cache directory
``mkdir -p ~/cakephp-cakephp-4b81775/serveaiuto/tmp/{logs,cache}``

#### Troubleshooting
+ If you get a "white response of death" look for error log in ~/cakephp-cakephp-4b81775/serveaiuto/tmp/logs/
+ Enable debugging in core.php ``Configure::write('debug', 2);`` instead of 0
