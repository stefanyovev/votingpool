<?

if( (!$_USER)&&($q["public"]=="0") ){

 redirect("?p=queries");

}else{

if( ($_USER) && ($_POST["comment"]) && (strlen($_POST["comment"])>1) && ($_GET["qid"]) ){
 mysql_query("insert into `comments` (uid,qid,created,comment) values ('".$_USER["uid"]."','".$_GET["qid"]."','".time()."','".$_POST["comment"]."')");
}

$r = mysql_query("select * from queries where qid = '".$_GET["qid"]."'");
$q = mysql_get_array($r);
mysql_free_result($r);

$q = $q[0];
$q["active"] = q_active( $q["qid"] );
$tmpu = getuserbyid($q["uid"]);
$q["uid"] = $tmpu["name"];

if( ($_USER) && ($_POST["vote"]=="true") && ($q["active"]) && (!voted($_USER["uid"],$q["qid"])) ){
 mysql_query("insert into votes (uid,qid,created,answer) values ('".$_USER["uid"]."','".$q["qid"]."','".time()."','".$_POST["answer"]."')");
}

?>
<table width="70%">
<tr><td align="center" class="big"><? echo $q["headline"]; if($q["active"]!=="active"){ echo "<br /><span style=\"color:#804040\">- ".$q["active"]." -</span>"; } ?></td></tr>
<tr><td align="center" class="small">Posted by <? echo $q["uid"]; ?> at <? echo date("d.m.Y",$q["created"]); ?>. Have <? $qv = get_q_votes($q["qid"]); echo $qv; ?> voted users and <? echo count_users()-$qv; ?> abstainers. </td></tr>
<tr><td align="center" class="normal" style="padding:10;"><b><? echo $q["question"]; ?></b></td></tr>
<tr><td align="center" style="padding:10;"><? echo build_votes($q); ?></td></tr>
<tr><td align="center" style="padding:10;"><? echo build_comments($q); ?></td></tr>
</table>
<?

}

?>