<?php
$servername = "localhost:3306";
$username = "root";
$password = "11111111";
$dbname = "handicraftdb";

try{
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>