<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_sc = "localhost";
$database_sc = "cmg58891_a";
$username_sc = "cmg58891_a";
$password_sc = "cmg911com";
$sc = mysql_pconnect($hostname_sc, $username_sc, $password_sc) or trigger_error(mysql_error(),E_USER_ERROR); 
$pdo_cmg = new PDO("mysql:host=localhost;dbname=cmg58891_a;charset=utf8","cmg58891_a","cmg911com");
?>