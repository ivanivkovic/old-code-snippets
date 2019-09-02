<?php

class ImageCropper{

	private $imgSrc;
	private $myImage;
	private $cropHeight;
	private $cropWidth;
	private $x;
	private $y;
	private $thumb;
	
	# Sets the image. Thumb function.
	public function setImage($image)
	{
		$this->imgSrc = $image;
		list($width, $height) = getimagesize($this->imgSrc);
		$parts = explode('.', $this->imgSrc);
		$file_type = end($parts);
		
		switch(strtolower($file_type))
		{
			case 'jpg':
			case 'jpeg':
			$file_type = 'jpeg';
			break;
			
			case 'gif':
			$file_type = 'gif';
			break;
			
			case 'png':
			$file_type = 'png';
			break;
		}
		
		$imagecreatefromfile_type = 'imagecreatefrom' . strtolower($file_type);
		$this->myImage = $imagecreatefromfile_type($this->imgSrc) or die('Error: Cannot find image!'); 
		$biggestSide = $width > $height ? $width : $height; # Find biggest length
		
		# The crop size will be half that of the largest side 
		   $cropPercent = .55; # Zoom.
		   $this->cropWidth = $biggestSide * $cropPercent; 
		   $this->cropHeight = $biggestSide * $cropPercent;
		   $this->x = ($width - $this->cropWidth) / 2;
		   $this->y = ($height - $this->cropHeight) / 2;
		}  
	
	# Creates the image. Thumb function.
	public function createThumb($thumbSize, $thumbSize2)
	{
		$this->thumb = imagecreatetruecolor($thumbSize, $thumbSize2);
		imagecopyresampled($this->thumb, $this->myImage, 0, 0,$this->x, $this->y, $thumbSize, $thumbSize, $this->cropWidth, $this->cropHeight); 
	}  
		
	# Saves/Loads the image. Thumb function.
	public function renderImage($fname)
	{
		# header('Content-type: image/jpeg'); #Good if we wanna generate the thumbnail and output it.
		imagejpeg($this->thumb, $fname, 100);
		imagedestroy($this->thumb);
	}
}
