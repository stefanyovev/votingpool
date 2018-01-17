<?

header("Content-type: text/plain");

$lines = 0;
$chars = 0;

$d = opendir(".");

readdir($d);
readdir($d);

while( $file = readdir($d) ){
 $l = 0;
 $ext = explode(".",$file);
 $ext = $ext[count($ext)-1];
 if( ($ext=="php")||($ext=="css")||($ext=="js")||($ext=="txt")||($ext=="tpl") ){
  $f = fopen($file,"r");
  while( !feof($f) ){
   $ch = fread($f,1);
   if( $ch == "\n" ){ $l++; }
  }
  fclose($f);
  $lines += $l;
  $chars += filesize($file);
  echo str_pad($file,30)." \t ".str_pad($l,3)." lines,   ".str_pad(filesize($file),5)." chars\r\n";
 }
}

echo "---------\r\n Total $lines lines, $chars chars";

?>