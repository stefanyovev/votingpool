<?



if( ( $_USER ) && ( $_USER["type"] !== "voter" ) ){

 if( ( $_POST ) && ( $_POST["action"] == "add_q" ) ){

  $ea = false;
  if( is_array($_POST["answers"]) ){
   foreach( $_POST["answers"] as $key => $val){
    if( strlen($val)<2 ){ unset($_POST["answers"][$key]); }
   }
   if( count($_POST["answers"])<2 ){ $ea = true; }
  }else{ $ea = true; }

print_r($_POST["answers"]); echo ($ea)?"EA":"FA";

  if( 
  ( strlen($_POST["headline"])>5 ) && 
  ( ereg("^[0-9]+$",$_POST["ttl"]) ) && 
  ( ereg("^[01]$",$_POST["public"]) ) && 
  ( ereg("^[0-9]+$",$_POST["quota"]) ) && 
  ( strlen($_POST["question"])>10 ) && (!$ea) ){

   add_q($_USER["uid"],$_POST["ttl"],$_POST["public"],$_POST["quota"],str_replace("<","&lt;",$_POST["headline"]),str_replace("<","&lt;",$_POST["question"]),str_replace("<","&lt;",implode(";",$_POST["answers"])));
   redirect("?p=queries");

  }else{

   echo "<span class=\"err\">���������� ���������� � ���������.<br /> ���� ��������� �������������.</span><br /><br />\r\n";
   put_form();

  }
 
 }else{
 
  put_form();
 
 }

}else{

 redirect("?p=queries");

}




// --------------------------------------------------------------

function put_form(){ ?>

<table cellspacing="10" style="font-family:tahoma,arial;font-size:12;"><form method="POST" id="add_q"><input type="hidden" name="action" value="add_q">

 <tr>
  <td align="right" width="50%" valign="top"><b>��������</b><br><span class="small">������ ���������� �����<br />min 5 ,max 50 symbols</span></td>
  <td valign="top"><input type="text" name="headline" value="<? echo $_POST["headline"]; ?>"></td>
 </tr>

 <tr>
  <td align="right" valign="top"><b>�����</b><br><span class="small">������� ����� �������� �� ���� �������</span></td>
  <td valign="top"><select name="ttl">
   <option value="0" <? echo ($_POST["ttl"]=="0")?"selected=\"selected\"":""; ?> > - ��� ����� - </option>
   <option value="86400" <? echo ($_POST["ttl"]=="86400")?"selected=\"selected\"":""; ?> >1 ���</option>
   <option value="172800" <? echo ($_POST["ttl"]=="172800")?"selected=\"selected\"":""; ?> >2 ����</option>
   <option value="259200" <? echo ($_POST["ttl"]=="259200")?"selected=\"selected\"":""; ?> >3 ����</option>
   <option value="345600" <? echo ($_POST["ttl"]=="345600")?"selected=\"selected\"":""; ?> >4 ����</option>
   <option value="432000" <? echo ($_POST["ttl"]=="432000")?"selected=\"selected\"":""; ?> >5 ����</option>
   <option value="518400" <? echo ($_POST["ttl"]=="518400")?"selected=\"selected\"":""; ?> >6 ����</option>
   <option value="604800" <? echo ($_POST["ttl"]=="604800")?"selected=\"selected\"":""; ?> >1 �������</option>
   <option value="1209600" <? echo ($_POST["ttl"]=="1209600")?"selected=\"selected\"":""; ?> >2 �������</option>
   <option value="1814400" <? echo ($_POST["ttl"]=="1814400")?"selected=\"selected\"":""; ?> >3 �������</option>
   <option value="2419200" <? echo ($_POST["ttl"]=="2419200")?"selected=\"selected\"":""; ?> >1 �����</option>
  </select></td>
 </tr>
 
 <tr>
  <td align="right" valign="top"><b>��������</b><br><span class="small">���� �� �� ����� �� �������������� �����������</span></td>
  <td valign="top"><select name="public">
   <option value="1" <? echo ($_POST["public"]=="1")?"selected=\"selected\"":""; ?> > �� </option>
   <option value="0" <? echo ($_POST["public"]=="0")?"selected=\"selected\"":""; ?> > �� </option>
  </select></td>
 </tr>

 <tr>
  <td align="right" valign="top"><b>����� �������</b><br><span class="small">�������� ������ ���� ����������� �� ���� �����<br /> ��� � 0 - ���� �����<br />� ������� ��������� ��� <b><? echo count_users(); ?></b> �����������</span></td>
  <td valign="top"><input type="text" name="quota" value="<? echo ($_POST["quota"])?$_POST["quota"]:"0"; ?>"></td>
 </tr>

 <tr>
  <td align="right" valign="top"><b>���������</b><br><span class="small"><b>feel free to speak</b><br />���� � ������� �� ������� �������� ������� �� <br /> �������� � ������ ������ ��� �����<br /> �������� ����� � ���������<br />min 10 symbols</span></td>
  <td valign="top"><textarea rows="5" cols="30" name="question"><? echo $_POST["question"]; ?></textarea></td>
 </tr>

 <tr>
  <td align="right" valign="top"><b>��������</b><br><span class="small">���������� �������� �� ����� ����������� ���� �� �������<br />���� ����� ����� _ � -</span></td>
  <td valign="top"><input type="text" name="answers[0]" value="<? echo $_POST["answers"][0]; ?>"><br /><input type="text" name="answers[1]" value="<? echo $_POST["answers"][1]; ?>"><br /><input type="text" name="answers[2]" value="<? echo $_POST["answers"][2]; ?>"></td>
 </tr>

 <tr>
  <td align="left"><a href="?p=queries" class="logout_link"><b style="font-size:16;"> &laquo; Back </b></a></td>
  <td align="right"><span class="logout_link" onclick="send('add_q')"><b style="font-size:16;"> &raquo; Post </b></span></td>
 </tr>

</form></table>

<? } ?>