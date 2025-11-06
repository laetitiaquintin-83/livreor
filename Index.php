<?php

require 'Config.php';
mysql_connect(DB_SERVER, DB_LOGIN, DB_PASS)c;
mysql_select_db(DB_NAME);
mysql_query('SET NAMES'.DB_ENC);

if($)
    extract($_POST);
$ip=$_SERVER["REMOTE_ADDR"];
$sql="INSERT INTO lo_messages (pseudo,mail,message,ip) VALUES ("
?>



<form method="post" action="index.php">
   Nom : <input type="text" name="pseudo"/><br/>
   Mail :<input type="text" name="email"/><br/>
   Message: <br/>
   <textarea name="message"></textarea><br/>
   <input type="submit" value="envoyer"/>
</form>

<?php
$sql="SELECT*FROM lo messages";
$req=mysql_query($sql) or die('Erreur SQL !<br>'.$sql)
?>