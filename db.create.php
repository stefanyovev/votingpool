<? header("Content-type: text/html; charset=windows-1251");

require("lib.php");
require("sql.php");

$s = get_settings();

if( $_POST ){ 
 foreach( $_POST as $key => $val ){
  $s[$key] = $val;
 }
 save_settings($s);
}



?>

<body>
<a href="index.php?p=intro"> &laquo; Обратно към главната страница</a>
<center>
<h3>MySQL Настройки за Voting Pool</h3>
<span id="err" style="color:red;font-family:tahoma,arial;font-size:12;">&nbsp;</span><br><br>
</center>
<form method="POST">
<table align="center">
 <tr><td align="right">Host</td><td><input type="text" name="dbhost" value="<? echo $s["dbhost"]; ?>" onkeypress="document.getElementById('err').innerHTML='Незапаметени';" /></td></tr>
 <tr><td align="right">Port</td><td><input type="text" name="dbport" value="<? echo $s["dbport"]; ?>" onkeypress="document.getElementById('err').innerHTML='Незапаметени';" /></td></tr>
 <tr><td align="right">User</td><td><input type="text" name="dbuser" value="<? echo $s["dbuser"]; ?>" onkeypress="document.getElementById('err').innerHTML='Незапаметени';" /></td></tr>
 <tr><td align="right">Pass</td><td><input type="text" name="dbpass" value="<? echo $s["dbpass"]; ?>" onkeypress="document.getElementById('err').innerHTML='Незапаметени';" /></td></tr>
 <tr><td align="right">Name</td><td><input type="text" name="dbname" value="<? echo $s["dbname"]; ?>" onkeypress="document.getElementById('err').innerHTML='Незапаметени';" /></td></tr>
 <tr><td colspan="2" align="right"><input type="reset" value="Reset" onclick="document.getElementById('err').innerHTML='&nbsp;';" /> <input type="submit" value="Save" /></td></tr>
</table>
</form>
<br /><hr width="100%" />
<span style="font-family:tahoma,arial;letter-spacing:0.5;font-size:14;color:#400000;">
<? //----------------------------------------------------

if( $link = @mysql_connect($s["dbhost"].":".$s["dbport"],$s["dbuser"],$s["dbpass"]) ){
 echo "Успешно се свързах със сървъра.<br>";

 $db_list = mysql_query("show databases"); $dbexist = false;
 while ($row = mysql_fetch_row($db_list)) { if( $row[0] == strtolower($s["dbname"]) ){ $dbexist = true; } }
 
 if( $dbexist ){

  mysql_select_db($s["dbname"],$link);
  $r = mysql_query("show tables from ".$s["dbname"],$link);
  $uexist = false; $qexist = false; $vexist = false; $cexist = false; $sexist = false; $mexist = false; $lexist = false;
  while ($row = mysql_fetch_row($r)) {
   if(($row[0]=="users")){ $uexist = true; }
   if(($row[0]=="queries")){ $qexist = true; }
   if(($row[0]=="votes")){ $vexist = true; }
   if(($row[0]=="comments")){ $cexist = true; }
   if(($row[0]=="suggestions")){ $sexist = true; }
   if(($row[0]=="msgs")){ $mexist = true; }
   if(($row[0]=="log")){ $lexist = true; }
  }
  if( !$uexist ){ echo "Грешка: Не намерих таблица 'users'<br>"; create_u(); }
  if( !$qexist ){ echo "Грешка: Не намерих таблица 'queries'<br>"; create_q(); }
  if( !$vexist ){ echo "Грешка: Не намерих таблица 'votes'<br>"; create_v(); }
  if( !$cexist ){ echo "Грешка: Не намерих таблица 'comments'<br>"; create_c(); }
  if( !$sexist ){ echo "Грешка: Не намерих таблица 'suggestions'<br>"; create_s(); }
  if( !$mexist ){ echo "Грешка: Не намерих таблица 'msgs'<br>"; create_m(); }
  if( !$lexist ){ echo "Грешка: Не намерих таблица 'log'<br>"; create_l(); }

 }else{
  echo "Грешка: Не намерих моята база от данни. Опитвам да я създам.<br>";
  if(mysql_query("create database ".$s["dbname"],$link)){
   mysql_select_db($s["dbname"],$link);
   echo "Готово. Създавам таблици.<br>";
   create_u();
   create_q();
   create_v();
   create_c();
   create_s();
   create_m();
   create_l();

  }else{
   echo "Грешка: Не можах да създам база от данни !!! <br>".mysql_error($link)."<br>";
  }
 }

}else{
 echo "Грешка: Не можах да се свържа със сървъра. !!!<br>".mysql_error($link)."<br>";
}


//---------------------------------------------------- ?>
</span>
<hr width="100%" />
<center><span id="err" style="font-family:tahoma,arial;font-size:12;">Строго препоръчително е след настройката на MySQL за приложението да смените разширението на този файл така че да не бъде достъпен за изпълнение от света.</span></center>
</body>