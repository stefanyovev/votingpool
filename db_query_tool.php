<? if($_USER["type"]=="root"){ ?><form method="POST" id="query">
<span class="big">Superuser`s Database Query Tool for VP2</span>
<textarea name="query" cols="3" style="width:100%;"></textarea>
<input type="submit" value="Run">
</form><?

if ($_POST) {
 $r = mysql_query($_POST["query"]);
 if( $r ){
  if ( @mysql_num_rows($r) > 0 ){
   echo build_table( mysql_get_array( $r ) ,mysql_affected_rows()." rows in set");
   mysql_free_result($r);
  }else{
   echo "<center class=\"err\">Affected Rows: ".mysql_affected_rows()."</center>";
  }
 }else{
  echo "<center class=\"err\">ERROR: ".mysql_error()."</center>";
 }
}

}else{

 redirect("?p=intro");

}

?>