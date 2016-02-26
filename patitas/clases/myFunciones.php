<?php
/*
	Héctor Fabián Morales Ramírez
	Tecnólogo en Ingeniería de Sistemas
	Enero 2011
*/

class myFunciones{

    public function generarToken($owner=""){		
        $token = md5(mktime().uniqid());
        mySession::set("token".$owner, $token);
    }

    public function getToken($owner=""){        
        $token = mySession::get("token".$owner);       
        return $token;
    }
    
    public function borrarToken($owner=""){        
        mySession::clear("token".$owner);
    }

    public function reprocesarHtml($html){
        $html = htmlspecialchars_decode($html, ENT_QUOTES);
        return $html;
    }

    public function procesarHtml($html){
        $html = htmlspecialchars($html, ENT_QUOTES);
        return $html;			
    }

    public function descargarArchivo($path, $nombreArchivo){
        $len = filesize($path);		
        
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header("Content-Description: File Transfer");
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=".$nombreArchivo);
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".$len);

        @set_time_limit(0);
        $fp = @fopen($path, "rb");

        $tamBuffer = 524288;         
        ob_clean();
        flush();
        
        while($fp && !feof($fp)) {
            $buffer = fread($fp, $tamBuffer);
            print $buffer;
            flush();
        }

        fclose($fp);
        die();		
    }
    
    public function redimensionarImg($archivo, $dirOrig, $dirDest, $ancho=80, $alto=80){
        $image = new Imagick($dirOrig.DS.$archivo);
        $image->cropThumbnailImage($ancho, $alto);
        $image->writeImage($dirDest.DS.$archivo);
    }    
	
    public function crearThumb($fileName, $imageDir, $imageDest, $ancho = 80, $alto = 80){	
        global $maxPixels;
        $maxPixels = 3840*3840; /*1920x1920*/

        $file = $imageDir.DS.$fileName;
        $fileDest = $imageDest.DS.$fileName;

        list($width, $height) = @getimagesize($file);
        $imagePixels = $width * $height;

        if($maxPixels <  $imagePixels)
            return false;

        /*if ($width == $ancho && $height == $alto){
            return true;
        }*/

        $outputX  = $ancho;
        $outputY  = $alto;

        $quality  = 100;

        $anchoResultado = $width;
        $altoResultado  = $height;

        $anchoMax = $ancho;
        $altoMax = $alto;

        if($anchoResultado > $anchoMax){
            $anchoResultado = $anchoMax;
            $altoResultado = ($height/$width)*$anchoResultado;			
        }

        if($altoResultado > $altoMax){
            $altoResultado  = $altoMax;
            $anchoResultado = ($width/$height)*$altoResultado;
        }

        $outputX  = $anchoResultado;
        $outputY  = $altoResultado;		

        $deltaX   = 0;
        $deltaY   = 0;

        $portionX = $width;
        $portionY = $height;

        $filePartes = pathinfo($file);
        $filePartes['extension'] = strtolower($filePartes['extension']);
        if($filePartes['extension']=="jpg"){
            $filePartes['extension']="jpeg";
        }

        $funcionCreateImageFrom = "imagecreatefrom".$filePartes['extension'];	
        $funcionImage = "image".$filePartes['extension'];

        $imageSrc  = @$funcionCreateImageFrom($file);
        try{
            if (!$imageDest = @imagecreatetruecolor($outputX, $outputY)){
                $imageDest = @imagecreate($outputX, $outputY);
            }
        }
        catch(Exception $e){
            $imageDest = @imagecreate($outputX, $outputY);
        }

        //if (@imagecopyresized($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $outputX, $outputY, $portionX, $portionY)) {
        if (@imagecopyresampled($imageDest, $imageSrc, 0, 0, $deltaX, $deltaY, $outputX, $outputY, $portionX, $portionY)) {
            if($filePartes['extension']=="png"){
                $quality = 0;
            }

            @$funcionImage($imageDest, $fileDest, $quality);
            @imagedestroy($imageSrc);
            @imagedestroy($imageDest);
            return true;
        }

        return false;
    }
}
?>