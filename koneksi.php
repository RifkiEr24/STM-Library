<?php
 $dbHost='localhost';
 $dbName='perpus';
 $dbUsername="root";
 $dbPassword="";

 try{
     $dbConn=new PDO("mysql:host=$dbHost;dbname=$dbName",$dbUsername,$dbPassword);
     //setting error mode as exception
     $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }catch(PDOException $e){
     echo $e->getMessage();
 }