<table class="t">
<form method="POST">
<tr><td colspan="3" class="t_h">����������</td></tr>

<tr><td class="t_d" width="1">�����&nbsp;�</td>
<td class="t_d"><select name="where">
 <option value="queries">������</option>
 <option value="users">�����������</option>
 <option value="comments">���������</option>
 <option value="suggestions">�����������</option> 
</select></td>
<td class="t_d"><input class="radio" checked="checked" type="radio" name="how" value="AND" /> ������ ���� <br /><input class="radio" type="radio" name="how" value="OR" /> ���� ���� �� ������</td>
</tr>
<tr><td class="t_d" width="1" colspan="3"><table width="100%"><tr><td><input style="width:100%;" type="text" name="what" /></td><td width="1%"><input type="submit" value="Search" /></td></tr></table></td></tr>
</form>
</table><br /><?

$sf = Array( "users" => Array( "name","type","status","mail","realname","info" ) ,
"queries" => Array( "headline","question" ) ,
"comments" => Array( "comment" ) ,
"suggestions" => Array( "user","suggestion" ) );

if( $_POST ){

$q = "select `".implode("`,`",$sf[$_POST["where"]])."` from `".$_POST["where"]."` where ";
$what = explode(" ",$_POST["what"]);

foreach( $what as $word ){
 $cond = array();
 foreach( $sf[$_POST["where"]] as $field ){
  $cond[] = "`$field` like '%$word%'";
 }
 $q .= "( ".implode(" OR ",$cond)." ) ".$_POST["how"]." ";
}

$q = substr($q,0,strlen($q)-strlen($_POST["how"])-2);

$r = mysql_query($q);
$t = mysql_get_array($r);
echo mysql_error();
echo build_table($t,"���������� �� ".$_POST["where"]);

}

?>