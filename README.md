ServeAiuto.org README
=====================
Getting Started
---------------
### Setting up a local development copy
#### Download Source Code
+ Download Latest Stable Version of CakePHP from http://cakephp.org/ and extract it
+ Get in the extracted directory (e.g. ~/cakephp-cakephp-4b81775) and clone this repository
  ``git clone git://github.com/stefanomanfredini/serveaiuto.git``
+ Clone needed plugin in ~/cakephp-cakephp-4b81775/plugins
``git clone https://github.com/cakephp/debug_kit DebugKit``
``git clone git://github.com/rafaelbandeira3/linkable.git Linkable``
``git clone git://github.com/dereuromark/tools.git Tools``
``git clone https://github.com/voidet/sign\_me\_up.git SignMeUp``
``git clone git://github.com/stefanomanfredini/AddressFinder-Helper---Plugin-for-cakephp-2.git AddressFinder``
``git clone https://github.com/stefanomanfredini/cakephp-2.x-BookmarkletHelper Bookmarklet``
``cd Tools``
``git checkout 2.0``

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
+ do the same with email.php.default

#### Logs and Cache directory
``mkdir -p ~/cakephp-cakephp-4b81775/serveaiuto/tmp/{logs,cache}``

#### Troubleshooting
If you get a "white response of death" look for error log in ~/cakephp-cakephp-4b81775/serveaiuto/tmp/logs/
