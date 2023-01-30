<?php
session_start();

    if ( !empty($_SESSION['captcha']) )
    {
        unset($_SESSION['captcha']);
    }   

    if (empty($_SESSION['captcha']))
    {
        $str = "";
	    $a = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for ($i = 0; $i <= 5; $i++)
        {
            $str.= $a[rand(0, 61)];
        }
    
    $_SESSION['captcha'] = $str;
}
header ('Content-Type: image/png');
$im = imagecreatetruecolor(70, 30);
$color_texto = imagecolorallocate($im, 14, 233, 137);
imagestring($im, 10, 5, 5,$str, $color_texto);
imagepng($im);
imagedestroy($im);
?>
