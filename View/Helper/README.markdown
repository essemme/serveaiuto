CakePHP google map v3 helper / wrapper
======================================
v1.2
@cakephp 2.0

most important changes
- link() creates now actual links - use url() for urls
- usage of array containers in order to use custom js more easily 
- geolocate-feature for state-of-the-art-browsers
- custom icons

for most current manual see
- http://www.dereuromark.de/2010/12/21/googlemapsv3-cakephp-helper/
- the test case (contains several use cases!)

Introduction
------------
This is a helper that is made upon google maps api version >= 3.0
and this depends on use of JQuery

Dependency
-----------

CakePHP 1.3
The script depends on using jquery, so please add jquery to the layout
or add it from
   
   http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js
   
   
Load Helper
-----------
	On the app_contoller.php for globally adding the helper
	
	<?php 	
		class AppController extends Contoller{
			public $helpers = array('Html','Javascript','GoogleMapV3');
		}	
	?>

	load it on a 
	<?php 	
		class DemoController extends AppContoller{
			
			function map() {
				$this->helpers[] = 'GoogleMapV3';
				#	rest of your code		
			}
		}	
	?>
	

Usage
------------

Add this to your view (it assumes you already included Jquery!!! If you didnt yet, do that before including the following snippet):
	
	<?php
		echo '<script type="text/javascript" src="'.$this->GoogleMapV3->apiUrl().'"></script>';
	?>
	
Or use "autoScript" => true for the next step.

Firstly create a div for google map. Give it a css height and width:

	<?php echo $this->GoogleMapV3->map(array('div'=>array('id'=>'my_map', 'height'=>'400', 'width'=>'100%'))); ?>


**Current Usage**

	1. Adding single/multiple markers
	2. Adding single/multiple infowindow
	3. Adding events on marker to show infowindow
	4. Map Links
	5. StaticMaps
	
### 1. Adding single/multiple markers

	To add a marker to the google maps pass an associative array
	<?php  
		$options = array(
	    'lat'=>48.95145,
  		'lng'=>11.6981,
			'icon'=> 'url_to_icon', # optional
			'title' => 'Some title', # optional
			'content' => '<b>HTML</b> Content for the Bubble/InfoWindow' # optional
		);

		//To add more than one marker use this multiple time
		// I use it inside a for loop for multiple markers
		$this->GoogleMapV3->addMarker($options);
	?>		 

### 2. (OPTIONAL) Adding single/multiple infowindow

	<?php 
		$options = array(
		    'lat'=>49.95144,
    		'lng'=>12.6981,
    		'content'=>'Thanks for using this'
		);
		
		$this->GoogleMapV3->addInfoWindow($options);
	?>

### 3. (OPTIONAL) Adding events on marker to show infowindow

	<?php 
		$marker = $this->GoogleMapV3->addMarker($options);
		$infoWindow = $this->GoogleMapV3->addInfoWindow($options);
		$this->GoogleMapV3->addEvent($marker, $infoWindow);
	?>
or
	<?php 
		$marker = $this->GoogleMapV3->addMarker($options);
		$custom = '...'; # js
		$this->GoogleMapV3->addCustomEvent($marker, $custom);
	?>

finally, now time to make the script

	<?php echo $this->GoogleMapV3->script() ?>


### addons and extended functionality

- custom javascript (for special click events and stuff)
- custom icons
- ...


### 4. Map Links

	<?php 
		$url = $this->GoogleMapV3->url(array('to'=>'Munich, Germany'));
		echo $this->Html->link('Visit Me', $url, array('target'=>'_blank'));
		
		# or directly:
		echo $this->GoogleMapV3->link('Visit Me', array('to'=>'Munich, Germany', 'from'=>'Berlin, Germany'), array('target'=>'_blank'));
	?>
	
	
### 5. StaticMaps

	<?php 
		# a simple image as map
		$markers = array(
			array('lat'=>48.2, 'lng'=>11.1),
			array('lat'=>48.1, 'lng'=>11.2)
		);
		$options = array(
			'size' => '500x400',
			'center' => true,
			'markers' => $this->GoogleMapV3->staticMarkers($markers)
		);
		$attr = array(
			'title'=>'Yeah'
		);
		echo $this->GoogleMapV3->staticMap($options, $attr);
		
		# you can even add an url to click on
		$attr['url'] = $this->GoogleMapV3->url(array('to'=>'Munich, Germany'));
		echo $this->GoogleMapV3->staticMap($options, $attr);
	?>
Instead of markers, paths can also be used.
Other options
- visible (locations that have to be in the map)
- center (without markers)
	
	
Test Files
-----------------

Test file included (they might not work right away because of special classes and functions)

To test put the helper in 
/app/Plugin/Tools/View/Helper/

the test file in
/app/Plugin/Tools/Test/Case/View/Helper/

open the following url in the browser:
http://yourdomain/test.php?show=cases&plugin=Tools

There you will be able to click on the GoogleMapV3 Test File.

If you want to test MY exact file you will need to change the class from MyCakeTestCase back to CakeTestCase.
Maybe some more modification will have to be made. So you can just as well create your own little test case and use mine as guideline :)

Testfiles are not only a great tool to ensure that the code is fine, it also may help in understanding how the code works.
So please at least take a look at the code in the test file in order to understand how to use it :)
If it still doesn't work, feel free to contact me. Also notify me about any mistake i made or enhancement you got! 
I will be happy to upgrade my code (you may fork the project and send me a direct pull request, as well).
