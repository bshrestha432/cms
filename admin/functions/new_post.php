<?php
session_start();
require_once "db.php"; // Adjust the path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'], $_POST['content'])) {
        $title = htmlspecialchars($_POST['title']);
        $content = htmlspecialchars($_POST['content']);
        $author = isset($_POST['author']) ? htmlspecialchars($_POST['author']) : 'Anonymous';

        $stmt = $pdo->prepare("INSERT INTO posts (author, title, content) VALUES (:author, :title, :content)");
        $stmt->bindParam(':author', $author);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);

        if ($stmt->execute()) {
            header("Location: ../posts.php?success=1");
        } else {
            echo "Error: Unable to insert post.";
        }
    } else {
        echo "Error: Missing required fields.";
    }
} else {
    header("Location: ../new-post.php");
}
?>
