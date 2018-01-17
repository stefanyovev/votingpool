<? if( $_USER["name"] == "root" ){

if( $_POST ){ 
 foreach( $_POST as $key => $val ){ 
  $s["img_$key"] = $val;
 }
 save_settings($s);
 $s = get_settings();
}

foreach( $s as $key => $val ){
 $tmp = explode("_",$key);
 if( $tmp[0] == "img" ){
  unset($tmp[0]);
  $GLOBALS[implode("_",$tmp)] = $val;
 }
}

$bbox = imagettfbbox($size,0,$font,$s1.$s2);
$w = $bbox[4]-$bbox[6]+30;
$h = $bbox[3]-$bbox[5]+55;

$img = imagecreatetruecolor($w,$h);
$d = imagecreatefromjpeg($dolphin);

$white = imagecolorallocate($img,255,255,255);
$c1 = imagecolorallocate($img,0,122,159);
$c2 = imagecolorallocate($img,234,158,39);

imagefilledrectangle($img,0,0,$w,$h,$white);
imagecopy($img,$d,$w-64,0,0,0,64,64);

imagettftext($img,$size,0,$x_offs,$h-14+$y_offs,$c1,$font,$s1);
$tmp = imagettfbbox($size,0,$font,$s1);
imagettftext($img,$size,0,$tmp[4]-$tmp[6]+$x_offs,$h-14+$y_offs,$c2,$font,$s2);


$newwidth = $w * $prc/100;
$newheight = $h * $prc/100;
$newimg = imagecreatetruecolor($newwidth, $newheight);
imagecopyresampled($newimg, $img, 0, 0, 0, 0, $newwidth, $newheight, $w, $h);

imagejpeg($newimg,"img.jpg",$q);

?>

<form method="POST">
<table>
<tr><td> blue text </td><td> <input type="text" name="s1" value="<? echo $s1; ?>" /> </td></tr>
<tr><td> orange text </td><td> <input type="text" name="s2" value="<? echo $s2; ?>" /> </td></tr>
<tr><td> font size </td><td> <input type="text" name="size" value="<? echo $size; ?>" /> </td></tr>
<tr><td> font file </td><td> <input type="text" name="font" value="<? echo $font; ?>" /> </td></tr>
<tr><td> dolphin file </td><td> <input type="text" name="dolphin" value="<? echo $dolphin; ?>" /> </td></tr>
<tr><td> x_offs </td><td> <input type="text" name="x_offs" value="<? echo $x_offs; ?>" /> </td></tr>
<tr><td> y_offs </td><td> <input type="text" name="y_offs" value="<? echo $y_offs; ?>" /> </td></tr>
<tr><td> quality </td><td> <input type="text" name="q" value="<? echo $q; ?>" /> </td></tr>
<tr><td> resize by % </td><td> <input type="text" name="prc" value="<? echo $prc; ?>" /> </td></tr>
<tr><td align="right" colspan="2"> <input type="submit" value="Change Image"> </td></tr> </td></tr>
</table>
</form>

<?

}else{

 echo "<span class=\"err\">First login as root !</span>";

}

?>