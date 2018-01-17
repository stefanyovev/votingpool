<?

$can_del = (($_USER["type"]=="root")||($_USER["type"]=="coroot"))?true:false;

if( $can_del && $_GET["delsid"] ){
 mysql_query("delete from `suggestions` where `sid` = '".$_GET["delsid"]."'");
}

if( $_POST["suggestion"] ){
 $u = ($_USER)?$_USER["name"]:"guest";
 mysql_query("insert into suggestions (`user`,`created`,`suggestion`) values ('$u','".time()."','".str_replace("<","",$_POST["suggestion"])."')");
 unset($_GET["pn"]);
}

$r = mysql_query("select COUNT(sid) from suggestions");
$row = mysql_fetch_row($r);
$pages_count = ceil($row[0]/20);
if( !$_GET["pn"] ){ $pn = $pages_count; }

if( $pages_count > 1 ){ 
 echo "<span style=\"font-size:10;color:gray;\">pages &raquo; &nbsp; ";
 for($i=1;$i<=$pages_count;$i++){ 
  echo "<a href=\"?p=suggestions&pn=$i\" style=\"font-size:10;\">".(($pn==$i)?"<b><u>$i</u></b>":"$i")."</a> &nbsp;/&nbsp; "; 
 }
 echo "</span><br /><br />";
}

$r = mysql_query("select * from suggestions order by `created` LIMIT ".($pn*20-20).",20");

if( $r ){
 $c = mysql_get_array($r);
 mysql_free_result($r);
 foreach( $c as $key => $val ){
  $c[$key]["created"] = date("d.m.Y",$c[$key]["created"]);
 }
}

$t = "<table width=\"100%\" class=\"t\">\r\n";
$t .= "<tr><td class=\"t_h\" colspan=\"".(($can_del)?4:3)."\">Suggestions ( page $pn/$pages_count )</td></tr>\r\n";
if( $r ){
 $t .= "<tr><td class=\"t_h\" width=\"1%\">User</td><td class=\"t_h\" width=\"1%\">Date</td><td class=\"t_h\">Suggestion</td>".(($can_del)?"<td class=\"t_h\" width=\"1\">action</td>":"")."</tr>\r\n";
 foreach( $c as $key => $val ){
  $t .= "<tr><td class=\"t_d\">".$c[$key]["user"]."</td><td class=\"t_d\">".$c[$key]["created"]."</td><td class=\"t_d\">".$c[$key]["suggestion"]."</td>".(($can_del)?"<td class=\"t_d\"><a class=\"small\" href=\"?p=suggestions&delsid=".$c[$key]["sid"]."\">delete</a></td>":false)."</tr>\r\n";
 }
}else{
 $t .= "<tr><td colspan=\"3\" class=\"t_d\" align=\"center\"><span class=\"normal\">- Няма публикувани предложения -</span></td></tr>\r\n";
}
$t .= "<tr><form method=\"POST\" id=\"sugg\"><td class=\"t_h\" colspan=\"".(($can_del)?4:3)."\"><table width=\"100%\"><tr><td width=\"100%\"><input type=\"text\" name=\"suggestion\" style=\"width:100%;\"></td><td><span class=\"logout_link\" onclick=\"send('sugg')\"><b style=\"font-size:14;\">&nbsp;&raquo;&nbsp;Post&nbsp;</b></span></td></tr></table></td></form></tr>\r\n";
$t .= "</table>";

echo $t;

?>