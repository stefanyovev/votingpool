<?

if( ($_USER) && ($_POST) ){
 if( (ereg("^[a-zA-Zа-яА-я ]{3,50}$",$_POST["realname"])) && (ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$_POST["mail"])) && (strlen($_POST["mail"])<51) ){
  updateuser($_USER["uid"],"realname",$_POST["realname"]);
  updateuser($_USER["uid"],"mail",$_POST["mail"]);
  updateuser($_USER["uid"],"info",$_POST["info"]);
  $msg = "Профилът беше обновен.";
 }else{
  $msg = "Информацията е невалидна. Моля спазваите ограниченията.";
 }
 echo "<span class=\"err\">$msg</span><br />\r\n";
}

if( $_USER ){ ?>

<table align="center" cellspacing="10"  class="normal"> <form method="POST" id="newprofile">
<tr><td align="right"><b>Real Name</b><br /><span class="small">min 3, max 50 letters</span></td><td><input type="text" name="realname" value="<? echo ($_USER["realname"])?$_USER["realname"]:$_POST["realname"]; ?>" /></td></tr>
<tr><td align="right"><b>e-Mail</b><br /><span class="small">min 6, max 50 symbols<br />must be valid mail</span></td><td><input type="text" name="mail" value="<? echo ($_USER["mail"])?$_USER["mail"]:$_POST["mail"]; ?>" /></td></tr>
<tr><td align="right"><b>Info</b><br /><span class="small">Here is the place to descirbe yourself</span></td><td><textarea name="info" rows="5" cols="30"><? echo ($_USER["info"])?$_USER["info"]:$_POST["info"]; ?></textarea></td></tr>
<tr><td align="right" colspan="2"><span class="logout_link" style="font-size:14;font-weight:bold;" onclick="send('newprofile')"> &raquo; Change </span></td></tr>
</form> </table>

<? }else{

 redirect("?p=queries");
 
}

?>