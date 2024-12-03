<?php

/* DATABASE CONNECTION */

try {
    $db = new PDO('mysql:host=localhost;dbname=Company;charset=utf8', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (Exception $e) {
    die("Cannot establish a secure connection to the host server at the moment: " . $e->getMessage());
}

/* DATABASE CONNECTION */

if (isset($_POST['submit'])) {

    //-- Get Employee Data --//
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $role = $_POST['role'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        die();
    }

    // Password same or not
    if ($password != $password2) {
        echo "Password doesn't match.";
        die();
    }

    // Check if employee email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Email already exists
        echo "Oops... This email already exists!";
        die();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

    // Insert data into DB
    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = $db->prepare($sql);

    try {
        $stmt->execute([$email, $hashedPassword, $role]);
        header('Location: ../users.php?success');
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
