<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as 
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, // [optional]
 * 		'mask' => 0666, // [optional] permission mask to use when creating cache files
 *	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *		'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 * Memcache (http://memcached.org/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => true, // [optional] set this to false for non-persistent connections
 * 		'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 *	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Wincache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 */
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Plugin' => array('/full/path/to/plugins/', '/next/full/path/to/plugins/'),
 *     'Model' =>  array('/full/path/to/models/', '/next/full/path/to/models/'),
 *     'View' => array('/full/path/to/views/', '/next/full/path/to/views/'),
 *     'Controller' => array('/full/path/to/controllers/', '/next/full/path/to/controllers/'),
 *     'Model/Datasource' => array('/full/path/to/datasources/', '/next/full/path/to/datasources/'),
 *     'Model/Behavior' => array('/full/path/to/behaviors/', '/next/full/path/to/behaviors/'),
 *     'Controller/Component' => array('/full/path/to/components/', '/next/full/path/to/components/'),
 *     'View/Helper' => array('/full/path/to/helpers/', '/next/full/path/to/helpers/'),
 *     'Vendor' => array('/full/path/to/vendors/', '/next/full/path/to/vendors/'),
 *     'Console/Command' => array('/full/path/to/shells/', '/next/full/path/to/shells/'),
 *     'Locale' => array('/full/path/to/locale/', '/next/full/path/to/locale/')
 * ));
 *
 */

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

Inflector::rules('plural', array(
	    'irregular' => array('organizzazione' => 'organizzazioni', 'volontario' => 'volontari',
	    		'richiesta' => 'richieste', 'tipo' => 'tipi', 'offerta' => 'offerte', 'provincia' => 'province',
                        'categoria' => 'categorie')
	    ,
	    'uninflected' => array('localita')//,'categorieeventi')
	    )
	);


Inflector::rules('singular', array(
            'irregular' => array('organizzazioni' => 'organizzazione', 'volontari' => 'volontario',
	    		'richieste' => 'richiesta', 'tipi' => 'tipo', 'offerte' => 'offerta', 'province' => 'provincia',
                        'categorie' => 'categoria' )    
	    ,
	    'uninflected' => array('localita')//,'categorieeventi')
	    )
	);

/**
 * Translation and Localization
 *#!# */
Configure::write('Languages.default', 'it');
$languages = array(
        'it',
	'en',
//	'sp',
//	'fr',
//	'de',
//	'jp',
//	'ch',
);
Configure::write('Languages.all', $languages);
/*^*/

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */
CakePlugin::load('DebugKit');
CakePlugin::load('Linkable');
CakePlugin::load('Tools');
CakePlugin::load('SignMeUp');
//CakePlugin::load('Searchable');
CakePlugin::load('AddressFinder');
CakePlugin::load('Bookmarklet');
CakePlugin::load('TwitterBootstrap');
CakePlugin::load('Facebook');
CakePlugin::load('Filter');

$config['Google'] = array(
                                'zoom' => 10,
                            //    'lat' => 51.1,
                            //    'lng' => 11.2,
                                'type' => 'H', # Roadmap, Satellite, Hybrid, Terrain
                                'size' => array('width'=>500, 'height'=>400),
                                'staticSize' => '500x450',
                                //'autoCenter' => true
                            );

Configure::write('GoogleMap',$config['Google']);

//$config['Role'] = array(
//    'superadmin' => 1,
//    'admin' => 2,
////    'moderator' => 3,
////    'helper' => 4,
//    'user' => 5,
//);
//
//Configure::write('Role',$config['Role']);

$config['AddressFinder'] = array(
        //map settings
        'height' => '300px',
        'width' => '350px',
        'default' => array('lat' => '44.8378942', 'lon' => '11.6204396'),
        //form fields settings
        'modelName' => 'Richiesta',
        'fields' => array('lat' => 'lat', 'lon' => 'lon', 'address' => 'localita_gmaps'),
        'latlonFieldsVisibility' => 'readonly', //'normal', 'readonly' or 'hidden' <- if hidden check your Security settings
        //rendering behaviour setting                
        'includeGoogleMapsScript' => true,
        'includeJQuery' => false,  //usually already included
        'renderFields' => false,  // render only the map and script, 
                                  // the form already has the required fields. If true, render the input fields too
        'preventSubmit' => false    // no submit on empty lat/lon;
    );
 
Configure::write('AddressFinder',$config['AddressFinder']);

 CakePlugin::load('DebugKit'); 

 
 