<?

require("init.php");

$u = getuserbyid($_GET["uid"]);

?><body onload="parent.show_win('Sending msg to <? echo $u["name"] ?>',document.body.innerHTML)"><?

 if( ($_POST["msg"]) && ($_USER) ){ 
     send_msg($_USER["name"],$u["name"],$_POST["msg"]);
     echo "The message to ".$u["name"]." was successfully sent.";
   }else{ 
?>

<form method="POST" action="send_msg.php?uid=<? echo $_GET["uid"] ?>" target="conn" id="msg" style="margin:0;padding:0;">
<textarea rows="5" cols="35" class="normal" name="msg"></textarea><br />
</form><br />
<table align="right"><tr><td><span onclick="send('msg')" class="logout_link"><b style="font-size:16"> &raquo; Send </b></a></td></tr></table>

<? } ?></body>