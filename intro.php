<? 

if( ( !$_USER ) && ( $_POST["loginuser"] ) ){
  echo "<span class=\"err\">".(($msg!=="")?$msg:"Грешна парола / потребител")."</span><br /><br />";
}elseif( $_POST["referer"] ){
 header("Location: ".$_POST["referer"]);
 die("Redirecting to where you came"); 
} 

?>

<table width="100%">

<tr>
<td style="padding:20;">

 <table align="left" class="t"> <form method="POST" id="loginform">

<? 

if( $_POST["referer"] ){
 $ref = $_POST["referer"];
}elseif( $_SERVER["HTTP_REFERER"] ){
 $ref = $_SERVER["HTTP_REFERER"];
}else{
 $tmp = explode("?",$_SERVER["REQUEST_URI"]);
 $ref = "http://".$_SERVER["HTTP_HOST"].$tmp[0]."?p=queries";
}
echo "<input type=\"hidden\" name=\"referer\" value=\"$ref\">\r\n";

?>
  <tr><td colspan="2" class="t_h" style="text-align:left;"> &raquo; Вход </td></tr>
  <tr><td align="right" style="padding-left:5;padding-right:5;" class="t_d">Потребител</td><td  class="t_d"><input size="15" type="text" name="loginuser" id="loginuser"></td></tr>
  <tr><td align="right"  class="t_d">Парола</td><td  class="t_d"><input size="15" type="password" name="loginpass" onkeydown=""></td></tr>
  <tr><td colspan="2" align="right" style="padding:2;"  class="t_d"><input type="submit" value="." style="margin:0;padding:0;border:none;background:none;color:#FDEFB3;"><span class="logout_link" onclick="send('loginform')"><b style="font-size:14;"> &raquo; Влез </b></span> &nbsp; </td></tr>
 </form> </table>
</td>

<td>

<? echo file_get_contents("u/intro.html"); ?>

</td>

</tr>

</table>