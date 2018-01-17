<?

 $r = mysql_query("select COUNT(uid) from users");
 $row = mysql_fetch_row($r);
 $pages_count = ceil($row[0]/20);
 if( !$_GET["pn"] ){ $pn = $pages_count; }

 if( $pages_count > 1 ){ 
  echo "<span style=\"font-size:10;color:gray;\">pages &raquo; &nbsp; ";
  for($i=1;$i<=$pages_count;$i++){ 
   echo "<a href=\"?p=users&pn=$i\" style=\"font-size:10;\">".(($pn==$i)?"<b><u>$i</u></b>":"$i")."</a> &nbsp;/&nbsp; "; 
  }
  echo "</span><br />";
 }


if( ( $_USER ) && ( ( $_USER["type"] == "root" ) || ( $_USER["type"] == "coroot" ) ) ){
 echo "<a href=\"?p=add_u\"><b><u> &raquo; Добави нов потребител </u></b></a> <br /> <br />";
}

$r = mysql_query("select uid,name,realname,mail,type,status,created,lastaccess,puid from `users` order by `created` LIMIT ".($pn*20-20).",20");
$arr = mysql_get_array($r);
mysql_free_result($r);

if( is_array($arr) ){
foreach($arr as $key => $val){

 $tmpu = getuserbyid($arr[$key]["puid"]);
 $arr[$key]["puid"] = $tmpu["name"];

 $tmpu = getuserbyid($arr[$key]["uid"]);
 $arr[$key]["name"] = "<a href=\"userinfo.php?uid=".$tmpu["uid"]."\" target=\"conn\">".$tmpu["name"]."</a>";
 if( $_USER ){
  $arr[$key]["action"] = "<a href=\"send_msg.php?uid=".$tmpu["uid"]."\" target=\"conn\" class=\"small\" style=\"font-size:10;\">send msg</a>";
  if( ($_USER["type"]=="root") || (($_USER["type"]=="coroot")&&($arr[$key]["type"]!=="root")&&($arr[$key]["type"]!=="coroot")) ){
   $arr[$key]["edit"] = "<a href=\"?p=edituser&uid=".$arr[$key]["uid"]."\" class=\"small\">edit</a>";
  }elseif( ($_USER["type"] == "root") || ($_USER["type"] == "coroot")){
   $arr[$key]["edit"] = "&nbsp;";
  }
 }else{
  unset($arr[$key]["mail"]);
 }
 unset($arr[$key]["uid"]);
 if( $arr[$key]["created"] == $arr[$key]["lastaccess"] ){
  $arr[$key]["lastaccess"] = "never";
 }else{
  $arr[$key]["lastaccess"] = date("d.m.Y",$arr[$key]["lastaccess"]);
 }
 $arr[$key]["created"] = date("d.m.Y",$arr[$key]["created"]);

}
}

echo build_table($arr,"Текущо регистрирани потребители");

?>