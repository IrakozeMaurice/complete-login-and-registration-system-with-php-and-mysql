<?php
$user = 'root';
$dsn = 'mysql:host=localhost; dbname=register';
$pass = 'pass';

try{
  //create connection
  $db = new PDO($dsn, $user, $pass);

  // set the PDO error mode to exception
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // echo "Connected to register database";
}catch(PDOException $e){
  echo "Database connection failed." . $e->getMessage();
}

 ?>
