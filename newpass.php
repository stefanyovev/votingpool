<?

if( ($_USER) && ($_POST) ){
 
 if( $_POST["oldpass"] == $_USER["pass"] ){
  if( ereg("^[a-zA-Z0-9_-]{3,30}$",$_POST["newpass1"]) ){
   if( $_POST["newpass1"] == $_POST["newpass2"] ){
    updateuser($_USER["uid"],"pass",$_POST["newpass1"]);
    $msg = "Password successfully changed.";
   }else{
    $msg = "You typed the new password in two different ways. Try again.";
   }
  }else{
   $msg = "New password is invalid. Please type another whitch matches the restrictions.";
  }
 }else{
  $msg = "woW ! .. Did you forget your current password ?";
 }

 echo "<span class=\"err\">$msg</span><br /><br />";

}


if( $_USER ){ ?>

<table align="center" cellspacing="10"  class="normal"> <form method="POST" id="newpass">
<tr><td align="right"><b>Old Password</b></td><td><input type="text" name="oldpass" /></td></tr>
<tr><td align="right"><b>New Password</b><br /><span class="small">min 3, max 30 symbols<br />letters, numbers, _ and -</span></td><td><input type="password" name="newpass1" /></td></tr>
<tr><td align="right"><b>New Password</b><br />again</td><td><input type="password" name="newpass2" /></td></tr>
<tr><td align="right" colspan="2"><span class="logout_link" style="font-size:14;font-weight:bold;" onclick="send('newpass')"> &raquo; Change </span></td></tr>
</form> </table>

<? }else{

 redirect("?p=queries"); 

}

?>