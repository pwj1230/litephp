<?php
namespace Lite\Cache;

/**
 * Class CacheFile
 * @package Lite\Cache
 */
class CacheFile extends CacheAdapter{
	protected function __construct(array $config = []){
		if(!$config['dir']){
			$dir = sys_get_temp_dir();
			$config['dir'] = $dir.'/litephp_cache/';
		}
		if(!is_dir($config['dir'])){
			mkdir($config['dir'], 0777, true);;
		}
		parent::__construct($config);
	}

	public function set($cache_key, $data, $expired=60){
		$file = $this->getFileName($cache_key);
		$string = serialize(array(
			'data' => $data,
			'expired' => time()+$expired
		));
		if($handle = fopen($file, 'w')){
			$result = fwrite($handle, $string);
			fclose($handle);
			return $result;
		}
		return false;
	}

	public function getFileName($cache_key){
		return $this->getConfig('dir') . md5($cache_key);
	}

	public function get($cache_key){
		$file = $this->getFileName($cache_key);
		if(file_exists($file)){
			$string = file_get_contents($file);
			if($string){
				$data = unserialize($string);
				if($data && $data['expired']>time()){
					return $data['data'];
				}
			}
			//清空cache，防止cache膨胀
			$this->delete($cache_key);
		}
		return null;
	}

	public function delete($cache_key){
		$file = $this->getFileName($cache_key);
		if(file_exists($file)){
			return unlink($file);
		}
		return false;
	}

	/**
	 * flush cache dir
	 */
	public function flush(){
		$dir = $this->getConfig('dir');
		if(is_dir($dir)){
			array_map('unlink', glob($dir . '/*'));
		}
	}

	public function getAll(){
		$dir = $this->getConfig('dir');
		return $dir;
	}
}