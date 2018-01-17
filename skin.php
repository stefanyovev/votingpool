<?

if( $_USER["type"] == "root" ){

if( $_POST["skin"] ){
 $s["skin"] = $_POST["skin"];
 save_settings($s);
 header("Refresh: 1; URL=\"?p=intro\""); 
 echo "<br /><span style=\"err\">Please wait ...</span><br />";
 die();
}

?>
<br />
<form method="POST">
 <span class="normal">Skin </span>
 <select name="skin">
<? $d = opendir("skins");
 while( $f = readdir($d) ){
  if( ($f !== ".") && ($f !== "..") ){
   echo "<option value=\"$f\" ".(($f==$s["skin"])?"selected=\"selected\"":"").">$f</option>\r\n";
  }
 }
?>
 </select> <input type="submit" value="Change" />
</form>

<?}else{

 redirect("?p=intro");
 
}

?>