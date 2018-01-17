<?

$q_u  = "create table users (";
$q_u .= "uid int(4) unique primary key auto_increment,";
$q_u .= "puid int(4),";
$q_u .= "created int(4),";
$q_u .= "lastaccess int(4),";
$q_u .= "name varchar(30) unique,";
$q_u .= "pass varchar(30),";
$q_u .= "type varchar(10),";
$q_u .= "status varchar(10),";
$q_u .= "mail varchar(50),";
$q_u .= "realname varchar(50),";
$q_u .= "info text";
$q_u .= ")";

$q_q  = "create table queries (";
$q_q .= "qid int(4) unique primary key auto_increment,";
$q_q .= "uid int(4),";
$q_q .= "created int(4),";
$q_q .= "ttl int(4),";
$q_q .= "public int(1),";
$q_q .= "quota int(4),";
$q_q .= "headline varchar(50),";
$q_q .= "question text,";
$q_q .= "answers text,";
$q_q .= "frozen int(1)";
$q_q .= ")";

$q_v  = "create table votes (";
$q_v .= "vid int(4) unique primary key auto_increment,";
$q_v .= "uid int(4),";
$q_v .= "qid int(4),";
$q_v .= "created int(4),";
$q_v .= "answer int(1)";
$q_v .= ")";

$q_c  = "create table comments (";
$q_c .= "cid int(4) unique primary key auto_increment,";
$q_c .= "uid int(4),";
$q_c .= "qid int(4),";
$q_c .= "created int(4),";
$q_c .= "comment text";
$q_c .= ")";

$q_s = "create table suggestions (";
$q_s .= "sid int(4) unique primary key auto_increment,";
$q_s .= "user varchar(30),";
$q_s .= "created int(4),";
$q_s .= "suggestion text";
$q_s .= ")";

$q_m = "create table `msgs` (";
$q_m .= "`mid` int(4) unique primary key auto_increment,";
$q_m .= "`created` int(4),";
$q_m .= "`from` varchar(30),";
$q_m .= "`to` varchar(30),";
$q_m .= "`read` int(1),";
$q_m .= "`msg` text,";
$q_m .= "`referer` text";
$q_m .= ")";

$q_l = "create table `log` (";
$q_l .= "`id` int(4) unique primary key auto_increment,";
$q_l .= "`ip` varchar(16),";
$q_l .= "`user` varchar(30),";
$q_l .= "`time` int(4),";
$q_l .= "`user_agent` text,";
$q_l .= "`r1` text,";
$q_l .= "`r2` text,";
$q_l .= "`r3` text,";
$q_l .= "`referer` text";
$q_l .= ")";

function create_u(){
  global $link,$q_u;
 if( mysql_query($q_u,$link) ){
  echo "Създадох таблица 'users'.<br>";
  mysql_query("insert into users (puid,created,name,pass,type,status,info) values ('1','".time()."','root','superuser','root','active','This is the SuperUser !')",$link);
  echo "<b>Добавих потребитял 'root' с парола 'superuser'.</b><br>";
 }else{
  echo "Грешка: Неможах да създам таблица 'users' !!!<br>".mysql_error($link)."<br>";
 }
}

function create_q(){
  global $link,$q_q;
 if( mysql_query($q_q,$link) ){
  echo "Създадох таблица 'queries'.<br>";
 }else{
  echo "Грешка: Неможах да създам таблица 'queries' !!!<br>".mysql_error($link)."<br>";
 }
}

function create_v(){
  global $link,$q_v;
 if( mysql_query($q_v,$link) ){
  echo "Създадох таблица 'votes'.<br>";
 }else{
  echo "Грешка: Неможах да създам таблица 'votes' !!!<br>".mysql_error($link)."<br>";
 }
}

function create_c(){
  global $link,$q_c;
 if( mysql_query($q_c,$link) ){
  echo "Създадох таблица 'comments'.<br>";
 }else{
  echo "Грешка: Неможах да създам таблица 'comments' !!!<br>".mysql_error($link)."<br>";
 }
}

function create_s(){
  global $link,$q_s;
 if( mysql_query($q_s,$link) ){
  echo "Създадох таблица 'suggestions'.<br>";
 }else{
  echo "Грешка: Неможах да създам таблица 'suggestions' !!!<br>".mysql_error($link)."<br>";
 }
}

function create_m(){
  global $link,$q_m;
 if( mysql_query($q_m,$link) ){
  echo "Създадох таблица 'msgs'.<br>";
 }else{
  echo "Грешка: Неможах да създам таблица 'msgs' !!!<br>".mysql_error($link)."<br>";
 }
}

function create_l(){
  global $link,$q_l;
 if( mysql_query($q_l,$link) ){
  echo "Създадох таблица 'log'.<br>";
 }else{
  echo "Грешка: Неможах да създам таблица 'log' !!!<br>".mysql_error($link)."<br>";
 }
}


?>