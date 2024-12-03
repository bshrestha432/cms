<?php

require_once "../admin/functions/db.php";

// Start the session at the top of your script
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $captcha = trim($_POST['captcha']);
    echo $captcha;

    $errors = [];

    // Validation checks
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    if (empty($confirm_password)) {
        $errors[] = "Confirm password is required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }
    if (empty($captcha)) {
        $errors[] = "CAPTCHA is required.";
    } elseif (!isset($_SESSION['captcha']) || $captcha != $_SESSION['captcha']) {
        $errors[] = "Invalid CAPTCHA.";
    }

    if (!empty($errors)) {
        // Return errors in JSON format
        header('Location: ../register.php?error=' . urlencode(json_encode(["status" => "error", "errors" => $errors])));
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $token = bin2hex(random_bytes(16));

    // Insert into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, token) VALUES (:name, :email, :password, :token)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashed_password,
            ':token' => $token,
        ]);
        header('Location: ../register.php?sent');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate email error code
            $error = "Email is already registered.";
        } else {
            $error = "Registration failed: " . $e->getMessage();
        }
        header('Location: ../register.php?error=' . urlencode(json_encode(["status" => "error", "errors" => $errors])));
  exit;
    }
}

// If the request method is not POST, return an error
http_response_code(405);
header('Location: ../register.php?error=' . urlencode(json_encode(["status" => "error", "errors" => $errors])));
exit;
