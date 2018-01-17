<?

session_set_cookie_params(null);
session_start();

function microtime_float()
{
   list($usec, $sec) = explode(" ", microtime());
   return ((float)$usec + (float)$sec);
}

$start_time = microtime_float();
$_SESSION["start_time"] = $start_time;

header("Content-type: text/html; charset=windows-1251");

require("lib.php");

$s = get_settings();

if( $s["first"] ){
 unset($s["first"]);
 save_settings($s);
 echo "Това е първото стартиране на системата. Трябва да пуснете <a href=\"db.create.php\">db.create.php</a> за да настроите MySQL и да създадете нужните таблици.";
 die();
}

if( (!($link = @mysql_connect($s["dbhost"].":".$s["dbport"],$s["dbuser"],$s["dbpass"]))) || (!mysql_select_db($s["dbname"],$link)) ){
  echo "Грешка при свързването към MySQL сървъра. Изпълни <a href=\"db.create.php\">db.create.php</a> за настроийки.";
  die();
}

if( ($_POST["loginuser"]) && ($_POST["loginpass"]) ){ $msg = login(); }else{ $msg = ""; }

if( $_POST["logout"] ){ unset($_SESSION["uid"]); }

if( $_SESSION["uid"] ){ $_USER = getuserbyid($_SESSION["uid"]); }else{ unset($_USER); }

if( $_USER ){
 $dellist = explode(",",file_get_contents("old-user.del.list"));
 if( is_array( $list ) ){
  foreach( $list as $key => $val ){ if( $val == $_USER["name"] ){ unset($list[$key]); } }
  $f = fopen("old-user.del.list","w");
  fwrite($f,implode(",",$list));
  fclose($f);
 }
}

if( ($_USER) && ($_USER["status"]!=="active") ){ $msg = "Този потребител временно е отстранен."; unset($_USER); }

?>