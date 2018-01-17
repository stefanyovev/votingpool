<?

if( (($_USER["type"]=="root") || ($_USER["type"]=="coroot")) && $_GET["uid"]){

 $u = getuserbyid($_GET["uid"]);
 if( ($_USER["type"] == "coroot") && (($u["type"]=="coroot")||($u["type"]=="root")) ){
  redirect("?p=users");
 }else{ 

if( $_POST["action"]=="ch_u" ){
 mysql_query("update `users` set `name`='".$_POST["name"]."',`pass`='".$_POST["pass"]."',`type`='".$_POST["type"]."',`status`='".$_POST["status"]."',`mail`='".$_POST["mail"]."',`realname`='".$_POST["realname"]."',`info`='".$_POST["info"]."' where uid = '".$u["uid"]."'");
 $u = getuserbyid($_GET["uid"]);
 echo "<center class=\"err\">Профилът беше обновен.<br /><a href=\"?p=users\"> &laquo; Обратно към потребители</a></center>"; 
}

if( $_GET["del"] == "del" ){

 delete_user($_GET["uid"]);
 redirect("?p=users");
}


?>
<br /><a href="?p=edituser&uid=<? echo $u["uid"]; ?>&del=del"><b><u> &raquo; Изтрий този потребител от системата &laquo; </u></b></a><br /><br />
<table cellspacing="10" style="font-family:tahoma,arial;font-size:12;"><form method="POST" id="ch_u"><input type="hidden" name="action" value="ch_u" />

 <tr>
  <td align="right">Name</td>
  <td align="left"><input type="text" name="name" value="<? echo $u["name"]; ?>"></td>
 </tr>

 <tr>
  <td align="right">Pass</td>
  <td align="left"><input type="text" name="pass" value="<? echo $u["pass"]; ?>"></td>
 </tr>

 <tr>
  <td align="right">Type</td>
  <td align="left"><select name="type"><option value="poster" <? if($u["type"]=="poster"){ echo "selected=\"selected\""; } ?>>poster</option><option value="voter" <? if($u["type"]=="voter"){ echo "selected=\"selected\""; } ?>>voter</option><? if( $_USER["type"]=="root" ){ echo "<option value=\"coroot\" ".(($u["type"]=="poster")?"selected=\"selected\"":"").">coroot</option>"; } ?></select></td>
 </tr>

 <tr>
  <td align="right">Status</td>
  <td align="left"><select name="status"><option value="active" <? if($u["status"]=="active"){ echo "selected=\"selected\""; } ?>>active</option><option value="distabled" <? if($u["status"]=="disabled"){ echo "selected=\"selected\""; } ?>>disbled</option></select></td>
 </tr>

 <tr>
  <td align="right">Mail</td>
  <td align="left"><input type="text" name="mail" value="<? echo $u["mail"]; ?>"></td>
 </tr>

 <tr>
  <td align="right">Realname</td>
  <td align="left"><input type="text" name="realname" value="<? echo $u["realname"]; ?>"></td>
 </tr>

 <tr>
  <td align="right">Info</td>
  <td align="left"><textarea name="info"><? echo $u["info"]; ?></textarea></td>
 </tr>

 <tr>
  <td colspan="2" align="right"><span class="logout_link" style="font-size:16;font-weight:bold;" onclick="send('ch_u');">&raquo; Change</span></td>
 </tr>
</table>

<? }

}else{
 redirect("?p=users");
}


?>