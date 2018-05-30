<?php
class Mimeg
    //extends wsActiveRecord
{

    public static function getrealpath($file_name){
//		if (isset($_GET['adm'])) {
/*
			echo '<pre>';
			print_r($filename);
			echo '</pre>';
*/
//		}
        $filename_dest = '';

        $default = array('path', 'type', 'filename', 'id', 'width', 'height', 'crop', 'crop_coords', 'fill', 'fill_color', 'original');
        $get = array();
        $temp_info = explode('/', $file_name);
        $path_info = array();
        for ($i = 0, $c = count($temp_info); $i < $c; $i = $i+2){
            $path_info[strtolower(@$temp_info[$i])] = @$temp_info[$i+1];
        }
        foreach ($default as $item){
            $get[$item] = @$path_info[$item];
        }

        $get['original'] = (int)$get['original'];



        $get['width'] = (int)$get['width'];
        $get['height'] = (int)$get['height'];
        $get['crop'] = $get['crop'] ? (strtolower($get['crop']) === 'true' ? 't' : 'f') : FALSE;
        $get['fill'] = $get['fill'] ? (strtolower($get['fill']) === 'true' ? 't' : 'f') : FALSE;
        $get['fill_color'] = $get['fill_color'] ? explode('_', $get['fill_color']) : FALSE;
        $get['crop_coords'] = $get['crop_coords'] ? explode('_', $get['crop_coords']) : FALSE;

        $filename_original = FALSE;
        $file = FALSE;

        $get['type'] = "standard";



        if ($get['type'] && $get['filename']){
            $filename_original = wsActiveRecord::useStatic('Shoparticles')->getSystemPath($get['type'], $get['filename']);

        }elseif ($get['path']){
            $filename_original = FCPATH . $get['path'];
        }

        if ($filename_original) {
            $ext = pathinfo($filename_original, PATHINFO_EXTENSION);
            if (!in_array(strtolower($ext), array('jpeg', 'jpg', 'gif', 'png', 'flv'), TRUE))
                $filename_original = FALSE;
        }



        if (@$file || @$filename_original){

            if (!$filename_original){
                $filename_original = $file->getSystemPath();
            }

            $ext = pathinfo($filename_original, PATHINFO_EXTENSION);

            // image for video
            if (strtolower($ext) == 'flv') {
                $ext = 'jpeg';
                $filename_original = substr($filename_original, 0, strripos($filename_original, 'flv')) . $ext;
            }

            if ($get['original']) {

                $filename_original_org = substr($filename_original, 0, strrpos($filename_original, '.')) . "_org.{$ext}";
                if (!is_file($filename_original_org) && is_file($filename_original)){
                    copy($filename_original, $filename_original_org);
                }
                $filename_original = str_replace($_SERVER['DOCUMENT_ROOT'], '' ,$filename_original_org);
            }

            $filename_dest = substr($filename_original, 0, strrpos($filename_original, '.'));

            if ($get['width']){
                $filename_dest .= '_w' . $get['width'];
            }
            if ($get['height']){
                $filename_dest .= '_h' . $get['height'];
            }
            if ($get['crop']){
                $filename_dest .= '_c' . $get['crop'];
            }
            if ($get['fill']){
                $filename_dest .= '_f' . $get['fill'];
            }

            if ($get['fill_color']){
                $filename_dest .= '_fc' . implode('_', $get['fill_color']);
            }
            if ($get['crop_coords']){
                $filename_dest .= '_cc' . implode('_', $get['crop_coords']);
            }


            $filename_dest .= '.' . $ext;
            //sort cache by size
            if ($get['width'] || $get['height']){
                $folder = '/files/'. $get['width'].'_'.$get['height'].'/';
                $filename_dest = $folder.pathinfo($filename_dest, PATHINFO_BASENAME);
            }
        }

        return $filename_dest;
    }

    public static function generateAllsizes($filename){
        ///mimage/type/1/width/36/height/36/crop/false/fill/true/fill_color/255_255_255/filename/
        Mimeg::resize(36, 36, $filename);
        Mimeg::resize(155, 155, $filename);
        Mimeg::resize(360, 360, $filename);
      //  Mimeg::resize(800, 600, $filename);
        //Mimeg::resize(396, 365, $filename);
		Mimeg::resize(600, 600, $filename);
        return true;
    }
	

    public static function resize($w, $h, $filename_original){
        //2b56eead95251c41f60aab4ba54c6013_w800_h600_cf_ft_fc255_255_255.jpg
        $folder = $_SERVER['DOCUMENT_ROOT'].'/files/'. $w.'_'.$h.'/';
        if (!file_exists($folder)){
            mkdir($folder);
        }
        //d($filename_original);

        $filename_dest = pathinfo($filename_original);
        $filename_dest = $folder.$filename_dest['filename'].'_w'.$w.'_h'.$h.'_cf_ft_fc255_255_255.'.strtolower($filename_dest['extension']);
        $rvk_image = new RvkImage();
        $result = $rvk_image->copy($filename_original, $filename_dest, $w, $h, false, true, array(255, 255, 255));

        //d($filename_dest);
        return $result;
    }
	
	public static function deleteAllsizes($filename){
        ///mimage/type/1/width/36/height/36/crop/false/fill/true/fill_color/255_255_255/filename/
        Mimeg::deleteimg(36, 36, $filename);
        Mimeg::deleteimg(155, 155, $filename);
        Mimeg::deleteimg(360, 360, $filename);
       // Mimeg::deleteimg(800, 600, $filename);
		Mimeg::deleteimg(600, 600, $filename);
        //Mimeg::deleteimg(396, 365, $filename);
        return true;
    }
	
	 public static function deleteimg($w, $h, $filename_original){
        //2b56eead95251c41f60aab4ba54c6013_w800_h600_cf_ft_fc255_255_255.jpg
        $folder = $_SERVER['DOCUMENT_ROOT'].'/files/'. $w.'_'.$h.'/';
        if (!file_exists($folder)){
            mkdir($folder);
        }
        //d($filename_original);

        $filename_dest = pathinfo($filename_original);
        $result = @unlink($folder.$filename_dest['filename'].'_w'.$w.'_h'.$h.'_cf_ft_fc255_255_255.'.strtolower($filename_dest['extension']));
        //d($filename_dest);
        return $result;
    }

}