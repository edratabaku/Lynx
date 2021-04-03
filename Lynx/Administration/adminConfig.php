<?php
//Credentials to access database
define ('db_host','localhost');
define ('db_username','root');
define ('db_password','Naturl!ch4');
define ('db_name','lynx');

//Tentohet lidhja me databazen
$mysqli = mysqli_connect(db_host,db_username,db_password,db_name);

//Kontrolli nese lidhja ka deshtuar
if(!$mysqli){
    die("ERROR: Could not connect. " . mysqli_error($mysqli));
}
?>