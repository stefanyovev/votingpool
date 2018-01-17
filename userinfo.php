<?

require("init.php");

$u = getuserbyid($_GET["uid"]);

?><body onload="parent.show_win('<? echo $u["name"] ?>`s info',document.body.innerHTML)"><?

if( strlen($u["info"])>0 ){
 echo $u["info"];
}else{
 echo "The user have not entered info about himself.";
}

?></body>