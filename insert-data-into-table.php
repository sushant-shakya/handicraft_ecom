<?php
include __DIR__ . '/dbConnectionWithPDO.php';

function clean_input($data) {
  $data = trim($data);               // Remove extra spaces, tabs, newlines
  $data = stripslashes($data);       // Remove backslashes
  $data = htmlspecialchars($data);   // Convert special characters to HTML entities
  return $data;
}


try{
  // Ensure the connection is established
  if (!$pdo) {
    throw new Exception("Database connection is not established.");
}

// Initialize variables and errors
$full_name = $email = $country = $city = $postal_code = $address = $phone = "";
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

if (empty($_POST["address"])) {
  $errors['address'] = "address is required.";
} else {
  $city = clean_input($_POST["address"]);
  if (!preg_match("/^[a-zA-Z,' ]*$/", $address)) {
      $errors['address'] = "Only letters and spaces are allowed.";
  }
}


if (empty($_POST["phone"])) {
  $errors['phone'] = "Phone number is required.";
} else {
  $phone = clean_input($_POST["phone"]);
  if (!preg_match("/^\+977\-9(8|7|6)[0-9]{8}$/", $phone)) {
      $errors['phone'] = "Phone number must be 10 digits.";
  }
}
}
// If no errors, insert into database
if (empty($errors)) {
  try {
      $sql = "INSERT INTO users (full_name, email, country, city, postal_code, address, phone) 
              VALUES (:full_name, :email, :country, :city, :postal_code, :address, :phone)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':full_name', $full_name);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':country', $country);
      $stmt->bindParam(':city', $city);
      $stmt->bindParam(':postal_code', $postal_code);
      $stmt->bindParam(':address', $address);
      $stmt->bindParam(':phone', $phone);

      if ($stmt->execute()) {
          echo "Form submited  successfully!";
      } else {
          echo "Failed to submit form.";
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
  $pdo = null;
}
?>