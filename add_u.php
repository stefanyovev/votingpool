<?

$right = true;
if( $_POST["type"] == "coroot" ){
 if( $_USER["type"] == "root" ){
  $right = true;
 }else{
  $right = false;
 }
}

if( ( $_USER ) && ( ( $_USER["type"] == "root" ) || ( $_USER["type"] == "coroot" ) ) ){

 if( ( $_POST ) && 
     ( $right ) && 
     ( $_POST["action"] == "add_u" ) && 
     ( $_POST["pass1"] == $_POST["pass2"] ) && 
     ( ereg("^[a-zA-Z0-9_-]{3,30}$",$_POST["pass1"]) ) && 
     ( ereg("^[a-zA-Z0-9_-]{3,30}$",$_POST["name"]) ) && 
     ( ereg("^[a-zA-Zа-яА-Я ]{3,50}$",$_POST["realname"]) ) && 
     ( ereg($_POST["type"],"coroot poster voter") && 
     ( strlen($_POST["mail"])<51 ) && 
     ( ereg("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$_POST["mail"]) ) )){

  if( !getuserbyname($_POST["name"]) ){
   add_u($_USER["uid"],$_POST["name"],$_POST["pass1"],$_POST["type"],"active",$_POST["mail"],$_POST["realname"]);
   send_msg($_USER["name"],$_POST["name"],file_get_contents("u/new-user.msg.txt"));
   redirect("?p=users");
  }else{
   echo "<span class=\"err\">Този потребител вече съществува.</span><br /><br />\r\n";
   put_form();
  }
 
 }else{

  if( $_POST ){
   echo "<span class=\"err\">Posted information is invalid.<br /> Please read the restrictions and repair your mistakes.</span><br /><br />\r\n";
  }

 put_form();

}

}else{

 redirect("?p=users");

}

function put_form(){
  global $_USER;

?>

<table cellspacing="10" style="font-family:tahoma,arial;font-size:12;"><form method="POST" id="add_u"><input type="hidden" name="action" value="add_u" />

 <tr>
  <td align="right" width="50%"><b>Потребителско име</b><br /><span class="small">a-z A-Z 0-9 _ -<br />Max 30 symbols, min 3</span></td>
  <td><input type="text" name="name" value="<? echo $_POST["name"]; ?>" /></td>
 </tr>

 <tr>
  <td align="right"><b>Парола</b><br /><span class="small">Max 30 symbols, min 3</span></td>
  <td><input type="password" name="pass1" value="<? echo $_POST["pass1"]; ?>" /></td>
 </tr>

 <tr>
  <td align="right"><b>Парола</b><br /><span class="small">отново</span></td>
  <td><input type="password" name="pass2" value="<? echo $_POST["pass2"]; ?>" /></td>
 </tr>

 <tr>
  <td align="right"><b>Тип</b><br /><span class="small">правата на потребителя зависят от неговия тип</span></td>
  <td><select name="type">
   <? if( $_USER["type"] == "root" ){ ?>
   <option value="coroot" <? echo ($_POST["type"]=="coroot")?"selected=\"selected\"":""; ?> >coroot</option>
   <? } ?>
   <option value="poster" <? echo ($_POST["type"]=="poster")?"selected=\"selected\"":""; ?> >poster</option>
   <option value="voter" <? echo ($_POST["type"]=="voter")?"selected=\"selected\"":""; ?> >voter</option>
  </select></td>
 </tr> 

 <tr>
  <td align="right"><b>Истринско име</b><br /><span class="small">само букви<br />max 50 symbols, min 6</span></td>
  <td><input type="text" name="realname" value="<? echo $_POST["realname"]; ?>" /></td>
 </tr>

 <tr>
  <td align="right"><b>e-Mail</b><br /><span class="small">max 50 symbols, min 3<br /></span></td>
  <td><input type="text" name="mail" value="<? echo $_POST["mail"]; ?>" /></td>
 </tr>

 <tr>
  <td align="left"><a href="?p=users" class="logout_link"><b style="font-size:16;"> &laquo; Back </b></a></td>
  <td align="right"><span class="logout_link" onclick="send('add_u')"><b style="font-size:16"> &raquo; Regsiter </b></span></td>
 </tr>

</form></table>

<? } ?>