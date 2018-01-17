<?



 if( ($_GET["freezeqid"]) && (($_USER["type"] == "root") || ($_USER["type"] == "coroot")) ){
  mysql_query("update queries set frozen = '1' where qid = '".$_GET["freezeqid"]."'");
 }elseif( ($_GET["unfreezeqid"]) && (($_USER["type"] == "root") || ($_USER["type"] == "coroot")) ){
  mysql_query("update queries set frozen = '0' where qid = '".$_GET["unfreezeqid"]."'");
 }

 if( !$_GET["filter"] ){
  echo "<a href=\"?p=queries&filter=active".(($_GET["pn"])?("&pn=".$_GET["pn"]):"")."\"><b> &nbsp; &raquo; Покажи само активните анкети &nbsp; </b></a><br />"; 
 }else{
  echo "<a href=\"?p=queries".(($_GET["pn"])?("&pn=".$_GET["pn"]):"")."\"><b> &nbsp; &raquo; Покажи всички анкети &nbsp; </b></a><br />"; 
 }

 $r = mysql_query("select COUNT(qid) from queries");
 $row = mysql_fetch_row($r);
 $pages_count = ceil($row[0]/20);
 if( !$_GET["pn"] ){ $pn = $pages_count; }

 if( $pages_count > 1 ){ 
  echo "<span style=\"font-size:10;color:gray;\">pages &raquo; &nbsp; ";
  for($i=1;$i<=$pages_count;$i++){ 
   echo "<a href=\"?p=queries&pn=$i".(($_GET["filter"])?("&filter=".$_GET["filter"]):"")."\" style=\"font-size:10;\">".(($pn==$i)?"<b><u>$i</u></b>":"$i")."</a> &nbsp;/&nbsp; "; 
  }
  echo "</span><br />";
 }

 if( ($_GET["delqid"]) && ( ($_USER["type"]=="root") || ($_USER["type"]=="coroot") ) ){
  mysql_query("delete from `votes` where qid = '".$_GET["delqid"]."'");
  mysql_query("delete from `comments` where qid = '".$_GET["delqid"]."'");
  mysql_query("delete from `queries` where qid = '".$_GET["delqid"]."'");
 }


if($_USER){

 $r = mysql_query("select qid,headline,created,uid,frozen from queries LIMIT ".($pn*20-20).",20");
 if( $_USER["type"] !== "voter" ){ echo "<a href=\"?p=add_q\"><b><u> &raquo; Добави анекта </u></b></a><br />"; }

}else{

 $r = mysql_query("select qid,headline,created,uid,frozen from queries where public > 0 LIMIT ".($pn*20-20).",20");

}

if( $r ){

$arr = mysql_get_array($r);

mysql_free_result($r);

if( is_array($arr) ){
 
 foreach($arr as $key => $val){

  $arr[$key]["status"] = q_active($arr[$key]["qid"]);

  if( ($_USER["type"] == "root") || ($_USER["type"] == "coroot") ){
   $tmp = ($arr[$key]["frozen"]=="1")?"un":"";
   $arr[$key]["action"] = "<a href=\"?p=queries&".$tmp."freezeqid=".$arr[$key]["qid"].(($_GET["pn"])?("&pn=".$_GET["pn"]):"").(($_GET["filter"])?("&filter=".$_GET["filter"]):"")."\" class=\"small\">".$tmp."freeze</a>";
   $arr[$key]["del"] = "<a href=\"?p=queries&delqid=".$arr[$key]["qid"].(($_GET["pn"])?("&pn=".$_GET["pn"]):"").(($_GET["filter"])?("&filter=".$_GET["filter"]):"")."\" class=\"small\">delete</a>";
  }

  unset($arr[$key]["frozen"]); 

  $arr[$key]["created"] = date("d.m.Y",$arr[$key]["created"]);

  $tmpu = getuserbyid($arr[$key]["uid"]);

  $arr[$key]["uid"] = "<a href=\"?p=user&uid=".$tmpu["uid"]."\">".$tmpu["name"]."</a>";
  $arr[$key]["headline"] = "<a href=\"?p=query&qid=".$arr[$key]["qid"]."\">".$arr[$key]["headline"]."</a>";

  if( ($_USER) && (!voted($_USER["uid"],$arr[$key]["qid"])) && ($arr[$key]["status"]=="active") ){ 
   $arr[$key]["headline"] = "<b>".$arr[$key]["headline"]."</b>"; 
  }
 }

 if( $_GET["filter"] ){
  foreach($arr as $key => $val){
   if( $arr[$key]["status"]!==$_GET["filter"] ){
    unset($arr[$key]);
   }
  }
 }

}

echo "<br />\r\n";
echo build_table($arr,"Текущи анкети в системата (page $pn/$pages_count)");

}else{
 echo "<br />\r\n";
 echo "<span class=\"err\">Няма пуснати анкети ".$_GET["filter"]." на тази страница</span>";
}

?>