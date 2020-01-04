<?php
namespace Lite\Component\Net;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Copyright 2010-2013 Craig Campbell
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Server Side Chrome PHP debugger class
 * @package ChromePhp
 * @author Craig Campbell <iamcraigcampbell@gmail.com>
 */
class ChromeLogger {
	/**
	 * @var string
	 */
	const VERSION = '4.1.0';

	/**
	 * @var string
	 */
	const HEADER_NAME = 'X-ChromeLogger-Data';

	/**
	 * @var string
	 */
	const BACKTRACE_LEVEL = 'backtrace_level';

	/**
	 * @var string
	 */
	const LOG = 'log';

	/**
	 * @var string
	 */
	const WARN = 'warn';

	/**
	 * @var string
	 */
	const ERROR = 'error';

	/**
	 * @var string
	 */
	const GROUP = 'group';

	/**
	 * @var string
	 */
	const INFO = 'info';

	/**
	 * @var string
	 */
	const GROUP_END = 'groupEnd';

	/**
	 * @var string
	 */
	const GROUP_COLLAPSED = 'groupCollapsed';

	/**
	 * @var string
	 */
	const TABLE = 'table';

	/**
	 * @var string
	 */
	protected $_php_version;

	/**
	 * @var int
	 */
	protected $_timestamp;

	/**
	 * @var array
	 */
	protected $_json = [
		'version' => self::VERSION,
		'columns' => ['log', 'backtrace', 'type'],
		'rows'    => [],
	];

	/**
	 * @var array
	 */
	protected $_backtraces = [];

	/**
	 * @var bool
	 */
	protected $_error_triggered = false;

	/**
	 * @var array
	 */
	protected $_settings = array(
		self::BACKTRACE_LEVEL => 1,
	);

	/**
	 * @var static
	 */
	protected static $_instance;

	/**
	 * Prevent recursion when working with objects referring to each other
	 * @var array
	 */
	protected $_processed = [];

	/**
	 * constructor
	 */
	private function __construct(){
		$this->_php_version = phpversion();
		$this->_timestamp = $this->_php_version >= 5.1 ? $_SERVER['REQUEST_TIME'] : time();
		$this->_json['request_uri'] = $_SERVER['REQUEST_URI'];
	}

	/**
	 * gets instance of this class
	 * @return static
	 */
	public static function getInstance(){
		if(self::$_instance === null){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * logs a variable to the console
	 * @param mixed $args $data,... unlimited OPTIONAL number of additional logs [...]
	 * @return void
	 */
	public static function log(...$args){
		self::_log('', $args);
	}

	/**
	 * logs a warning to the console
	 * @param mixed $args $data,... unlimited OPTIONAL number of additional logs [...]
	 * @return void
	 */
	public static function warn(...$args){
		self::_log(self::WARN, $args);
	}

	/**
	 * logs an error to the console
	 * @param mixed $args $data,... unlimited OPTIONAL number of additional logs [...]
	 * @return void
	 */
	public static function error(...$args){
		self::_log(self::ERROR, $args);
	}

	/**
	 * sends a group log
	 * @param array $args
	 */
	public static function group(...$args){
		self::_log(self::GROUP, $args);
	}

	/**
	 * sends an info log
	 * @param mixed $args $data,... unlimited OPTIONAL number of additional logs [...]
	 * @return void
	 */
	public static function info(...$args){
		self::_log(self::INFO, $args);
	}

	/**
	 * sends a collapsed group log
	 * @param array $args
	 */
	public static function groupCollapsed(...$args){
		self::_log(self::GROUP_COLLAPSED, $args);
	}

	/**
	 * ends a group log
	 * @param array $args
	 */
	public static function groupEnd(...$args){
		self::_log(self::GROUP_END, $args);
	}

	/**
	 * sends a table log
	 * @param string value
	 */
	public static function table(...$args){
		self::_log(self::TABLE, $args);
	}

	/**
	 * internal logging call
	 * @param string $type
	 * @param array $args
	 * @return void
	 */
	protected static function _log($type, array $args){
		// nothing passed in, don't do anything
		if(count($args) == 0 && $type != self::GROUP_END){
			return;
		}

		$logger = self::getInstance();

		$logger->_processed = [];

		$logs = [];
		foreach($args as $arg){
			$logs[] = $logger->_convert($arg);
		}

		$backtrace = debug_backtrace(false);
		$level = $logger->getSetting(self::BACKTRACE_LEVEL);

		$backtrace_message = 'unknown';
		if(isset($backtrace[$level]['file']) && isset($backtrace[$level]['line'])){
			$backtrace_message = $backtrace[$level]['file'].' : '.$backtrace[$level]['line'];
		}
		$logger->_addRow($logs, $backtrace_message, $type);
	}

	/**
	 * converts an object to a better format for logging
	 * @param Object
	 * @return array
	 */
	protected function _convert($object){
		// if this isn't an object then just return it
		if(!is_object($object)){
			return $object;
		}

		//Mark this object as processed so we don't convert it twice and it
		//Also avoid recursion when objects refer to each other
		$this->_processed[] = $object;

		$object_as_array = [];

		// first add the class name
		$object_as_array['___class_name'] = get_class($object);

		// loop through object vars
		$object_vars = get_object_vars($object);
		foreach($object_vars as $key => $value){

			// same instance as parent object
			if($value === $object || in_array($value, $this->_processed, true)){
				$value = 'recursion - parent object ['.get_class($value).']';
			}
			$object_as_array[$key] = $this->_convert($value);
		}

		$reflection = new ReflectionClass($object);

		// loop through the properties and add those
		foreach($reflection->getProperties() as $property){

			// if one of these properties was already added above then ignore it
			if(array_key_exists($property->getName(), $object_vars)){
				continue;
			}
			$type = $this->_getPropertyKey($property);

			if($this->_php_version >= 5.3){
				$property->setAccessible(true);
			}

			try{
				$value = $property->getValue($object);
			}catch(ReflectionException $e){
				$value = 'only PHP 5.3 can access private/protected properties';
			}

			// same instance as parent object
			if($value === $object || in_array($value, $this->_processed, true)){
				$value = 'recursion - parent object ['.get_class($value).']';
			}

			$object_as_array[$type] = $this->_convert($value);
		}
		return $object_as_array;
	}

	/**
	 * takes a reflection property and returns a nicely formatted key of the property name
	 * @param \ReflectionProperty
	 * @return string
	 */
	protected function _getPropertyKey(ReflectionProperty $property){
		$static = $property->isStatic() ? ' static' : '';
		if($property->isPublic()){
			return 'public'.$static.' '.$property->getName();
		}

		if($property->isProtected()){
			return 'protected'.$static.' '.$property->getName();
		}

		if($property->isPrivate()){
			return 'private'.$static.' '.$property->getName();
		}
		return null;
	}

	/**
	 * adds a value to the data array
	 * @param array $logs
	 * @param $backtrace
	 * @param $type
	 * @return void
	 */
	protected function _addRow(array $logs, $backtrace, $type){
		// if this is logged on the same line for example in a loop, set it to null to save space
		if(in_array($backtrace, $this->_backtraces)){
			$backtrace = null;
		}

		// for group, groupEnd, and groupCollapsed
		// take out the backtrace since it is not useful
		if($type == self::GROUP || $type == self::GROUP_END || $type == self::GROUP_COLLAPSED){
			$backtrace = null;
		}

		if($backtrace !== null){
			$this->_backtraces[] = $backtrace;
		}

		$row = array($logs, $backtrace, $type);

		$this->_json['rows'][] = $row;
		$this->_writeHeader($this->_json);
	}

	protected function _writeHeader($data){
		header(self::HEADER_NAME.': '.$this->_encode($data));
	}

	/**
	 * encodes the data to be sent along with the request
	 * @param array $data
	 * @return string
	 */
	protected function _encode($data){
		return base64_encode(utf8_encode(json_encode($data)));
	}

	/**
	 * adds a setting
	 * @param string key
	 * @param mixed value
	 * @return void
	 */
	public function addSetting($key, $value){
		$this->_settings[$key] = $value;
	}

	/**
	 * add ability to set multiple settings in one call
	 * @param array $settings
	 * @return void
	 */
	public function addSettings(array $settings){
		foreach($settings as $key => $value){
			$this->addSetting($key, $value);
		}
	}

	/**
	 * gets a setting
	 * @param string key
	 * @return mixed
	 */
	public function getSetting($key){
		if(!isset($this->_settings[$key])){
			return null;
		}
		return $this->_settings[$key];
	}
}
