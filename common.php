<?php
   require_once('config.php');
  
   //connextion phpMyAdmin
   $dsn = "mysql:host=".localHost.";dbname=".dataBase.";charset=UTF8";
   try {
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
      
        $connection=new PDO($dsn,userName,password,$options);
      
        if (!$connection) {
            echo "Error!";
        }
   } catch (PDOException $e) {
        die($e->getMessage());
   }

 

 
   

   

