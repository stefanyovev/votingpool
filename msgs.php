<?

if( $_USER ){

 if( $_GET["delmid"] ){ mysql_query("delete from `msgs` where `mid` = '".$_GET["delmid"]."'"); }

 $m = get_u_msgs($_USER["name"]);

 echo "<table class=\"t\" width=\"70%\">\r\n";
 echo "<tr><td class=\"t_h\">Your Personal Messages</td></tr>";
 if(is_array($m)){
  foreach($m as $msg){
   if( ($msg["from"]!=="-system-") && ($msg["read"]!=="1") ){ send_msg("- system -",$msg["from"],$_USER["name"]." have read you message at ".date("d.m.Y H:i")); }
   if($msg["read"]=="0"){ mysql_query("update msgs set `read` = '1' where `mid` = '".$msg["mid"]."'"); }
   echo mysql_error($link);
    echo "<tr><td class=\"t_d\" style=\"padding:5;\"><b>From</b>: ";
    echo $msg["from"];
    echo "<br /><b>Date</b>: ";
    echo date("d.m.Y H:i",$msg["created"]);
    echo "<br />";
    echo $msg["msg"];
    echo "<br />";
    echo "<table align=\"right\"><tr><td><a href=\"?p=msgs&delmid=".$msg["mid"]."\">delete this msg</a></td></tr></table>";
    echo "</td></tr>\r\n";
  }
 }else{
  echo "<tr><td class=\"t_d\" align=\"center\"> - Empty - </td></tr>";
 }
 echo "</table>\r\n";

}else{

 redirect("?p=intro");

}

?>