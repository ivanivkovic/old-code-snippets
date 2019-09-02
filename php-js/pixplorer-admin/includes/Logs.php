<?php

class Logs{
	
	public static $dir = 'content/logs/';
	
	public static function setLog($log, $category = '', $specs = ''){
		
		if($category !== '' && $category !== 'all'){
			self::logToFile($log, $category, $specs);
		}
		
		self::logToFile($log, 'all', $specs);
		
	}
	
	# Creates log file if doesn't exist.
	private function logToFile($log, $category, $specs = ''){
		
		$file = self::$dir . $category . '/' . CUR_DATE . '.txt';
		
		$old_content = self::getLog($category, CUR_DATE);
		
		$f = fopen($file, 'w+');
		
		$log = CUR_TIME . ' - ' . $log . "\n";
		
		
		if($specs !== ''){
			
			foreach($specs as $spec => $value){
			
				$log .= "\t" . $spec . ' : ' . $value. "\n";
			
			}
		}
		
		fwrite($f, $log);
		
		fwrite($f, $old_content); 
		
		fclose($f);
		
	}
	
	public static function getLog($category, $date){
		
		$file = self::$dir . $category . '/' . $date . '.txt';
		
		$f = fopen($file, 'a+');
		$fsize = filesize($file);
		
		$content = $fsize > 0 ? fread($f, $fsize) : false;
		
		return $content;
		
	}
	
	public static function convertToHTML($content){
		$content = str_replace("\t", '&nbsp; &nbsp; &nbsp; ', $content);
		$content = str_replace("\n", '<div class="spacer5"></div>', $content);
		return $content;
	}
	
	public static function getCategories(){
		
		$categories = array();
		
		if($handle = opendir(self::$dir)){
			
			$c = 0;
			
			while(false !== ($dir = readdir($handle))) {
			
				if(is_dir(self::$dir . $dir) && $dir !== '.' && $dir !== '..'){
				
					if($dir === 'all'){
						$key = 0;
					}else{
						$key = $c + 1;
					}

					$categories[$key] = $dir;
					
					++$c;
				}
				
			}
			
			closedir($handle);
		}
		
		$return = !empty($categories) ? $categories : false;
		
		return $return;
		
	}
	
	public static function getLogs($category){
		
		$logs = array();

		$dir = self::$dir . $category . '/';
		
		$files = scandir($dir, 1);
		
		foreach( $files as $file )
		{
			if(is_file($dir . $file) && $file !== '.' && $file !== '..'){
				
				$parts = explode('.', $file);
				
				array_push($logs, $parts[0]);
				
			}
			
		}
		
		
		$return = !empty($logs) ? $logs : false;
		
		return $return;
	}
	
}