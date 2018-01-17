<?

require("init.php");
echo time()-2678400;
$r = mysql_query("select `name`,`mail` from `users` where `lastaccess` < ".(time()-2678400));

$list = explode(",",file_get_contents("old-user.del.list"));
while( $row = mysql_fetch_row($r) ){
 if( insidearr($list,$row[0]) ){
  
  echo $row[0]." was deleted from system";
  foreach( $list as $key => $val ){ if( $val == $row[0] ){ unset($list[$key]); } }
  $f = fopen("old-user.del.list","w");
  fwrite($f,implode(",",$list));
  fclose($f);
  $u = getuserbyname($row[0]);
  delete_user($u["uid"]);
  mail($row[1],"Your account was deleted",file_get_contents("u/deleted-user.mail.txt"));
 
 }else{

  echo $row[0]." added to del list";
  $list[] = $row[0];
  $f = fopen("old-user.del.list","w");
  fwrite($f,implode(",",$list));
  fclose($f);
  mail($row[1],"Your account is going to be deleted",file_get_contents("u/old-user.mail.txt"));

 }
}

?>