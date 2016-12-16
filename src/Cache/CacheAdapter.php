<?php
namespace Lite\Cache;
use Lite\Exception\Exception;
use function Lite\func\dump;

abstract class CacheAdapter implements CacheInterface{
	private static $instances;
	private $config;

	protected function __construct($config){
		$this->setConfig($config);
	}

	/**
	 * quick cache
	 * 快速调用方法，不提供配置参数传入
	 * @param $key
	 * @param callable $fetcher
	 * @param int $expired_seconds
	 * @return mixed
	 * @throws \Lite\Exception\Exception
	 */
	public static function cache($key, callable $fetcher, $expired_seconds = 60){
		$cache_class = get_called_class();
		if($cache_class == self::class){
			throw new Exception('cache method not calledable in '.self::class);
		}

		/** @var self $instance */
		$instance = new $cache_class();
		$expired_seconds = $expired_seconds ?: 60;
		$data = $instance->get($key);
		if(!isset($data)){
			$data = call_user_func($fetcher);
			$instance->set($key, $data, $expired_seconds);
		}
		return $data;
	}

	public function getConfig($key = ''){
		if($key){
			return $this->config[$key];
		}
		return $this->config;
	}

	public function setConfig($config){
		$this->config = $config;
	}

	/**
	 * singleton instance
	 * @param array $config
	 * @return CacheAdapter
	 */
	public static function instance(array $config = array()){
		$class = get_called_class();
		if(!self::$instances[$class]){
			self::$instances[$class] = new $class($config);
		}
		return self::$instances[$class];
	}
}