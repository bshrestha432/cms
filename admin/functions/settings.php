

<?php 

// Database Connectuion
require_once "db.php";

// UPDATE PASSWORD

	
	if (isset($_POST['submit'])) {

		$password = password_hash($_POST['password'],PASSWORD_BCRYPT);

		$email = $_POST["email"];

			$sql = "UPDATE users SET password = :password WHERE email = :email ";

	$stmt = $db->prepare($sql);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    try {
      $result = $stmt->execute();
    
      header('Location:../index.php?set');

    }
    catch (Exception $e) {
        $e->getMessage();
        echo "Error";
    }

}


// UPDATE PASSWORD


?>