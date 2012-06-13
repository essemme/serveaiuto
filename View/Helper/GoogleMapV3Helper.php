<?php
/**
 * This is a CakePHP helper that helps users to integrate google map v3
 * into their application by only writing php code. this helper depends on JQuery
 *
 * @author Rajib Ahmed
 * @version 0.10.12
 *
 * enhanced/modified by Mark Scherer
 */
 /**
 * PHP5 / CakePHP1.3
 *
 * @author        Mark Scherer
 * @link          http://www.dereuromark.de/2010/12/21/googlemapsv3-cakephp-helper/
 * @package       tools plugin
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * fixed brackets, spacesToTabs, indends, some improvements, supports multiple maps now.
 * now capable of resetting itself (full or partly) for multiple maps on a single view
 *
 * CodeAPI: 		http://code.google.com/intl/de-DE/apis/maps/documentation/javascript/basics.html
 * Icons/Images: 	http://gmapicons.googlepages.com/home
 *
 * v1.2
 * 2011-10-12 ms
 */
class GoogleMapV3Helper extends AppHelper {

	public static $MAP_COUNT = 0;
	public static $MARKER_COUNT = 0;
	public static $ICON_COUNT = 0;
	public static $INFO_WINDOW_COUNT = 0;
	public static $INFO_CONTENT_COUNT = 0;

	const API = 'maps.google.com/maps/api/js?';
	const STATIC_API = 'maps.google.com/maps/api/staticmap?';

	const TYPE_ROADMAP = 'R';
	const TYPE_HYBRID = 'H';
	const TYPE_SATELLITE = 'S';
	const TYPE_TERRAIN = 'T';

	public $types = array(
		self::TYPE_ROADMAP => 'ROADMAP',
		self::TYPE_HYBRID => 'HYBRID',
		self::TYPE_SATELLITE => 'SATELLITE',
		self::TYPE_TERRAIN => 'TERRAIN'
	);

	/**
	 * Cakephp builtin helper
	 *
	 * @var array
	 */
	public $helpers = array('Html', 'Js');

	/**
	 * google maker config instance variable
	 *
	 * @var array
	 */
	public $markers = array();

	public $infoWindows = array();

	public $infoContents = array();

	public $icons = array();

	public $matching = array();
	//public $iconMatching = array();

	public $map = '';

	protected $_mapIds = array(); # remember already used ones (valid xhtml contains ids not more than once)

	/**
	 * settings of the helper
	 * @var array
	 */
	protected $_defaultOptions = array(
		'zoom' =>null, # global, both map and staticMap
		'lat' => null, # global, both map and staticMap
		'lng' => null, # global, both map and staticMap
		'type' => self::TYPE_ROADMAP,
		'map'=>array(
			'api' => null,
			'streetViewControl' => false,
			'navigationControl' => true,
			'mapTypeControl' => true,
			'scaleControl' => true,
			'scrollwheel' => false,
			'keyboardShortcuts' => true,
			//'zoom' =>5, # deprecated as default value, uses global one if missing
			//'type' =>'R', # deprecated as default value, uses global one if missing
			//'lat' => 51, # deprecated as default value, uses global one if missing
			//'lng' => 11, # deprecated as default value, uses global one if missing
			'typeOptions' => array(),
			'navOptions' => array(),
			'scaleOptions' => array(),
			'defaultLat' => 51, # only last fallback, use Configure::write('Google.lat', ...); to define own one
			'defaultLng' => 11, # only last fallback, use Configure::write('Google.lng', ...); to define own one
			'defaultZoom' => 5,
		),
		'staticMap' => array(
			'size' => '300x300',
			//'type' =>'R', # deprecated as default value, uses global one if missing
			//'zoom' => 12 # deprecated as default value, uses global one if missing
			//'lat' => 51, # deprecated as default value, uses global one if missing
			//'lng' => 11, # deprecated as default value, uses global one if missing
			'format' => 'png',
			'mobile' => false,
			//'shadow' => true # for icons
		),
		'geolocate' => false,
		'sensor' => false,
		'language' => null,
		'region' => null,
		'showMarker' => true,
		//'showInfoWindow' => true,
		'infoWindow' => array(
			'content'=>'',
			'useMultiple'=>false, # Using single infowindow object for all
			'maxWidth'=>300,
			'lat'=>null,
			'lng'=>null,
			'pixelOffset' => 0,
			'zIndex' => 200,
			'disableAutoPan' => false
		),
		'marker'=>array(
			//'autoCenter' => true,
			'icon' => null, # => default (red marker) //http://google-maps-icons.googlecode.com/files/home.png
			'title' => null,
			'shadow' => null,
			'shape' => null,
			'zIndex' => null,
			'draggable' => false,
			'cursor' => null,
			'directions' => false # add form with directions
		),
		'div'=>array(
			'id'=>'map_canvas',
			'width' => '100%',
			'height' => '400px',
			'class' => 'map',
			'escape' => true
		),
		'event'=>array(
		),
		'animation' => array(
			//TODO
		),
		'callbacks' => array(
			'geolocate' => null //TODO
		),
		'plugins' => array(
			'keydragzoom' => false, # http://google-maps-utility-library-v3.googlecode.com/svn/tags/keydragzoom/
			'markermanager' => false, # http://google-maps-utility-library-v3.googlecode.com/svn/tags/markermanager/
			'markercluster' => false, # http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerclusterer/
		),
		'autoCenter' => false, # try to fit all markers in (careful, all zooms values are omitted)
		'autoScript' => false, # let the helper include the neccessary js script links
		'inline' => false, # for scripts
		'https' => null # auto detect
	);

	protected $_currentOptions =array();

	protected $_apiIncluded = false;
	protected $_gearsIncluded = false;
	protected $_located = false;


	public function __construct($View = null, $settings = array()) {
		parent::__construct($View, $settings);

		# read constum config settings
		$google = (array)Configure::read('Google');
		if (!empty($google['api'])) {
			$this->_defaultOptions['map']['api'] = $google['api'];
		}
		if (!empty($google['zoom'])) {
			$this->_defaultOptions['map']['zoom'] = $google['zoom'];
		}
		if (!empty($google['lat'])) {
			$this->_defaultOptions['map']['lat'] = $google['lat'];
		}
		if (!empty($google['lng'])) {
			$this->_defaultOptions['map']['lng'] = $google['lng'];
		}
		if (!empty($google['type'])) {
			$this->_defaultOptions['map']['type'] = $google['type'];
		}
		if (!empty($google['size'])) {
			$this->_defaultOptions['div']['width'] = $google['size']['width'];
			$this->_defaultOptions['div']['height'] = $google['size']['height'];
		}
		if (!empty($google['staticSize'])) {
			$this->_defaultOptions['staticMap']['size'] = $google['staticSize'];
		}
		# the following are convience defaults - if not available the map lat/lng/zoom defaults will be used
		if (!empty($google['staticZoom'])) {
			$this->_defaultOptions['staticMap']['zoom'] = $google['staticZoom'];
		}
		if (!empty($google['staticLat'])) {
			$this->_defaultOptions['staticMap']['lat'] = $google['staticLat'];
		}
		if (!empty($google['staticLng'])) {
			$this->_defaultOptions['staticMap']['lng'] = $google['staticLng'];
		}
		$this->_currentOptions = $this->_defaultOptions;
	}



/** Google Maps JS **/

	/**
	 * JS maps.google API url
	 * Like:
	 *  http://maps.google.com/maps/api/js?sensor=true
	 * Adds Key - more variables could be added after it with "&key=value&..."
	 * - region
	 * @param bool $sensor
	 * @param string $language (iso2: en, de, ja, ...)
	 * @param string $append (more key-value-pairs to append)
	 * @return string $fullUrl
	 * 2009-03-09 ms
	 */
	public function apiUrl($sensor = false, $api = null, $language = null, $append = null) {
		$url = $this->_protocol() . self::API;

		$url .= 'sensor=' . ($sensor ? 'true' : 'false');
		if (!empty($language)) {
			$url .= '&language='.$language;
		}
		/*
		if (!empty($this->key)) {
			$url .= '&key='.$this->key;
		}
		*/
		if (!empty($api)) {
			$this->_currentOptions['map']['api'] = $api;
		}
		if (!empty($this->_currentOptions['map']['api'])) {
			$url .= '&v='.$this->_currentOptions['map']['api'];
		}
		if (!empty($append)) {
			$url .= $append;
		}
		$this->_apiIncluded = true;
		return $url;
	}

	public function gearsUrl() {
		$this->_gearsIncluded = true;
		$url = $this->_protocol() . 'code.google.com/apis/gears/gears_init.js';
		return $url;
	}


	/**
	 * @return string $currentMapObject
	 * 2010-12-18 ms
	 */
	public function name() {
		return 'map'.self::$MAP_COUNT;
	}

	/**
	 * @return string $currentContainerId
	 * 2010-12-18 ms
	 */
	public function id() {
		return $this->_currentOptions['div']['id'];
	}

	/**
	 * make it possible to include multiple maps per page
	 * resets markers, infoWindows etc
	 * @param full: true=optionsAsWell
	 * @return void
	 * 2010-12-18 ms
	 */
	public function reset($full = true) {
		//self::$MAP_COUNT
		self::$MARKER_COUNT = self::$INFO_WINDOW_COUNT = 0;
		$this->markers = $this->infoWindows = array();
		if ($full) {
			$this->_currentOptions = $this->_defaultOptions;
		}
	}

	/**
	 * set the controls of current map
	 * @param array $controls:
	 * - zoom, scale, overview: TRUE/FALSE
	 *
	 * - map: FALSE, small, large
	 * - type: FALSE, normal, menu, hierarchical
	 * TIP: faster/shorter by using only the first character (e.g. "H" for "hierarchical")
	 *
	 * 2011-03-15 ms
	 */
	public function setControls($options = array()) {
		if (!empty($options['streetView'])) {
			$this->_currentOptions['map']['streetViewControl'] = $options['streetView'];
		}
		if (!empty($options['zoom'])) {
			$this->_currentOptions['map']['scaleControl'] = $options['zoom'];
		}
		if (isset($options['scrollwheel'])) {
			$this->_currentOptions['map']['scrollwheel'] = $options['scrollwheel'];
		}
		if (isset($options['keyboardShortcuts'])) {
			$this->_currentOptions['map']['keyboardShortcuts'] = $options['keyboardShortcuts'];
		}
		/*
		if (!empty($options['map'])) {
			if ($options['map'] == 'l' || $options['map'] == 'large') {
				$this->setMapControl('GLargeMapControl()');
			} else {
				$this->setMapControl('GSmallMapControl()');
			}
		}
		*/
		if (!empty($options['type'])) {
			/*
			if ($options['type'] == 'm' || $options['type'] == 'menu') {
				$this->setMapControl('GMenuMapTypeControl()');
			} elseif ($options['type'] == 'h' || $options['type'] == 'hierarchical') {
				$this->setMapControl('GHierarchicalMapTypeControl()');
			} else {
				$this->setMapControl('GMapTypeControl()');
			}
			*/
			$this->_currentOptions['map']['type'] = $options['type'];
		}
	}

	/**
	 * This the initialization point of the script
	 * Returns the div container you can echo on the website
	 *
	 * @param array $options associative array of settings are passed
	 * @return string $divContainer
	 * 2010-12-20 ms
	 */
	public function map($options = array()) {
		$this->reset();
		$this->_currentOptions = Set::merge($this->_currentOptions, $options);
		$this->_currentOptions['map'] = array_merge($this->_currentOptions['map'], array('zoom'=>$this->_currentOptions['zoom'], 'lat' => $this->_currentOptions['lat'], 'lng' => $this->_currentOptions['lng'], 'type' => $this->_currentOptions['type']), $options);
		if (!$this->_currentOptions['map']['lat'] || !$this->_currentOptions['map']['lng']) {
			$this->_currentOptions['map']['lat'] = $this->_currentOptions['map']['defaultLat'];
			$this->_currentOptions['map']['lng'] = $this->_currentOptions['map']['defaultLng'];
			$this->_currentOptions['map']['zoom'] = $this->_currentOptions['map']['defaultZoom'];
		} elseif (!$this->_currentOptions['map']['zoom']) {
			$this->_currentOptions['map']['zoom'] = $this->_currentOptions['map']['defaultZoom'];
		}

		# autoinclude js?
		if (!empty($options['autoScript']) && !$this->_apiIncluded) {
			$res = $this->Html->script($this->apiUrl(), array('inline'=>$options['inline']));
			if ($options['inline']) {
				echo $res;
			}
			# usually already included
			//http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js
		}
		# still not very common: http://code.google.com/intl/de-DE/apis/maps/documentation/javascript/basics.html
		if (!empty($options['autoScript']) && !$this->_gearsIncluded) {
			$res = $this->Html->script($this->gearsUrl(), array('inline'=>$options['inline']));
			if ($options['inline']) {
				echo $res;
			}
		}

		$map = "
			var initialLocation = ".$this->_initialLocation().";
			var browserSupportFlag =  new Boolean();
			var myOptions = ".$this->_mapOptions().";

			// deprecated
			gMarkers".self::$MAP_COUNT." = new Array();
			gInfoWindows".self::$MAP_COUNT." = new Array();
			gWindowContents".self::$MAP_COUNT." = new Array();
		";

		#rename "map_canvas" to "map_canvas1", ... if multiple maps on one page
		while (in_array($this->_currentOptions['div']['id'], $this->_mapIds)) {
			$this->_currentOptions['div']['id'] .= '-1'; //TODO: improve
		}
		$this->_mapIds[] = $this->_currentOptions['div']['id'];

		$map .= "
			var ".$this->name()." = new google.maps.Map(document.getElementById(\"".$this->_currentOptions['div']['id']."\"), myOptions);
			";
		$this->map = $map;

		$result = '';
		$this->_currentOptions['div']['style'] = '';
		if (is_numeric($this->_currentOptions['div']['width'])) {
			$this->_currentOptions['div']['width'] .= 'px';
		}
		if (is_numeric($this->_currentOptions['div']['height'])) {
			$this->_currentOptions['div']['height'] .= 'px';
		}

		$this->_currentOptions['div']['style'] .= 'width: '.$this->_currentOptions['div']['width'].';';
		$this->_currentOptions['div']['style'] .= 'height: '.$this->_currentOptions['div']['height'].';';
		unset($this->_currentOptions['div']['width']); unset($this->_currentOptions['div']['height']);

		$defaultText = isset($this->_currentOptions['content']) ? $this->_currentOptions['content'] : __('Map cannot be displayed!');
		$result = $this->Html->tag('div', $defaultText, $this->_currentOptions['div']);

		return $result;
	}


	public function _initialLocation() {
		if ($this->_currentOptions['map']['lat'] && $this->_currentOptions['map']['lng']) {
			return "new google.maps.LatLng(".$this->_currentOptions['map']['lat'].", ".$this->_currentOptions['map']['lng'].")";
		}
		$this->_currentOptions['autoCenter'] = true;
		return 'false';
	}

	/**
	 * @param array $options
	 * - lat, lng, title, content, icon, directions
	 * @return int $markerCount or false on failure
	 * 2010-12-18 ms
	 */
	public function addMarker($options) {
		if (empty($options)) {
			return false;
		}
		if (!isset($options['lat']) || !isset($options['lng'])) {
			return false;
		};
		if (!preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $options['lat']) || !preg_match("/[-+]?\b[0-9]*\.?[0-9]+\b/", $options['lng'])) {
			return false;
		}

		$defaults = $this->_currentOptions['marker'];
		if (isset($options['icon']) && is_array($options['icon'])) {
			$defaults = array_merge($defaults, $options['icon']);
			unset($options['icon']);
		}
		$options = array_merge($defaults, $options);


		$params = array();
		$params['map'] = $this->name();

		if (isset($options['title'])) {
			$params['title'] = json_encode($options['title']);
		}
		if (isset($options['icon'])) {
			$params['icon'] = $options['icon'];
			if (is_int($params['icon'])) {
				$params['icon'] = 'gIcons'.self::$MAP_COUNT.'['.$params['icon'].']';
			} else {
				$params['icon'] = json_encode($params['icon']);
			}
		}
		if (isset($options['shadow'])) {
			$params['shadow'] = $options['shadow'];
			if (is_int($params['shadow'])) {
				$params['shadow'] = 'gIcons'.self::$MAP_COUNT.'['.$params['shadow'].']';
			}
		}
		if (isset($options['shape'])) {
			$params['shape'] = $options['shape'];
		}
		if (isset($options['zIndex'])) {
			$params['zIndex'] = $options['zIndex'];
		}

		$marker = "
			var x".self::$MARKER_COUNT." = new google.maps.Marker({
				position: new google.maps.LatLng(".$options['lat'].",".$options['lng']."),
				".$this->_toObjectParams($params, false, false)."
			});
			gMarkers".self::$MAP_COUNT.".push(
				x".self::$MARKER_COUNT."
			);
		";
		$this->map.= $marker;

		if (!empty($options['directions'])) {
			$options['content'] .= $this->_directions($options['directions'], $options);
		}

		if (!empty($options['content']) && $this->_currentOptions['infoWindow']['useMultiple']) {
			$x = $this->addInfoWindow(array('content'=>$options['content']));
			$this->setContentInfoWindow($options['content'], $x);
			/*
			$marker .= "

			var window".self::$MARKER_COUNT." = new google.maps.InfoWindow({ content: '".$options['content']."',
		size: new google.maps.Size(50,50)
		});

			google.maps.event.addListener(x".self::$MARKER_COUNT.", 'click', function() {
			/ ".$this->name().".setZoom(7); /
			infowindow.setContent(gWindows[".self::$MARKER_COUNT."]);
				infowindow.setPosition(event.latLng);
				infowindow.open(map);
			});

			";
			*/
			$this->addEvent($x);

		} elseif (!empty($options['content'])) {
			if (!isset($this->_currentOptions['marker']['infoWindow'])) {
				$this->_currentOptions['marker']['infoWindow'] = $this->addInfoWindow();
			}

			$x = $this->addInfoContent($options['content']);

			$event = "
			gInfoWindows".self::$MAP_COUNT."[".$this->_currentOptions['marker']['infoWindow']."].setContent(gWindowContents".self::$MAP_COUNT."[".self::$MARKER_COUNT."]);
			gInfoWindows".self::$MAP_COUNT."[".$this->_currentOptions['marker']['infoWindow']."].open(".$this->name().", gMarkers".self::$MAP_COUNT."[".self::$MARKER_COUNT."]);
			";
			$this->addCustomEvent(self::$MARKER_COUNT, $event);
		}

		# custom matching event?

		if (isset($options['id'])) {
			$this->matching[$options['id']] = self::$MARKER_COUNT;
		}
		/*
		//$this->mapMarkers[$id] = ;
		//$function = 'function() { '.$id.'.'.$call.'("'.$content.'");}';
		$function = 'function() { mapMarkers[\''.$id.'\'].'.$call.'(mapWindows[\''.$id.'\']);}';

		$this->addListener($id, $function, isset($options['action'])?$options['action']:null);
		//"gInfoWindows".self::$MAP_COUNT.".setContent(gWindowContents1[1]);
		//"gInfoWindows".self::$MAP_COUNT.".open(map1, gMarkers1[1]);
		*/
		return self::$MARKER_COUNT++;
	}

	/**
	 * build directions form (type get) for directions inside infoWindows
	 * @param mixed $directions
	 * - bool TRUE for autoDirections (using lat/lng)
	 * @param array $options
	 * - options array of marker for autoDirections etc (optional)
	 * 2011-03-22 ms
	 */
	public function _directions($directions, $markerOptions = array()) {
		$options = array(
			'from' => null,
			'to' => null,
			'label' => __('Enter your address'),
			'submit' => __('Get directions'),
			'escape' => true,
			'zoom' => null, # auto
		);
		if ($directions === true) {
			$options['to'] = $markerOptions['lat'].','.$markerOptions['lng'];
		} elseif (is_array($directions)) {
			$options = array_merge($options, $directions);
		}
		if (empty($options['to']) && empty($options['from'])) {
			return '';
		}
		$form = '<form action="http://maps.google.com/maps" method="get" target="_blank">';
		$form .= $options['escape'] ? h($options['label']) : $options['label'];
		if (!empty($options['from'])) {
			$form .= '<input type="hidden" name="saddr" value="'.$options['from'].'" />';
		} else {
			$form .= '<input type="text" name="saddr" />';
		}
		if (!empty($options['to'])) {
			$form .= '<input type="hidden" name="daddr" value="'.$options['to'].'" />';
		} else {
			$form .= '<input type="text" name="daddr" />';
		}
		if (isset($options['zoom'])) {
			$form .= '<input type="hidden" name="z" value="'.$options['zoom'].'" />';
		}
		$form .= '<input type="submit" value="'.$options['submit'].'" />';
		$form .= '</form>';

		return '<div class="directions">'.$form.'</div>';
	}


	public function addInfoContent($con) {
		$this->infoContents[self::$MARKER_COUNT] = $this->escapeString($con);
		$event = "
			gWindowContents".self::$MAP_COUNT.".push(".$this->escapeString($con).");
			";
		$this->addCustom($event);

		//TODO: own count?
		return self::$MARKER_COUNT;
	}

	public $setIcons = array(
		'color' => 'http://www.google.com/mapfiles/marker%s.png',
		'alpha' => 'http://www.google.com/mapfiles/marker%s%s.png',
		'numeric' => 'http://google-maps-icons.googlecode.com/files/%s%s.png',
		'special' => 'http://google-maps-icons.googlecode.com/files/%s.png'
	);

	/**
	 * get a custom icon set
	 * @param color: green, red, purple, ... or some special ones like "home", ...
	 * @param char: A...Z or 0...20/100 (defaults to none)
	 * @param size: s, m, l (defaults to medium)
	 * NOTE: for special ones only first parameter counts!
	 * @return array: array(icon, shadow, shape, ...)
	 * 2011-03-14 ms
	 */
	public function iconSet($color, $char = null, $size = 'm') {
		$colors = array('red', 'green', 'yellow', 'blue', 'purple', 'white', 'black');
		if (!in_array($color, $colors)) {
			$color = 'red';
		}
		if (!empty($char)) {
			if ($color == 'red') {
				$color = '';
			} else {
				$color = '_'.$color;
			}
			$url = sprintf($this->setIcons['alpha'], $color, $char);
		} else {
			if ($color == 'red') {
				$color = '';
			} else {
				$color = '_'.$color;
			}
			$url = sprintf($this->setIcons['color'], $color);
		}

/*
var iconImage = new google.maps.MarkerImage('images/' + images[0] + '.png',
	new google.maps.Size(iconData[images[0]].width, iconData[images[0]].height),
	new google.maps.Point(0,0),
	new google.maps.Point(0, 32)
);

var iconShadow = new google.maps.MarkerImage('images/' + images[1] + '.png',
	new google.maps.Size(iconData[images[1]].width, iconData[images[1]].height),
	new google.maps.Point(0,0),
	new google.maps.Point(0, 32)
);

var iconShape = {
	coord: [1, 1, 1, 32, 32, 32, 32, 1],
	type: 'poly'
};
*/

		$shadow = 'http://www.google.com/mapfiles/shadow50.png';
		$res = array('url'=>$url, 'icon'=>$this->icon($url, array('size'=>array('width'=>20, 'height'=>34))), 'shadow'=>$this->icon($shadow, array('size'=>array('width'=>37, 'height'=>34), 'shadow'=>array('width'=>10, 'height'=>34))));
		//$this->icons[$ICON_COUNT] = $res;
		//$ICON_COUNT++;
		return $res;
	}

	/**
	 * @param string $imageUrl (http://...)
	 * @param string $shadowImageUrl (http://...)
	 * @param array $imageOptions
	 * @param array $shadowImageOptions
	 * custom icon: http://thydzik.com/thydzikGoogleMap/markerlink.php?text=?&color=FFFFFF
	 * custom icons: http://code.google.com/p/google-maps-icons/wiki/NumericIcons#Lettered_Balloons_from_A_to_Z,_in_10_Colors
	 * custom shadows: http://www.cycloloco.com/shadowmaker/shadowmaker.htm
	 * 2011-03-13 ms
	 */
	public function addIcon($image, $shadow = null, $imageOptions = array(), $shadowOptions = array()) {
		$res = array('url'=>$image);
		$res['icon'] = $this->icon($image, $imageOptions);
		if ($shadow) {
			$last = $this->_iconRemember[$res['icon']];
			if (!isset($shadowOptions['anchor'])) {
				$shadowOptions['anchor'] = array();
			}
			$shadowOptions['anchor'] = array_merge($shadowOptions['anchor'], $last['options']['anchor']);

			$res['shadow'] = $this->icon($shadow, $shadowOptions);
		}
		return $res;
	}

	protected $_iconRemember = array();

	/**
	 * generate icon object
	 * @param url (required)
	 * @param options (optional):
	 * - size: array(width=>x, height=>y)
	 * - origin: array(width=>x, height=>y)
	 * - anchor: array(width=>x, height=>y)
	 */
	public function icon($url, $options = array()) {
		// The shadow image is larger in the horizontal dimension
		// while the position and offset are the same as for the main image.
		if (empty($options['size'])) {
			if ($data = @getimagesize($url)) {
				$options['size']['width'] = $data[0];
				$options['size']['height'] = $data[1];
			} else {
				$options['size']['width'] = $options['size']['height'] = 0;
			}
		}
		if (empty($options['anchor'])) {
			$options['anchor']['width'] = intval($options['size']['width']/2);
			$options['anchor']['height'] = $options['size']['height'];
		}
		if (empty($options['origin'])) {
			$options['origin']['width'] = $options['origin']['height'] = 0;
		}
		if (isset($options['shadow'])) {
			$options['anchor'] = $options['shadow'];
		}
		//pr(returns($options));

		$icon = 'new google.maps.MarkerImage(\''.$url.'\',
	new google.maps.Size('.$options['size']['width'].', '.$options['size']['height'].'),
	new google.maps.Point('.$options['origin']['width'].', '.$options['origin']['height'].'),
	new google.maps.Point('.$options['anchor']['width'].', '.$options['anchor']['height'].')
)';
		$this->icons[self::$ICON_COUNT] = $icon;
		$this->_iconRemember[self::$ICON_COUNT] = array('url'=>$url, 'options'=>$options, 'id'=>self::$ICON_COUNT);
		//$this->map .= $code;
		return self::$ICON_COUNT++;
	}


	/**
	 * @param array $options
	 * - lat, lng, content, maxWidth, pixelOffset, zIndex
	 * @return int $windowCount
	 * 2010-12-18 ms
	 */
	public function addInfoWindow($options=array()) {
		$options = $this->_currentOptions['infoWindow'];
		$options = array_merge($options,$options);


		if (!empty($options['lat']) && !empty($options['lng'])) {
			$position = "new google.maps.LatLng(".$options['lat'].", ".$options['lng'].")";
		} else {
			$position = " ".$this->name().".getCenter()";
		}

			$windows = "
			gInfoWindows".self::$MAP_COUNT.".push( new google.maps.InfoWindow({
					position: {$position},
					content: ".$this->escapeString($options['content']).",
					maxWidth: {$options['maxWidth']},
					pixelOffset: {$options['pixelOffset']}
					/*zIndex: {$options['zIndex']},*/
			}));
			";
		$this->map .= $windows;
		return self::$INFO_WINDOW_COUNT++;
	}

	/**
	 * @param int $marker
	 * @param int $infoWindow
	 * @return void
	 * 2010-12-18 ms
	 */
	public function addEvent($marker, $infoWindow) {
		$this->map .= "
			google.maps.event.addListener(gMarkers[{$marker}], 'click', function() {
				gInfoWindows".self::$MAP_COUNT."[$infoWindow].open(".$this->name().", this);
			});
		";
	}

	/**
	 * @param int $marker
	 * @param string $event (js)
	 * @return void
	 * 2010-12-18 ms
	 */
	public function addCustomEvent($marker, $event) {
		$this->map .= "
			google.maps.event.addListener(gMarkers".self::$MAP_COUNT."[{$marker}], 'click', function() {
				$event
			});
		";
	}

	/**
	 * @param string $custom (js)
	 * @return void
	 * 2010-12-18 ms
	 */
	public function addCustom($js) {
		$this->map .= $js;
	}

	/**
	 * @param string $content (html/text)
	 * @param int $infoWindowCount
	 * @return void
	 * 2010-12-18 ms
	 */
	public function setContentInfoWindow($con, $index) {
		$this->map .= "
			gInfoWindows".self::$MAP_COUNT."[$index].setContent(".$this->escapeString($con).");";
	}


	public function escapeString($content) {
		return json_encode($content);
	}


	/**
	 * This method returns the javascript for the current map container
	 * Just echo it below the map container
	 * @return string
	 * 2010-12-18 ms
	*/
	public function script() {
		$script='<script type="text/javascript">
		'.$this->_arrayToObject('matching', $this->matching, false, true).'
		'.$this->_arrayToObject('gIcons'.self::$MAP_COUNT, $this->icons, false, false).'

	jQuery(document).ready(function() {
		';

		$script .= $this->map;
		if ($this->_currentOptions['geolocate']) {
			$script .= $this->_geolocate();
		}

		if ($this->_currentOptions['showMarker'] && !empty($this->markers) && is_array($this->markers)) {
			$script .= implode($this->markers, " ");
		}

		if ($this->_currentOptions['autoCenter']) {
			$script .= $this->_autoCenter();
		}
		$script .= '

	});
</script>';
		self::$MAP_COUNT++;
		return $script;
	}

	/**
	 * set a custom geolocate callback
	 * @param string $customJs
	 * false: no callback at all
	 * 2011-03-16 ms
	 */
	public function geolocateCallback($js) {
		if ($js === false) {
			$this->_currentOptions['callbacks']['geolocate'] = false;
			return;
		}
		$this->_currentOptions['callbacks']['geolocate'] = $js;
	}

	/**
	 * experimental - works in cutting edge browsers like chrome10
	 * 2011-03-16 ms
	 */
	public function _geolocate() {
		return '
	// Try W3C Geolocation (Preferred)
	if (navigator.geolocation) {
		browserSupportFlag = true;
		navigator.geolocation.getCurrentPosition(function(position) {
			geolocationCallback(position.coords.latitude, position.coords.longitude);
		}, function() {
			handleNoGeolocation(browserSupportFlag);
		});
		// Try Google Gears Geolocation
	} else if (google.gears) {
		browserSupportFlag = true;
		var geo = google.gears.factory.create(\'beta.geolocation\');
		geo.getCurrentPosition(function(position) {
			geolocationCallback(position.latitude, position.longitude);
		}, function() {
			handleNoGeoLocation(browserSupportFlag);
		});
		// Browser doesn\'t support Geolocation
	} else {
		browserSupportFlag = false;
		handleNoGeolocation(browserSupportFlag);
	}

	function geolocationCallback(lat, lng) {
		'.$this->_geolocationCallback().'
	}

	function handleNoGeolocation(errorFlag) {
	if (errorFlag == true) {
		//alert("Geolocation service failed.");
	} else {
		//alert("Your browser doesn\'t support geolocation. We\'ve placed you in Siberia.");
	}
	//'.$this->name().'.setCenter(initialLocation);
	}
	';
	}

	public function _geolocationCallback() {
		if (($js = $this->_currentOptions['callbacks']['geolocate']) === false) {
			return '';
		}
		if ($js === null) {
			$js = 'initialLocation = new google.maps.LatLng(lat, lng);
		'.$this->name().'.setCenter(initialLocation);
';
		//return $js;
		}
		return $js;
	}

	/**
	 * auto center map
	 * careful: with only one marker this can result in too high zoom values!
	 * @return string $autoCenterCommands
	 * 2010-12-17 ms
	 */
	protected function _autoCenter() {
		return '
		var bounds = new google.maps.LatLngBounds();
		$.each(gMarkers'.self::$MAP_COUNT.',function (index, marker) { bounds.extend(marker.position);});
		'.$this->name().'.fitBounds(bounds);
		';
	}

	/**
	 * @return json like js string
	 * 2010-12-17 ms
	 */
	protected function _mapOptions() {
		$options = array_merge($this->_currentOptions, $this->_currentOptions['map']);

		$mapOptions = array_intersect_key($options, array(
			'streetViewControl' => null,
			'navigationControl' => null,
			'mapTypeControl' => null,
			'scaleControl' => null,
			'scrollwheel' => null,
			'zoom' => null,
			'keyboardShortcuts' => null
		));
		$res = array();
		foreach ($mapOptions as $key => $mapOption) {
			$res[] = $key.': '.$this->Js->value($mapOption);
		}
		if (empty($options['autoCenter'])) {
			$res[] = 'center: initialLocation';
		}
		if (!empty($options['navOptions'])) {
			$res[] = 'navigationControlOptions: '.$this->_controlOptions('nav', $options['navOptions']);
		}
		if (!empty($options['typeOptions'])) {
			$res[] = 'mapTypeControlOptions: '.$this->_controlOptions('type', $options['typeOptions']);
		}
		if (!empty($options['scaleOptions'])) {
			$res[] = 'scaleControlOptions: '.$this->_controlOptions('scale', $options['scaleOptions']);
		}

		if (array_key_exists($options['type'], $this->types)) {
			$type = $this->types[$options['type']];
		} else {
			$type = $options['type'];
		}
		$res[] = 'mapTypeId: google.maps.MapTypeId.'.$type;

		return '{'.implode(', ', $res).'}';
	}

	protected function _controlOptions($type, $options) {
		$mapping = array(
			'nav' => 'NavigationControlStyle',
			'type' => 'MapTypeControlStyle',
			'scale' => ''
		);
		$res = array();
		if (!empty($options['style']) && ($m = $mapping[$type])) {
			$res[] = 'style: google.maps.'.$m.'.'.$options['style'];
		}
		if (!empty($options['pos'])) {
			$res[] = 'position: google.maps.ControlPosition.'.$options['pos'];
		}

		return '{'.implode(', ', $res).'}';
	}




/** Google Maps Link **/

	/**
	 * returns a maps.google link
	 * @param string $linkTitle
	 * @param array $mapOptions
	 * @param array $linkOptions
	 * 2011-03-12 ms
	 */
	public function link($title, $mapOptions = array(), $linkOptions = array()) {
		return $this->Html->link($title, $this->url($mapOptions), $linkOptions);
	}


	/**
	 * returns a maps.google url
	 * @param array options:
	 * - from: neccessary (address or lat,lng)
	 * - to: 1x neccessary (address or lat,lng - can be an array of multiple destinations: array('dest1', 'dest2'))
	 * - zoom: optional (defaults to none)
	 * @return string link: http://...
	 * 2010-12-18 ms
	 */
	public function url($options = array()) {
		$link = $this->_protocol() . 'maps.google.it/maps?';

		$linkArray = array();
		if (!empty($options['from'])) {
			$linkArray[] = 'saddr='.h($options['from']);
		}

		if (!empty($options['to']) && is_array($options['to'])) {
			$to = array_shift($options['to']);
			foreach ($options['to'] as $key => $value) {
				$to .= '+to:'.$value;
			}
			$linkArray[] = 'daddr='.h($to);
		} elseif (!empty($options['to'])) {
			$linkArray[] = 'daddr='.h($options['to']);
		}

		if (!empty($options['zoom'])) {
			$linkArray[] = 'z='.(int)$options['zoom'];
		}
		//$linkArray[] = 'f=d';
		//$linkArray[] = 'hl=de';
		//$linkArray[] = 'ie=UTF8';
		return $link.implode('&', $linkArray);
	}

/** STATIC MAP **/

/** http://maps.google.com/staticmap?center=40.714728,-73.998672&zoom=14&size=512x512&maptype=mobile&markers=40.702147,-74.015794,blues%7C40.711614,-74.012318,greeng%7C40.718217,-73.998284,redc&mobile=true&sensor=false **/


	/**
	 * Create a plain image map
	 * @link http://code.google.com/intl/de-DE/apis/maps/documentation/staticmaps
	 * @param options:
	 * - string $size [NECCESSARY: VALxVAL, e.g. 500x400 - max 640x640]
	 * - string $center: x,y or address [NECCESSARY, if no markers are given; else tries to take defaults if available] or TRUE/FALSE
	 * - int $zoom [optional; if no markers are given, default value is used; if set to "auto" and ]*
	 * - array $markers [optional, @see staticPaths() method]
	 * - string $type [optional: roadmap/hybrid, ...; default:roadmap]
	 * - string $mobile TRUE/FALSE
	 * - string $visible: $area (x|y|...)
	 * - array $paths [optional, @see staticPaths() method]
	 * - string $language [optional]
	 * @param array $attributes: html attributes for the image
	 * - title
	 * - alt (defaults to 'Map')
	 * - url (tip: you can pass $this->link(...) and it will create a link to maps.google.com)
	 * @return string $imageTag
	 * 2010-12-18 ms
	 */
	public function staticMap($options = array(), $attributes = array()) {
		$defaultAttributes = array('alt' => __('Map'));

		return $this->Html->image($this->staticMapUrl($options), array_merge($defaultAttributes, $attributes));
	}

	/**
	 * Create a link to a plain image map
	 * @param string $linkTitle
	 * @param array $mapOptions
	 * @param array $linkOptions
	 * 2011-03-12 ms
	 */
	public function staticMapLink($title, $mapOptions = array(), $linkOptions = array()) {
		return $this->Html->link($title, $this->staticMapUrl($mapOptions), $linkOptions);
	}


	/**
	 * Create an url to a plain image map
	 * @param options
	 * - see staticMap() for details
	 * @return string $urlOfImage: http://...
	 * 2010-12-18 ms
	 */
	public function staticMapUrl($options = array()) {
		$map = $this->_protocol() . self::STATIC_API;
		/*
		$params = array(
			'sensor' => 'false',
			'mobile' => 'false',
			'format' => 'png',
			//'center' => false
		);

		if (!empty($options['sensor'])) {
			$params['sensor'] = 'true';
		}
		if (!empty($options['mobile'])) {
			$params['mobile'] = 'true';
		}
		*/

		$defaults = array_merge($this->_defaultOptions, $this->_defaultOptions['staticMap']);
		$mapOptions = array_merge($defaults, $options);

		$params = array_intersect_key($mapOptions, array(
			'sensor' => null,
			'mobile' => null,
			'format' => null,
			'size' => null,
			//'zoom' => null,
			//'lat' => null,
			//'lng' => null,
			//'visible' => null,
			//'type' => null,
		));
		# do we want zoom to auto-correct itself?
		if (!isset($options['zoom']) && !empty($mapOptions['markers'])|| !empty($mapOptions['paths']) || !empty($mapOptions['visible'])) {
			$options['zoom'] = 'auto';
		}

		# a position on the map that is supposed to stay visible at all cost
		if (!empty($mapOptions['visible'])) {
			$params['visible'] = urlencode($mapOptions['visible']);
		}

		# center and zoom are not necccessary if path, visible or markers are given
		if (!isset($options['center']) || $options['center'] === false) {
			# dont use it
		} elseif ($options['center'] === true && $mapOptions['lat'] !== null && $mapOptions['lng'] !== null) {
			$params['center'] = (string)$mapOptions['lat'].','.(string)$mapOptions['lng'];
		} elseif (!empty($options['center'])) {
			$params['center'] = urlencode($options['center']);
		} /*else {
			# try to read from markers array???
			if (isset($options['markers']) && count($options['markers']) == 1) {
				//pr ($options['markers']);
			}
		}*/

		if (!isset($options['zoom']) || $options['zoom'] === false) {
			# dont use it
		} else {
			if ($options['zoom'] == 'auto') {
				if (!empty($options['markers']) && strpos($options['zoom'],'|') !== false) {
					# let google find the best zoom value itself
				} else {
					# do something here?
				}
			} else {
				$params['zoom'] = $options['zoom'];
			}
		}

		if (array_key_exists($mapOptions['type'], $this->types)) {
			$params['maptype'] = $this->types[$mapOptions['type']];
		} else {
			$params['maptype'] = $mapOptions['type'];
		}
		//unset($options['type']);
		$params['maptype'] = strtolower($params['maptype']);


		# old: {latitude},{longitude},{color}{alpha-character}
		# new: @see staticMarkers()
		if (!empty($options['markers'])) {
			$params['markers'] = $options['markers'];
		}

		if (!empty($options['paths'])) {
			$params['path'] = $options['paths'];
		}

		# valXval
		if (!empty($options['size'])) {
			$params['size'] = $options['size'];
		}

		$pieces = array();
		foreach ($params as $key => $value) {
			if (is_array($value)) {
				$value = implode('&'.$key.'=', $value);
			} elseif ($value === true) {
				$value = 'true';
			} elseif ($value === false) {
				$value = 'false';
			} elseif ($value === null) {
				continue;
			}
			$pieces[] = $key.'='.$value;
			//$map .= $key.'='.$value.'&';
		}
		return $map . implode('&', $pieces);
	}

	/**
	 * prepare paths for staticMap
	 * @param array $pathElementArrays
	 * - elements: [required] (multiple array(lat=>x, lng=>y) or just a address strings)
	 * - color: red/blue/green (optional, default blue)
	 * - weight: numeric (optional, default: 5)
	 * @return string $paths: e.g: color:0x0000FF80|weight:5|37.40303,-122.08334|37.39471,-122.07201|37.40589,-122.06171{|...}
	 * 2010-12-18 ms
	 */
	public function staticPaths($pos = array()) {
		$defaults = array(
			'color' => 'blue',
			'weight' => 5 # pixel
		);


		# not a 2-level array? make it one
		if (!isset($pos[0])) {
			$pos = array($pos);
		}

		$res = array();
		foreach ($pos as $p) {
			$options = array_merge($defaults, $p);

			$markers = $options['path'];
			unset($options['path']);

			# prepare color
			if (!empty($options['color'])) {
				$options['color'] = $this->_prepColor($options['color']);
			}

			$path = array();
			foreach ($options as $key => $value) {
				$path[] = $key.':'.urlencode($value);
			}
			foreach ($markers as $key => $pos) {
				if (is_array($pos)) {
					# lat/lng?
					$pos = $pos['lat'].','.$pos['lng'];
				}
				$path[] = $pos;
			}
			$res[] = implode('|', $path);
		}
		return $res;
	}

	/**
	 * prepare markers for staticMap
	 * @param array $markerArrays
	 * - lat: xx.xxxxxx (NECCESSARY)
	 * - lng: xx.xxxxxx (NECCESSARY)
	 * - address: (instead of lat/lng)
	 * - color: red/blue/green (optional, default blue)
	 * - label: a-z or numbers (optional, default: s)
	 * - icon: custom icon (png, gif, jpg - max 64x64 - max 5 different icons per image)
	 * - shadow: TRUE/FALSE
	 * @param style (global) (overridden by custom marker styles)
	 * - color
	 * - label
	 * - icon
	 * - shadow
	 * @return array $markers: color:green|label:Z|48,11|Berlin
	 *
	 * NEW: size:mid|color:red|label:E|37.400465,-122.073003|37.437328,-122.159928&markers=size:small|color:blue|37.369110,-122.096034
	 * OLD: 40.702147,-74.015794,blueS|40.711614,-74.012318,greenG{|...}
	 * 2010-12-18 ms
	 */
	public function staticMarkers($pos = array(), $style = array()) {
		$markers = array();
		$verbose = false;

		$defaults = array(
			'shadow' => 'true',
			'color' => 'blue',
			'label' => '',
			'address' => '',
			'size' => ''
		);

		# not a 2-level array? make it one
		if (!isset($pos[0])) {
			$pos = array($pos);
		}

		# new in statitV2: separate styles! right now just merged

		foreach ($pos as $p) {
			$p = array_merge($defaults, $style, $p);

			# adress or lat/lng?
			if (!empty($p['lat']) && !empty($p['lng'])) {
				$p['address'] = $p['lat'].','.$p['lng'];
			} else {
				$p['address'] = $p['address'];
			}
			$p['address'] = urlencode($p['address']);


			$values = array();

			# prepare color
			if (!empty($p['color'])) {
				$p['color'] = $this->_prepColor($p['color']);
				$values[] = 'color:'.$p['color'];
			}
			# label? A-Z0-9
			if (!empty($p['label'])) {
				$values[] = 'label:'.strtoupper($p['label']);
			}
			if (!empty($p['size'])) {
				$values[] = 'size:'.$p['size'];
			}
			if (!empty($p['shadow'])) {
				$values[] = 'shadow:'.$p['shadow'];
			}
			if (!empty($p['icon'])) {
				$values[] = 'icon:'.urlencode($p['icon']);
			}
			$values[] = $p['address'];

			//TODO: icons
			$markers[] = implode('|', $values);
		}

		//TODO: shortcut? only possible if no custom params!
		if ($verbose) {

		}
		// long: markers=styles1|address1&markers=styles2|address2&...
		// short: markers=styles,address1|address2|address3|...

		return $markers;
	}
	
	protected function _protocol() {
		if (($https = $this->_currentOptions['https']) === null) {
			$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
		}
		return ($https ? 'https' : 'http') . '://';
	}

	/**
	 * # to 0x
	 * or # added
	 * @param string $color: FFFFFF, #FFFFFF, 0xFFFFFF or blue
	 * @return string $color
	 * 2010-12-20 ms
	 */
	protected function _prepColor($color) {
		if (strpos($color, '#') !== false) {
			return str_replace('#', '0x', $color);
		} elseif (is_numeric($color)) {
			return '0x'.$color;
		}
		return $color;
	}


/** TODOS/EXP **/

/*
TODOS:

- animations
marker.setAnimation(google.maps.Animation.BOUNCE);

- geocoding (+ reverse)

- directions

- overlays

- fluster (for clustering?)
or
- markerManager (many markers)

- infoBox
http://google-maps-utility-library-v3.googlecode.com/svn/tags/infobox/

- ...

*/


	public function geocoder() {
		$js = 'var geocoder = new google.maps.Geocoder();';
		//TODO

	}

	/**
	 * managing lots of markers!
	 * @link http://google-maps-utility-library-v3.googlecode.com/svn/tags/markermanager/1.0/docs/examples.html
	 * @param options
	 * -
	 * @return void
	 * 2010-12-18 ms
	 */
	public function setManager() {
		$js .= '
		var mgr'.self::$MAP_COUNT.' = new MarkerManager('.$this->name().');
		';
	}

	public function addManagerMarker($marker, $options) {
		$js = 'mgr'.self::$MAP_COUNT.'.addMarker('.$marker.');';
	}


	/**
	 * clustering for lots of markers!
	 * @link ?
	 * @param options
	 * -
	 * based on Fluster2 0.1.1
	 * @return void
	 */
	public function setCluster($options) {
		$js = self::$flusterScript;
		$js .= '
		var fluster'.self::$MAP_COUNT.' = new Fluster2('.$this->name().');
		';

		# styles
		'fluster'.self::$MAP_COUNT.'.styles = {}';

		$this->map .= $js;
	}

	public function addClusterMarker($marker, $options) {
		$js = 'fluster'.self::$MAP_COUNT.'.addMarker('.$marker.');';
	}

	public function initCluster() {
		$this->map .= 'fluster'.self::$MAP_COUNT.'.initialize();';
	}


	public static $flusterScript = '
function Fluster2(_map,_debug) {var map=_map;var projection=new Fluster2ProjectionOverlay(map);var me=this;var clusters=new Object();var markersLeft=new Object();this.debugEnabled=_debug;this.gridSize=60;this.markers=new Array();this.currentZoomLevel=-1;this.styles={0:{image:\'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m1.png\',textColor:\'#FFFFFF\',width:53,height:52},10:{image:\'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m2.png\',textColor:\'#FFFFFF\',width:56,height:55},20:{image:\'http://gmaps-utility-library.googlecode.com/svn/trunk/markerclusterer/1.0/images/m3.png\',textColor:\'#FFFFFF\',width:66,height:65}};var zoomChangedTimeout=null;function createClusters() {var zoom=map.getZoom();if (clusters[zoom]) {me.debug(\'Clusters for zoom level \'+zoom+\' already initialized.\')}else{var clustersThisZoomLevel=new Array();var clusterCount=0;var markerCount=me.markers.length;for (var i=0;i<markerCount;i++) {var marker=me.markers[i];var markerPosition=marker.getPosition();var done=false;for (var j=clusterCount-1;j>=0;j--) {var cluster=clustersThisZoomLevel[j];if (cluster.contains(markerPosition)) {cluster.addMarker(marker);done=true;break}}if (!done) {var cluster=new Fluster2Cluster(me,marker);clustersThisZoomLevel.push(cluster);clusterCount++}}clusters[zoom]=clustersThisZoomLevel;me.debug(\'Initialized \'+clusters[zoom].length+\' clusters for zoom level \'+zoom+\'.\')}if (clusters[me.currentZoomLevel]) {for (var i=0;i<clusters[me.currentZoomLevel].length;i++) {clusters[me.currentZoomLevel][i].hide()}}me.currentZoomLevel=zoom;showClustersInBounds()}function showClustersInBounds() {var mapBounds=map.getBounds();for (var i=0;i<clusters[me.currentZoomLevel].length;i++) {var cluster=clusters[me.currentZoomLevel][i];if (mapBounds.contains(cluster.getPosition())) {cluster.show()}}}this.zoomChanged=function() {window.clearInterval(zoomChangedTimeout);zoomChangedTimeout=window.setTimeout(createClusters,500)};this.getMap=function() {return map};this.getProjection=function() {return projection.getP()};this.debug=function(message) {if (me.debugEnabled) {console.log(\'Fluster2: \'+message)}};this.addMarker=function(_marker) {me.markers.push(_marker)};this.getStyles=function() {return me.styles};this.initialize=function() {google.maps.event.addListener(map,\'zoom_changed\',this.zoomChanged);google.maps.event.addListener(map,\'dragend\',showClustersInBounds);window.setTimeout(createClusters,1000)}}
function Fluster2Cluster(_fluster,_marker) {var markerPosition=_marker.getPosition();this.fluster=_fluster;this.markers=[];this.bounds=null;this.marker=null;this.lngSum=0;this.latSum=0;this.center=markerPosition;this.map=this.fluster.getMap();var me=this;var projection=_fluster.getProjection();var gridSize=_fluster.gridSize;var position=projection.fromLatLngToDivPixel(markerPosition);var positionSW=new google.maps.Point(position.x-gridSize,position.y+gridSize);var positionNE=new google.maps.Point(position.x+gridSize,position.y-gridSize);this.bounds=new google.maps.LatLngBounds(projection.fromDivPixelToLatLng(positionSW),projection.fromDivPixelToLatLng(positionNE));this.addMarker=function(_marker) {this.markers.push(_marker)};this.show=function() {if (this.markers.length==1) {this.markers[0].setMap(me.map)}else if (this.markers.length>1) {for (var i=0;i<this.markers.length;i++) {this.markers[i].setMap(null)}if (this.marker==null) {this.marker=new Fluster2ClusterMarker(this.fluster,this);if (this.fluster.debugEnabled) {google.maps.event.addListener(this.marker,\'mouseover\',me.debugShowMarkers);google.maps.event.addListener(this.marker,\'mouseout\',me.debugHideMarkers)}}this.marker.show()}};this.hide=function() {if (this.marker!=null) {this.marker.hide()}};this.debugShowMarkers=function() {for (var i=0;i<me.markers.length;i++) {me.markers[i].setVisible(true)}};this.debugHideMarkers=function() {for (var i=0;i<me.markers.length;i++) {me.markers[i].setVisible(false)}};this.getMarkerCount=function() {return this.markers.length};this.contains=function(_position) {return me.bounds.contains(_position)};this.getPosition=function() {return this.center};this.getBounds=function() {return this.bounds};this.getMarkerBounds=function() {var bounds=new google.maps.LatLngBounds(me.markers[0].getPosition(),me.markers[0].getPosition());for (var i=1;i<me.markers.length;i++) {bounds.extend(me.markers[i].getPosition())}return bounds};this.addMarker(_marker)}
function Fluster2ClusterMarker(_fluster,_cluster) {this.fluster=_fluster;this.cluster=_cluster;this.position=this.cluster.getPosition();this.markerCount=this.cluster.getMarkerCount();this.map=this.fluster.getMap();this.style=null;this.div=null;var styles=this.fluster.getStyles();for (var i in styles) {if (this.markerCount>i) {this.style=styles[i]}else{break}}google.maps.OverlayView.call(this);this.setMap(this.map);this.draw()};Fluster2ClusterMarker.prototype=new google.maps.OverlayView();Fluster2ClusterMarker.prototype.draw=function() {if (this.div==null) {var me=this;this.div=document.createElement(\'div\');this.div.style.position=\'absolute\';this.div.style.width=this.style.width+\'px\';this.div.style.height=this.style.height+\'px\';this.div.style.lineHeight=this.style.height+\'px\';this.div.style.background=\'transparent url("\'+this.style.image+\'") 50% 50% no-repeat\';this.div.style.color=this.style.textColor;this.div.style.textAlign=\'center\';this.div.style.fontFamily=\'Arial, Helvetica\';this.div.style.fontSize=\'11px\';this.div.style.fontWeight=\'bold\';this.div.innerHTML=this.markerCount;this.div.style.cursor=\'pointer\';google.maps.event.addDomListener(this.div,\'click\',function() {me.map.fitBounds(me.cluster.getMarkerBounds())});this.getPanes().overlayLayer.appendChild(this.div)}var position=this.getProjection().fromLatLngToDivPixel(this.position);this.div.style.left=(position.x-parseInt(this.style.width/2))+\'px\';this.div.style.top=(position.y-parseInt(this.style.height/2))+\'px\'};Fluster2ClusterMarker.prototype.hide=function() {this.div.style.display=\'none\'};Fluster2ClusterMarker.prototype.show=function() {this.div.style.display=\'block\'};
function Fluster2ProjectionOverlay(map) {google.maps.OverlayView.call(this);this.setMap(map);this.getP=function() {return this.getProjection()}}Fluster2ProjectionOverlay.prototype=new google.maps.OverlayView();Fluster2ProjectionOverlay.prototype.draw=function() {};
\'';




/** CALCULATING STUFF **/


	/**
	 * Calculates Distance between two points array('lat'=>x,'lng'=>y)
	 * DB:
		'6371.04 * ACOS( COS( PI()/2 - RADIANS(90 - Retailer.lat)) * ' .
						'COS( PI()/2 - RADIANS(90 - '. $data['Location']['lat'] .')) * ' .
						'COS( RADIANS(Retailer.lng) - RADIANS('. $data['Location']['lng'] .')) + ' .
						'SIN( PI()/2 - RADIANS(90 - Retailer.lat)) * ' .
						'SIN( PI()/2 - RADIANS(90 - '. $data['Location']['lat'] . '))) ' .
		'AS distance'
	 *  @param array pointX
	 *  @param array pointY
	 *	@return int distance: in km
	 * DEPRECATED - use GeocodeLib::distance() instead!
	 * 2009-03-06 ms
	 */
	public function distance($pointX, $pointY) {
		/*
		$res = 	6371.04 * ACOS( COS( PI()/2 - rad2deg(90 - $pointX['lat'])) *
				COS( PI()/2 - rad2deg(90 - $pointY['lat'])) *
				COS( rad2deg($pointX['lng']) - rad2deg($pointY['lng'])) +
				SIN( PI()/2 - rad2deg(90 - $pointX['lat'])) *
				SIN( PI()/2 - rad2deg(90 - $pointY['lat'])));

		$res = 6371.04 * acos(sin($pointY['lat'])*sin($pointX['lat'])+cos($pointY['lat'])*cos($pointX['lat'])*cos($pointY['lng'] - $pointX['lng']));
		*/

		# seems to be the only working one (although slightly incorrect...)
		$res =  69.09 * rad2deg(acos(sin(deg2rad($pointX['lat'])) * sin(deg2rad($pointY['lat'])) +  cos(deg2rad($pointX['lat'])) * cos(deg2rad($pointY['lat'])) * cos(deg2rad($pointX['lng'] - $pointY['lng']))));

		# Miles to KM
		$res *= 1.609344;

		return ceil($res);
	}



	protected function _arrayToObject($name, $array, $asString = true, $keyAsString = false) {
		$res = 'var '.$name.' = {'.PHP_EOL;
		$res .= $this->_toObjectParams($array, $asString, $keyAsString);
		$res .= '};';
		return $res;
	}

	protected function _toObjectParams($array, $asString = true, $keyAsString = false) {
		$pieces = array();
		foreach ($array as $key => $value) {
			$e = ($asString && strpos($value, 'new ') !== 0 ? '\'' : '');
			$ke = ($keyAsString ? '\'' : '');
			$pieces[] = $ke.$key.$ke.': '.$e.$value.$e;
		}
		return implode(','.PHP_EOL, $pieces);
	}

}