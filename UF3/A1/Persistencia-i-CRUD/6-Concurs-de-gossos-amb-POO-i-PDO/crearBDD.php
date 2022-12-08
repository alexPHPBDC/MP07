<?php

$mysqlExeRoute = "C:/Program Files/MySQL/MySQL Server 8.0/bin/mysql.exe";
$sqldumpRoute = "C:/xampp/htdocs/dump.sql";

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {//Maquina de casa
    
    system('"'.$mysqlExeRoute.'" --user=root --password=patata < "'.$sqldumpRoute.'"');
} else {//Maquina del insti
    system("mysql --user=alex --password=patata < dump.sql");
}

?>