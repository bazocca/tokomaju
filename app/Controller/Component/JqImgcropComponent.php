<?php 
	/**
	*	Title:
	*		Image cropping component
	*
	*	Description:
	*		Handle image cropping or scaling option.
	*		Creating Thumbnail Image
	*
	**/
class JqImgcropComponent extends Component {
	public function __construct(ComponentCollection $collection,
      $settings = array()) {
        parent::__construct($collection, $settings);
    }
	/**
	 * Manage Image that was uploaded
	 * @param string $uploadedInfo contains information of uploaded file (name, size)
	 * @param string $uploadTo contains directory location address of the image that has been stored
	 * @param string $prefix contains prefix of filename that will be written
	 * @return string Image Path, Image Name, Image Width, Image Height
	 * @public
	 **/
    function uploadImage($uploadedInfo, $uploadTo, $prefix){
        $webpath = $uploadTo;
        $upload_dir = WWW_ROOT.str_replace("/", DS, $uploadTo);
        $upload_path = $upload_dir.DS;
        $max_file = "34457280";                         // Approx 30MB
        $max_width = 800;
		$max_height = 600;

        $userfile_name = $uploadedInfo['name'];
        $userfile_tmp =  $uploadedInfo["tmp_name"];
        $userfile_size = $uploadedInfo["size"];
        $filename = $prefix.basename($uploadedInfo["name"]);
        $file_ext = substr($filename, strrpos($filename, ".") + 1);
        $uploadTarget = $upload_path.$filename;

        if(empty($uploadedInfo)) {
                  return false;
                }  

        if (isset($uploadedInfo['name'])){
            move_uploaded_file($userfile_tmp, $uploadTarget );
            chmod ($uploadTarget , 0777);
            $width = $this->getWidth($uploadTarget);
            $height = $this->getHeight($uploadTarget);
            // Scale the image if it is greater than the width set above
            if ($width > $max_width){
                $scale = $max_width/$width;
                $uploaded = $this->resizeImage($uploadTarget,$width,$height,$scale);
            }else{
                $scale = 1;
                $uploaded = $this->resizeImage($uploadTarget,$width,$height,$scale);
            }
        }
        return array('imagePath' => $webpath.$filename, 'imageName' => $filename, 'imageWidth' => $this->getWidth($uploadTarget), 'imageHeight' => $this->getHeight($uploadTarget));
    }
	/**
	 * Retrieve height of an image
	 * @param string $image contains location address of the image
	 * @return integer $height height of the image
	 * @public
	 **/
    function getHeight($image) {
        $sizes = getimagesize($image);
        $height = $sizes[1];
        return $height;
    }
	
	/**
	 * Retrieve width of an image
	 * @param string $image contains location address of the image
	 * @return integer $width width of the image
	 * @public
	 **/
    function getWidth($image) {
        $sizes = getimagesize($image);
        $width = $sizes[0];
        return $width;
    }

	/**
	 * resize original image to display image with specific parameter value
	 * @param image $src contains source original image
	 * @param image $dst contains destination image blueprint for display image result
	 * @param integer $width width of the image
	 * @param integer $height height of the image
 	 * @param integer $crop[optional] contains resize image will be cropped(1) or not(0)
	 * @return image contains display image result
	 * @public
	 **/
	function image_resize($src, $dst, $width, $height, $crop=0 , $src_x=0 , $src_y = 0 , $dst_x = 0 , $dst_y = 0)
	{
	  $w = $this->getWidth($src);
	  $h = $this->getHeight($src);
	  $type = strtolower(substr(basename($src), strrpos(basename($src), ".") + 1));
	  if($type == 'jpeg') $type = 'jpg';
	  
	  $img = "";
	  switch($type)
	  {
	    case 'bmp': $img = imagecreatefromwbmp($src); break;
	    case 'gif': $img = imagecreatefromgif($src); break;
	    case 'jpg': $img = imagecreatefromjpeg($src); break;
	    case 'png': $img = imagecreatefrompng($src); break;
	  }
	  
	  $resize_width = $w;
	  $resize_height = $h;
	
	  // resize
	  if($crop == 1)
	  {
	    if($w >= $width && $h >= $height)
	  	{
	  		$tempScaleX = $width * 1.0 / $w;
			$tempScaleY = $height * 1.0 / $h;
			$ratio = max($tempScaleX , $tempScaleY);
			$resize_height = ceil($h * 1.0 * $ratio);
			$resize_width = ceil($w * 1.0 * $ratio);
			if($tempScaleX > $tempScaleY)
			{	
				$dst_y = -(abs($resize_height - $height) / 2.0);
			}					
			else
			{	
				$dst_x = -(abs($resize_width - $width) / 2.0);
			}
	  	}
		else if($w >= $width)
		{
			$dst_x = -(abs($resize_width - $width) / 2.0);
			$height = $resize_height; 
		}
		else if($h >= $height)
		{
			$dst_y = -(abs($resize_height - $height) / 2.0);
			$width = $resize_width;
		}
		else
		{
			$width = $resize_width;
			$height = $resize_height;
		}
	  }
	  // MANUAL CROPPING !!
	  else if($crop == 2)
	  {
	  	if($width > 0 && $height > 0)
		{
			$resize_width = $width;
		  	$resize_height = $height;
			$w = $width;
			$h = $height;
		}
		else // IF INVALID SIZE, THEN USE FULL CROP AS DEFAULT !!
		{
			$width = $w;
			$height = $h;
			$src_x = 0;
			$src_y = 0;
		}
	  }
	  // WITHOUT CROPPING !!
	  else
	  {
		if($w >= $width || $h >= $height)
	  	{
	  		$tempScaleX = $width * 1.0 / $w;
			$tempScaleY = $height * 1.0 / $h;
			$ratio = min($tempScaleX , $tempScaleY);
			$resize_height = ceil($h * 1.0 * $ratio);
			$resize_width = ceil($w * 1.0 * $ratio);
			
			if($tempScaleX > $tempScaleY)
			{
				$width = $resize_width;
			}
			else
			{		
				$height = $resize_height;
			}
	  	}
		else
		{
			$width = $resize_width;
			$height = $resize_height;
		}
	  }
	
	  $new = imagecreatetruecolor($width, $height);	
	  // preserve transparency
	  if($type == "gif" || $type == "png")
      {
          $transindex = imagecolortransparent($img);
          
          if($transindex >= 0)
          {
              $transcol = imagecolorsforindex($img, $transindex);
              $transindex = imagecolorallocatealpha($new, $transcol['red'], $transcol['green'], $transcol['blue'], 127);
              imagefill($new, 0, 0, $transindex);
              imagecolortransparent($new, $transindex);
          }
          else if($type == "png")
          {
              imagealphablending($new, false);
              $color = imagecolorallocatealpha($new, 0, 0, 0, 127);
              imagefill($new, 0, 0, $color);
              imagesavealpha($new, true);
          }
      }
	
	  imagecopyresampled($new, $img, $dst_x, $dst_y, $src_x, $src_y, $resize_width, $resize_height, $w, $h);
	  
	  switch($type){
	    case 'bmp': imagewbmp($new, $dst); break;
	    case 'gif': imagegif($new, $dst); break;
	    case 'jpg': imagejpeg($new, $dst , 90); break;
	    case 'png': imagepng($new, $dst,9); break;
	  }
	  
	  chmod($dst, 0777);
      return filesize($dst);
	}

	/**
	 * resize original image to thumbnail image with specific parameter value
	 * @param image $src contains source original image
	 * @param image $dst contains destination image blueprint for thumbnail image result
	 * @param integer $width width of the image
	 * @param integer $height height of the image
 	 * @param integer $crop[optional] contains resize image will be cropped(1) or not(0)
	 * @return image contains thumbnail image result
	 * @public
	 **/
	function thumb_resize($src, $dst, $width, $height, $crop=0)
	{
	  $w = $this->getWidth($src);
	  $h = $this->getHeight($src);
	  $type = strtolower(substr(basename($src), strrpos(basename($src), ".") + 1));
	  if($type == 'jpeg') $type = 'jpg';
	  
	  $img = "";
	  switch($type){
	    case 'bmp': $img = imagecreatefromwbmp($src); break;
	    case 'gif': $img = imagecreatefromgif($src); break;
	    case 'jpg': $img = imagecreatefromjpeg($src); break;
	    case 'png': $img = imagecreatefrompng($src); break;
	  }
	  
	  $src_x = 0;
	  $src_y = 0;
	  $dst_x = 0;
	  $dst_y = 0;
	  $resize_width = $w;
	  $resize_height = $h;
	
	  // resize
	  if($crop)
	  {
	    if($w >= $width && $h >= $height)
	  	{
	  		$tempScaleX = $width * 1.0 / $w;
			$tempScaleY = $height * 1.0 / $h;
			$ratio = max($tempScaleX , $tempScaleY);
			$resize_height = ceil($h * 1.0 * $ratio);
			$resize_width = ceil($w * 1.0 * $ratio);
			if($tempScaleX > $tempScaleY)
			{	
				$dst_y = -(abs($resize_height - $height) / 2.0);
			}					
			else
			{	
				$dst_x = -(abs($resize_width - $width) / 2.0);
			}
	  	}
		else if($w >= $width)
		{
			$dst_x = -(abs($resize_width - $width) / 2.0);
			if($type == "gif" or $type == "png")
			{
				$dst_y = +(abs($resize_height - $height) / 2.0);
			}
			else
			{
				$height = $resize_height;
			} 
		}
		else if($h >= $height)
		{
			$dst_y = -(abs($resize_height - $height) / 2.0);
			if($type == "gif" or $type == "png")
			{
				$dst_x = +(abs($resize_width - $width) / 2.0);
			}
			else
			{
				$width = $resize_width;
			}
		}
		else
		{
			if($type == "gif" or $type == "png")
			{
				$dst_x = (abs($resize_width - $width) / 2.0);
				$dst_y = (abs($resize_height - $height) / 2.0);
			}
			else
			{
				$width = $resize_width;
				$height = $resize_height;
			}
		}
	  }
	  else // no cropping !!!
	  {
		if($w >= $width || $h >= $height)
	  	{
	  		$tempScaleX = $width * 1.0 / $w;
			$tempScaleY = $height * 1.0 / $h;
			$ratio = min($tempScaleX , $tempScaleY);
			$resize_height = ceil($h * 1.0 * $ratio);
			$resize_width = ceil($w * 1.0 * $ratio);
			
			if($type == "gif" or $type == "png")
			{
				if($tempScaleX > $tempScaleY)
				{
					$dst_x = +(abs($resize_width - $width) / 2.0);
				}
				else
				{
					$dst_y = +(abs($resize_height - $height) / 2.0); 
				}
			}
			else
			{
				if($tempScaleX > $tempScaleY)
				{
					$width = $resize_width;
				}
				else
				{		
					$height = $resize_height;
				}
			}
	  	}
		else
		{
			if($type == "gif" or $type == "png")
			{
				$dst_x = (abs($resize_width - $width) / 2.0);
				$dst_y = (abs($resize_height - $height) / 2.0);
			}
			else
			{
				$width = $resize_width;
				$height = $resize_height;
			}
		}
	  }
	
	  $new = imagecreatetruecolor($width, $height);
	
	  // preserve transparency
      if($type == "gif" || $type == "png")
      {
          $transindex = imagecolortransparent($img);
          
          if($transindex >= 0)
          {
              $transcol = imagecolorsforindex($img, $transindex);
              $transindex = imagecolorallocatealpha($new, $transcol['red'], $transcol['green'], $transcol['blue'], 127);
              imagefill($new, 0, 0, $transindex);
              imagecolortransparent($new, $transindex);
          }
          else if($type == "png")
          {
              imagealphablending($new, false);
              $color = imagecolorallocatealpha($new, 0, 0, 0, 127);
              imagefill($new, 0, 0, $color);
              imagesavealpha($new, true);
          }
      }
	
	  imagecopyresampled($new, $img, $dst_x, $dst_y, $src_x, $src_y, $resize_width, $resize_height, $w, $h);
	
	  switch($type){
	    case 'bmp': imagewbmp($new, $dst); break;
	    case 'gif': imagegif($new, $dst); break;
	    case 'jpg': imagejpeg($new, $dst , 90); break;
	    case 'png': imagepng($new, $dst,9); break;
	  }
	  
	  chmod($dst, 0777);
      return filesize($dst);
	}
}