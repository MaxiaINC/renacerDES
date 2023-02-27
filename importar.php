<?php








 

//ENTER THE RELEVANT INFO BELOW
$mysqlDatabaseName ='senadis2';
$mysqlUserName ='root';
$mysqlPassword ='';
$mysqlHostName ='localhost';
$mysqlImportFilename ='respaldos\senadis2022-04-14.sql';
echo "PASÓ 1";
//DONT EDIT BELOW THIS LINE
//Export the database and output the status to the page
$command='C:/wamp64/bin/mysql/mysql5.7.36/bin/mysql -h' .$mysqlHostName .' -u' .$mysqlUserName .' ' .$mysqlDatabaseName .' < ' .$mysqlImportFilename;
echo "PASÓ 2";
exec($command,$output,$worked);
echo "PASÓ 3";
switch($worked){
    case 0:
        echo 'Archivo <b>' .$mysqlImportFilename .'</b> importado satisfactoriamente a la base de datos <b>' .$mysqlDatabaseName .'</b>';
        break;
    case 1:
        echo 'Se ha producido un error de exportación, compruebe la siguiente información:<br/><br/><table><tr><td>Nombre de la base de datos:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>MySQL Host Name:</td><td><b>' .$mysqlHostName .'</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$mysqlImportFilename .'</b></td></tr></table>';
        break;
} 
?>