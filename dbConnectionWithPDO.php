<?php
$servername = "localhost:3306";
$username = "root";
$password = "11111111";
$dbname = "handicraftdb";

try{
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
  // set the PDO error mode to exception
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // // Run the GRANT statement to give privileges
  // $grantSql = "GRANT CREATE, ALTER, INSERT, SELECT ON $dbname.* TO 'root'@'localhost';";
  // $conn->exec($grantSql);
  // echo "Privileges granted successfully<br>";

  // // Flush privileges to apply changes
  // $conn->exec("FLUSH PRIVILEGES;");
  // echo "Privileges flushed successfully<br>";
  
}catch(PDOException $e){
  echo "Connection failed:". $e->getMessage();

}
// $conn = null;
?>