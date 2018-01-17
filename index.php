<?

require("init.php");

if( is_file($_GET["p"].".php") ){ 
 $page = $_GET["p"].".php";
}else{
 $page = "intro.php";
}

$base = explode("/",$_SERVER["REQUEST_URI"]);
unset($base[count($base)-1]);
$base = "http://".$_SERVER["HTTP_HOST"].implode("/",$base)."/";

echo tpl(file_get_contents("skins/".$s["skin"]."/head.tpl"),array( "base" => $base , "userline" => userline() , "skin" => $s["skin"] ) );

require($page);

echo tpl(file_get_contents("skins/".$s["skin"]."/foot.tpl"),array("sec" => round(microtime_float()-$start_time,3) , "skin" => $s["skin"]));

hit();

?>