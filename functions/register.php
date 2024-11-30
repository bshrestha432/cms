

<?php

require_once "../admin/functions/db.php";

// print_r($_SESSION);
// Form processing
// if (isset($_POST['submit'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $captcha = trim($_POST['captcha']);

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password) || empty($captcha)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    // } elseif ($captcha !== $_SESSION['captcha']) {
    //     $error = "Invalid CAPTCHA.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $token = bin2hex(random_bytes(16));

        // Insert into database using PDO
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, token) VALUES (:name, :email, :password, :token)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashed_password,
                ':token'    =>$token,
            ]);
            header('Location:../register.php?sent');
            $success = "User registered successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate email error code
                $error = "Email is already registered.";
            } else {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
// }

// if (isset($_POST['submit'])) {
	
// 	$sql = "INSERT INTO comments(name, comment, post_id)
//     VALUES (?,?,?)";

//     $stmt = $db->prepare($sql);


//     try {
//       $stmt->execute([$name, $comment, $post_id]);
//       header('Location:../blog.php');
//       // echo "DONE!!";

//       }

//      catch (Exception $e) {
//         $e->getMessage();
//         echo "Error";
//     }	

// }







?>