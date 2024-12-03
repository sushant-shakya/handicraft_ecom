<?php
include 'dbConnection.php';
include 'create-user-info-table.php';

try{
  // Ensure the connection is established
  if (!$conn) {
    throw new Exception("Database connection is not established.");
}

// Initialize variables and errors
$full_name = $email = $phone_number = "";
$errors = [];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  // Form validation
  if (empty($_POST["full_name"])) {
    $errors['full_name'] = "Full name is required.";
} else {
    $full_name = clean_input($_POST["full_name"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $full_name)) {
        $errors['full_name'] = "Only letters and spaces are allowed.";
    }
}
if (empty($_POST["email"])) {
  $errors['email'] = "Email is required.";
} else {
  $email = clean_input($_POST["email"]);
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors['email'] = "Invalid email format.";
  }
}
if (empty($_POST["country"])) {
  $errors['country'] = "country is required.";
} else {
  $country = clean_input($_POST["country"]);
  if (!preg_match("/^[a-zA-Z-' ]*$/", $country)) {
      $errors['country'] = "Only letters and spaces are allowed.";
  }
}
if (empty($_POST["city"])) {
  $errors['city'] = "city is required.";
} else {
  $city = clean_input($_POST["city"]);
  if (!preg_match("/^[a-zA-Z-' ]*$/", $city)) {
      $errors['city'] = "Only letters and spaces are allowed.";
  }
}
if (empty($_POST["postal_code"])) {
  $errors['postal_code'] = "postal_code is required.";
} else {
  $postal_code = clean_input($_POST["postal_code"]);
  if (!preg_match("/^[0-9]{5}$/", $postal_code)) {
      $errors['postal_code'] = "Postal code must be exactly 5 digits.";
  }
}

if (empty($_POST["phone_number"])) {
  $errors['phone_number'] = "Phone number is required.";
} else {
  $phone_number = clean_input($_POST["phone_number"]);
  if (!preg_match("/^\+977\-9(8|7|6)[0-9]{8}$/", $phone_number)) {
      $errors['phone_number'] = "Phone number must be 10 digits.";
  }
}
}
// If no errors, insert into database
if (empty($errors)) {
  try {
      $sql = "INSERT INTO users (full_name, email, country, city, postal_code, address, phone_number ) 
              VALUES (:full_name, :email, :country, :city, :postal_code, :address, :phone_number)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':full_name', $full_name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':country', $country);
      $stmt->bindParam(':city', $city);
      $stmt->bindParam(':postal_code', $postal_code);
      $stmt->bindParam(':address', $address);
      $stmt->bindParam(':phone_number', $phone_number);

      if ($stmt->execute()) {
          echo "Form inserted successfully!";
      } else {
          echo "Failed to insert data.";
      }
  } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
  }
}

}catch(PDOException $e){
  echo "PDO ERROR: " . $e->getMessage();
}catch(Exception $e){
  echo "ERROR: " . $e->getMessage();
}finally{
  // Close the database connection
  $conn = null;
}
?>