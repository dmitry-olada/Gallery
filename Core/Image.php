<?php

namespace Core;

class Image
{
    public  function SetImageSize($img, $width, $height, $AspectRatio = true, $name = false)
    {
        $file_type = trim(strtolower(strrchr($img, '.')),'.');
        $file_type = !stristr($file_type, '?') ? $file_type : substr($file_type, 0, strrpos($file_type, '?'));

        switch ($file_type){
            case "jpg":
            case "jpeg":
                $srcImage = ImageCreateFromJPEG($img);
                break;
            case "gif":
                $srcImage = ImageCreateFromGIF($img);
                break;
            case "png":
                $srcImage = ImageCreateFromPNG($img);
                break;
            default:
                return false;
        }

        $srcWidth = ImageSX($srcImage);
        $srcHeight = ImageSY($srcImage);

        if(($width > $srcWidth) || ($height < $srcHeight)){
            if($AspectRatio){
                if(($width > $srcWidth) && ($height > $srcHeight)) {
                    $destWidth = $srcWidth;
                    $destHeight = $srcHeight;
                }
                else{
                    $ratioWidth = $srcWidth/$width;
                    $ratioHeight = $srcHeight/$height;
                    if($ratioWidth < $ratioHeight){
                        $destWidth = intval($srcWidth/$ratioHeight);
                        $destHeight = $height;
                    }
                    else{
                        $destWidth = $width;
                        $destHeight = intval($srcHeight/$ratioWidth);
                    }
                }
            }else{
                $destHeight = $height;
                $destWidth = $width;
            }

            $resImage = ImageCreateTrueColor($destWidth, $destHeight);
            ImageCopyResampled($resImage, $srcImage, 0, 0, 0, 0, $destWidth, $destHeight, $srcWidth, $srcHeight);

            $img = $name?$name:$img;

            switch ($file_type){
                case "jpg":
                case "jpeg":
                    return ImageJPEG($resImage, $img, 100);
                    break;
                case "gif":
                    return ImageGIF($resImage, $img);
                    break;
                case "png":
                    return ImagePNG($resImage, $img);
                    break;
            }
            ImageDestroy($srcImage);
            ImageDestroy($resImage);
        }
        return false;
    }

}