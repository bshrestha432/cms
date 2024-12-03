<?php
require_once "../admin/functions/db.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE token = :token AND status = 'unverified'");
    $stmt->execute([':token' => $token]);
    $user = $stmt->fetch();

    if ($user) {
        // Update the user's status to 'active'
        $updateStmt = $pdo->prepare("UPDATE users SET status = 'active', token = NULL WHERE token = :token");
        $updateStmt->execute([':token' => $token]);

        echo "Your email has been verified successfully!";
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
