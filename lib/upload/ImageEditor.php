<?php

class upload_ImageEditor{
	
	protected $maxWidth;
	protected $maxHeight;
	protected $thumbWidth;
	protected $thumbHeight;
	
	protected function setWidthHeight($width, $height, $maxwidth, $maxheight){
		if ($width > $height){
			if ($width > $maxwidth){
				//Then you have to resize it.
				//Then you have to resize the height to correspond to the change in width.
				$difinwidth = $width / $maxwidth;
				$height = intval($height / $difinwidth);
				//Then default the width to the maxwidth;
				$width = $maxwidth;
				//Now, you check if the height is still too big in case it was to begin with.
				if ($height > $maxheight){
					//Rescale it.
					$difinheight = $height / $maxheight;
					$width = intval($width / $difinheight);
					//Then default the height to the maxheight;
					$height = $maxheight;
				}
			} else {
				if ($height > $maxheight){
					//Rescale it.
					$difinheight = $height / $maxheight;
					$width = intval($width / $difinheight);
					//Then default the height to the maxheight;
					$height = $maxheight;
				}
			}
		} else {
			if ($height > $maxheight){
					//Then you have to resize it.
					//You have to resize the width to correspond to the change in width.
					$difinheight = $height / $maxheight;
					$width = intval($width / $difinheight);
					//Then default the height to the maxheight;
					$height = $maxheight;
					//Now, you check if the width is still too big in case it was to begin with.
				if ($width > $maxwidth){
					//Rescale it.
					$difinwidth = $width / $maxwidth;
					$height = intval($height / $difinwidth);
					//Then default the width to the maxwidth;
					$width = $maxwidth;
				}
			} else {
				if ($width > $maxwidth){
					//Rescale it.
					$difinwidth = $width / $maxwidth;
					$height = intval($height / $difinwidth);
					//Then default the width to the maxwidth;
					$width = $maxwidth;
				}
			}
		}
		
		$widthheightarr = array ("$width","$height");
		
		return $widthheightarr;
	}
	
	public function createImage($images, $thumb, $width = null, $height = null){
		
		if(is_array($images)){
			$imagenames = array_map(array('FileUpload', 'createImage'), $images, $thumb);
			return $imagenames;
		}else{
			$nameParts = explode (".", $images);
			if($thumb){
				$imagename = "th_" . $nameParts[0];
				if($width && $height){
					$this->thumbWidth = $width;
					$this->thumbHeight = $height;
				}
				$imagename = $this->resizeImage($images, $imagename, $this->thumbWidth, $this->thumbHeight);
			}else{
				$imagename = $nameParts[0];
				if($width && $height){
					$this->maxWidth = $width;
					$this->maxHeight = $height;
				}
				$imagename = $this->resizeImage($images, $imagename, $this->maxWidth, $this->maxHeight);
			}
			
			return $imagename;
		}
	}
	
	//This function creates a thumbnail and then saves it.
	protected function resizeImage ($img, $imgname, $constrainw, $constrainh){
		//Find out the old measurements.
		$oldsize = getimagesize ($img);
		//Find an appropriate size.
		$newsize = $this->setWidthHeight ($oldsize[0], $oldsize[1], $constrainw, $constrainh);
		//Create a duped thumbnail.
		//$nameParts = explode (".", $img);
		//Check if you need a gif or jpeg.
		/*if ($nameParts[1] == "gif"){
			$src = imagecreatefromgif ($img);
		} else {*/
			$src = imagecreatefromjpeg ($img);
		//}
		//Make a true type dupe.
		$dupe = imagecreatetruecolor ($newsize[0], $newsize[1]);
		//Resample it.
		imagecopyresampled ($dst, $src, 0, 0, 0, 0, $newsize[0], $newsize[1], $oldsize[0], $oldsize[1]);
		//Create a thumbnail.
		
		//$thumbname = $imgName . $nameParts[1];
		$thumbname = $imgName . ".jpg";
		/*if ($nameParts[1] == "gif"){
			imagegif ($dupe, $thumbname);
		} else {*/
			imagejpeg ($dupe, $thumbname);
		//}
		//And then clean up.
		imagedestroy ($dupe);
		imagedestroy ($src);
		
		return $thumbname;
	}
	
	public function setMaxWidth($width){
		$this->maxWidth = $width;
	}
	
	public function setMaxHeight($height){
		$this->maxHeight = $height;
	}
	
	public function setMaxWidthHeight($width, $height){
		$this->maxWidth = $width;
		$this->maxHeight = $height;
	}
	
	public function setThumbWidth($width){
		$this->thumbWidth = $width;
	}
	
	public function setThumbHeight($height){
		$this->thumbHeight = $height;
	}
	
	public function setThumbWidthHeight($width, $height){
		$this->thumbWidth = $width;
		$this->thumbHeight = $height;
	}
}
?>