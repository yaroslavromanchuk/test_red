<?php

/*****************
 *	Version: 1.0.0
 ******************/

class RvkImage {

	private $_jpeg_quality = 80; // 0 - 100

	public function get_info($filename)
	{
		$result = FALSE;
		if (is_file($filename))
		{
			$result = getimagesize($filename);
		}
		return $result;
	}

	public function get_extension($filename)
	{
		$result = FALSE;
		if ($info = $this->get_info($filename))
		{
			$result = strtolower(image_type_to_extension($info[2], FALSE));
		}
		return $result;
	}

	/**
	 * Copy and resize image
	 */
	public function copy($old_filename, $new_filename, $new_width = FALSE, $new_height = FALSE, $crop = FALSE, $fill = FALSE, $fill_color = array(255, 255 ,255), $watermark = false)
	{
		$result = FALSE;
		if (is_file($old_filename))
		{
			try
			{
				$old_res = getimagesize($old_filename);
				$src_w = $old_res[0];
				$src_h = $old_res[1];
				switch ($old_res[2])
				{
					case IMAGETYPE_GIF:
						$old_image = imagecreatefromgif($old_filename);
						break;
					case IMAGETYPE_PNG:
						$old_image = imagecreatefrompng($old_filename);
						break;
					case IMAGETYPE_JPEG:
					default:
						$old_image = imagecreatefromjpeg($old_filename);
						break;
				}

				// resizing
				if ($new_width || $new_height)
				{
					$dst_x = $src_x = 0;
					$dst_y = $src_y = 0;
					$old_r = $old_res[0]/$old_res[1];
					if ($new_width && ! $new_height)
					{
						$new_res[0] = $dst_w = $new_width;
						$new_res[1] = $dst_h = $new_width/$old_r;
					}
					elseif ( ! $new_width && $new_height)
					{
						$new_res[0] = $dst_w = $new_height*$old_r;
						$new_res[1] = $dst_h = $new_height;
					}
					elseif ( ! $crop)
					{
						if ( ! $fill)
						{
							if ($new_width/$new_height > $old_r)
							{
								$new_res[0] = $dst_w = $new_height*$old_r;
								$new_res[1] = $dst_h = $new_height;
							}
							else
							{
								$new_res[0] = $dst_w = $new_width;
								$new_res[1] = $dst_h = $new_width/$old_r;
							}
						}
						else
						{
							$new_r = $new_width/$new_height;
							$new_res[0] = $new_width;
							$new_res[1] = $new_height;
							if ($new_r > $old_r)
							{
								$dst_h = $new_res[1];
								$dst_w = $dst_h*$old_r;
								$dst_x = ($new_res[0] - $dst_w) / 2;
							}
							else
							{
								$dst_w = $new_res[0];
								$dst_h = $dst_w/$old_r;
								$dst_y = ($new_res[1] - $dst_h) / 2;
							}
						}
					}
					else
					{
						$new_r = $new_width/$new_height;
						$new_res[0] = $dst_w = $new_width;
						$new_res[1] = $dst_h = $new_height;
						if ($new_r > $old_r)
						{
							$src_h = $old_res[0]/$new_r;
							$src_y = ($old_res[1] - $src_h) / 2;
						}
						else
						{
							$src_w = $old_res[1]*$new_r;
							$src_x = ($old_res[0] - $src_w) / 2;
						}
					}
					$new_image = imagecreatetruecolor($new_res[0], $new_res[1]);
					if ( ! $crop && $fill)
					{
						$color = imagecolorallocate($new_image, $fill_color[0], $fill_color[1], $fill_color[2]);
						imagefill($new_image, 0, 0, $color);
					}
					imagecopyresampled($new_image, $old_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
					//
					if ($watermark){
						$alpha_level = 80;
						$font = $_SERVER['DOCUMENT_ROOT'].'/js/tahoma.ttf';
						$width = imagesx($new_image);
						$height = imagesy($new_image);
						$angle =  -rad2deg(atan2((-$height),($width)));
						$text = "RED.UA";
						$c = imagecolorallocatealpha($new_image, 128, 128, 128, $alpha_level);
						$size = (($width+$height)/2)*2/strlen($text);
						$size = ceil($size/2);
						$box  = imagettfbbox ( $size, $angle, $font, $text );
						$x = $width/2 - abs($box[4] - $box[0])/2;
						$y = $height/2 + abs($box[5] - $box[1])/2;
						imagettftext($new_image,$size ,$angle, $x, $y, $c, $font, $text);
					}
				}
				else
				{
					$new_image = imagecreatetruecolor($old_res[0], $old_res[1]);
					imagecopy($new_image, $old_image, 0, 0, 0, 0, $old_res[0], $old_res[1]);
				}
				// get extension of new filename
				$new_ext = pathinfo($new_filename, PATHINFO_EXTENSION);
				// save
				switch (strtolower($new_ext))
				{
					case 'gif':
						imagegif($new_image, $new_filename);
						break;
					case 'png':
						imagepng($new_image, $new_filename);
						break;
					case 'jpg':
					case 'jpeg':
					default:
						imagejpeg($new_image, $new_filename, $this->_jpeg_quality);
						break;
				}
				imagedestroy($old_image);
				imagedestroy($new_image);

				$result = TRUE;
			}
			catch (Exception $e)
			{
				$result = FALSE;
			}
		}
		return $result;
	}

	/**
	 * Copy and cut image
	 */
	public function cut($old_filename, $new_filename, $cut_x, $cut_y, $cut_w, $cut_h, $dst_w, $dst_h)
	{
		$result = FALSE;
		if (is_file($old_filename))
		{
			try
			{
				$old_res = getimagesize($old_filename);
				$src_w = $old_res[0];
				$src_h = $old_res[1];
				switch ($old_res[2])
				{
					case IMAGETYPE_GIF:
						$old_image = imagecreatefromgif($old_filename);
						break;
					case IMAGETYPE_PNG:
						$old_image = imagecreatefrompng($old_filename);
						break;
					case IMAGETYPE_JPEG:
					default:
						$old_image = imagecreatefromjpeg($old_filename);
						break;
				}

				$new_image = imagecreatetruecolor($dst_w, $dst_h);

				// cut
				imagecopyresampled($new_image, $old_image, 0, 0, $cut_x, $cut_y, $dst_w, $dst_h, $cut_w, $cut_h);

				// get extension of new filename
				$new_ext = pathinfo($new_filename, PATHINFO_EXTENSION);
				// save
				switch (strtolower($new_ext))
				{
					case 'gif':
						imagegif($new_image, $new_filename);
						break;
					case 'png':
						imagepng($new_image, $new_filename);
						break;
					case 'jpg':
					case 'jpeg':
					default:
						imagejpeg($new_image, $new_filename, $this->_jpeg_quality);
						break;
				}
				imagedestroy($old_image);
				imagedestroy($new_image);

				$result = TRUE;
			}
			catch (Exception $e)
			{
				$result = FALSE;
			}
		}
		return $result;
	}

	/**
	 * Copy and cut image (extended)
	 */
	public function cut_ext($old_filename, $new_filename, $cut_x, $cut_y, $cut_w, $cut_h, $dst_x, $dst_y, $dst_w, $dst_h, $w, $h, $fill_color = array(255, 255 ,255))
	{
		$result = FALSE;
		if (is_file($old_filename))
		{
			try
			{
				$old_res = getimagesize($old_filename);
				$src_w = $old_res[0];
				$src_h = $old_res[1];
				switch ($old_res[2])
				{
					case IMAGETYPE_GIF:
						$old_image = imagecreatefromgif($old_filename);
						break;
					case IMAGETYPE_PNG:
						$old_image = imagecreatefrompng($old_filename);
						break;
					case IMAGETYPE_JPEG:
					default:
						$old_image = imagecreatefromjpeg($old_filename);
						break;
				}

				$new_image = imagecreatetruecolor($w, $h);
				$color = imagecolorallocate($new_image, $fill_color[0], $fill_color[1], $fill_color[2]);
				imagefill($new_image, 0, 0, $color);

				// cut
				imagecopyresampled($new_image, $old_image, $dst_x, $dst_y, $cut_x, $cut_y, $dst_w, $dst_h, $cut_w, $cut_h);

				// get extension of new filename
				$new_ext = pathinfo($new_filename, PATHINFO_EXTENSION);
				// save
				switch (strtolower($new_ext))
				{
					case 'gif':
						imagegif($new_image, $new_filename);
						break;
					case 'png':
						imagepng($new_image, $new_filename);
						break;
					case 'jpg':
					case 'jpeg':
					default:
						imagejpeg($new_image, $new_filename, $this->_jpeg_quality);
						break;
				}
				imagedestroy($old_image);
				imagedestroy($new_image);

				$result = TRUE;
			}
			catch (Exception $e)
			{
				$result = FALSE;
			}
		}
		return $result;
	}

}

?>