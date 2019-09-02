<?php

class libFile
{
	public static $forbiddenFiles = array('exe', 'bat', 'cmd', 'scr', 'pif', 'php');
	
	public static function getFiles($datafrom, $interid)
	{
		if( ! is_numeric($datafrom) )
		{
			$data = Core::$db->fetch( array('fileid', 'filename'), 'file', array('datafrom' => $datafrom, 'interid' => $interid));
			
			if( ! $data || empty($data) )
			{
				return array();
			}
			
			return $data;
		}
		else
		{
			throw new Exception('Wrong parameter for datafrom.');
		}
	}
	
	// Upload fajla. Ako već postoji fajl će biti spremljen kao datoteka[1].ext
	public static function upload($tmpfile, $file, $datafrom, $interid)
	{
		$dir = Conf::DIR_FILES . $datafrom . '/' . $interid . '/';
		
		if( ! is_dir($dir) )
		{
			mkdir($dir);
		}
		
		$extension = self::getExtension($file);
		$fileName = self::filterExtension($file);
		$tFile = $fileName;
		
		$i = 1;
		
		while( self::exists( $tFile . '.' . $extension, $datafrom, $interid ) )
		{
			$tFile = $fileName . '[' . $i . ']'; ++$i;
		}
		
		unset($i);
		
		$newFile = $dir . $tFile . '.' . $extension;
		
		if( move_uploaded_file($tmpfile, $newFile) )
		{
			Core::$db->insert( array('filename' => $tFile . '.' . $extension, 'userid' => Core::$user->id, 'datafrom' => $datafrom, 'interid' => $interid), 'file' );
			
			return true;
		}

		return false;
	}
	
	public static function exists($file, $datafrom = '', $interid = '')
	{
		if( is_numeric($file) )
		{
			return self::getFileById( $file );
		}
		else if( $file !== '' && $datafrom !== '' && $interid !== '' )
		{
			$file = Conf::DIR_FILES . $datafrom . '/' . $interid . '/' . $file;
			
			if( file_exists($file) )
			{
				return true;
			}
		}
		
		return false;
	}
	
	public static function getFileById( $id )
	{
		$return = Core::$db->fetchOne( array('datafrom', 'filename', 'interid'), 'file', array('fileid' => $id) );
		
		if( $return !== false )
		{
			return $return;
		}
		
		return false;
	}

	public static function serve($data)
	{
		$file = Conf::DIR_FILES . $data['datafrom'] . '/' . $data['interid'] . '/' . $data['filename'];
		
		header('Content-Length: ' . filesize( $file )); 
		header('Content-Type: application/octet-stream'); 
		header('Content-Disposition: attachment; filename=' . $data['filename']); 

		echo file_get_contents( $file );

		die();
	}
	
	public static function serveGeneral($filename)
	{
		$file = Conf::DIR_FILES . 'general/' . $filename;
		
		if( file_exists($file))
		{
			header('Content-Length: ' . filesize( $file )); 
			header('Content-Type: application/octet-stream'); 
			header('Content-Disposition: attachment; filename=' . $filename); 

			echo file_get_contents( $file );
		}
		
		die();
	}

	public static function getExtension( $filename )
	{
		$parts = explode( '.', $filename );
		return end( $parts );
	}
	
	public static function filterExtension( $filename )
	{
		$parts = explode( '.', $filename );
		unset( $parts[ count($parts) - 1 ] );
		
		return implode( '', $parts );
	}
	
	public static function isValidExtension( $file )
	{
		if( in_array( self::getExtension( $file ), self::$forbiddenFiles ) )
		{
			return false;
		}
		
		return true;
	}
	
	public static function writeToFile($folder, $file, $text)
	{
		$file = Conf::DIR_FILES . $folder . '/' . $file;
		
		$handle = fopen( $file, 'w') or die('Cannot open file:  ' . $file);
		
		fwrite($handle, $text);
	}
	
	public static function delete( $fileId )
	{
		$data = self::getFileById($fileId);
		
		if( $data['datafrom'] != NULL )
		{
			if( self::exists($data['filename'], $data['datafrom'], $data['interid']) )
			{
				$file = Conf::DIR_FILES . $data['datafrom'] . '/' . $data['interid'] . '/' . $data['filename'];
				
				if( unlink( $file ) )
				{
					if ( Core::$db->delete( array('fileid' => $fileId), 'file' ) )
					{
						libTemplate::addSuccess(15);
					}
				}
			}
			
			// Ako je dir prazan izbriši ga. ( project/32   folder bi onda bio nepotreban)
			if( self::isEmptyDir( $data['datafrom'], $data['interid'] ) )
			{
				self::deleteDir( $data['datafrom'], $data['interid'] );
			}
		}
	}
	
	// Provjerava ako je prazan folder.
	public static function isEmptyDir( $datafrom, $interid )
	{
		$empty = false;
		$dir = Conf::DIR_FILES . $datafrom . '/' . $interid;
		
		$files = @scandir($dir);
		
		if ( count($files) === 2 )
		{
			$empty = true;
		}
		
		return $empty;
	}
	
	/**
	* Uploada fajlove poslane s post formom.
	* 
	* param string datafrom | za koji su model vezani
	* int interid za koji su vezane datoteke
	*/

	public static function uploadFilesByPost($datafrom, $interid, $postName = 'files')
	{
		if( self::isPendingUpload($postName) )
		{
			foreach( $_FILES[$postName]['name'] as $key => $filename )
			{
				if( self::isValidExtension( $filename ) )
				{
					if ( self::upload( $_FILES[$postName]['tmp_name'][$key], $filename, $datafrom, $interid) )
					{
						libTemplate::addSuccess(6);
					}
				}
				else
				{
					libTemplate::addError(3);
				}
			}
		}
	}
	
	public static function deleteFilesByPost()
	{
		if( self::isPendingDelete() )
		{
			foreach( $_POST['filesdelete'] as $file )
			{
				if( is_numeric( $file ) )
				{
					if( self::getFileById($file) )
					{
						self::delete($file);
					}
				}
			}
		}
	}
	
	public static function deleteAll( $datafrom, $interid )
	{
		$filesList = Core::$db->fetch('fileid', 'file', array('datafrom' => $datafrom, 'interid' => $interid));
		
		if( ! empty( $filesList ) )
		{
			foreach( $filesList as $fileId )
			{
				self::delete( $fileId['fileid'] );
				return true;
			}
		}
		
		return false;
	}
	
	public static function deleteDir( $datafrom, $interid )
	{
		$folder = Conf::DIR_FILES . $datafrom . '/' . $interid;
		
		if( is_dir( $folder ) )
		{
			rmdir( $folder );
			return true;
		}
		
		return false;
	}
	
	public static function isPendingDelete()
	{
		return isset( $_POST['filesdelete'] );
	}
	
	public static function isPendingUpload($filesName = 'files')
	{
		return ! empty( $_FILES[ $filesName ] );
	}
	
	public static function printFilesListForDelete($filesList, $filesName = 'filesdelete[]')
	{
		foreach( $filesList as $file ): ?>
		
			<label class="checkbox">
				<input type="checkbox" name="<?= $filesName ?>" value="<?= $file['fileid'] ?>"/> <?= $file['filename'] ?>
			</label>
		
			<br/>
			
		<? endforeach;
	}
}