<?php
/**
* Класс формирует эскизы изображений
*/
class shopCreatethumbnails
{
    /**
    *Метод для формирования эскизов
    *@param $src_image_path - путь к оригиналу изображения
    * $size - строка вида "width X height" || массив вида array('width' => int, 'height' => int);
    * $max_size - максимальный размер стороны изображения
    */
    public static function generateThumb($src_image_path, $size, $max_size=false)
    {
        $image = waImage::factory($src_image_path);
        $width = $height = null;
        $size_info = self::parseSize($size);
        $type = $size_info['type'];
        $width = $size_info['width'];
        $height = $size_info['height'];
  
        switch($type)
        {
            case 'crop':
                if(is_numeric($max_size) && $width > $max_size){
                    return null;
                }
                $image->resize($width, $height, waImage::INVERSE)->crop($width, $height);
                break;

            case 'rectangle':
                if(is_numeric($max_size) && (($width > $max_size) || ($height > $max_size))){
                    return null;
                }
                
                if($width > $height){
                    $w = $image->width;
                    $h = $image->width * $height / $width;
                }else{
                    $h = $image->height;
                    $w = $image->height * $width / $height;
                }

                $image->crop($w, $h)->resize($width, $height, waImage::INVERSE);
                break;

            default:
                throw new waException('Unknown type');
                break;
        }
        return $image;
    }
 
    /**
    *Метод создаёт структуру данных для генерации эскизов изображений
    *@param $size - строка вида "width X height" || массив вида array('width' => int, 'height' => int);
    *@return array('type' => string, 'width' => string, 'height' => string);
    */
    public static function parseSize($size)
    {
        $type = 'unknown';
        if(is_array($size)){
            $width = $size['width'];
            $height = $size['height'];
        }else{
            $sizes = explode('X', $size);
            $width = $sizes[0];
            $height = $sizes[1];
        }
  
        if($width == $height){
            $type = 'crop';
        }else{
            $type = 'rectangle';
        }
  
        return array(
            'type' => $type,
            'width' => $width,
            'height' => $height
        );
    }
}