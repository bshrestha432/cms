
<?php 

// Database Connectuion
require_once "db.php";

try {

    if (isset($_POST['submit'])) {
        // Get the email from the form
        $email = $_POST['email'];

        // Check if the file was uploaded
        if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {
            // File upload details
            $fileTmpPath = $_FILES['imageUpload']['tmp_name'];
            $fileName = $_FILES['imageUpload']['name'];
            $fileSize = $_FILES['imageUpload']['size'];
            $fileType = $_FILES['imageUpload']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            // Allowed file extensions
            $allowedfileExtensions = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExtension, $allowedfileExtensions)) {
                // Generate a new filename to avoid overwriting
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                // Directory to save the file
                $uploadFileDir = '../uploads/';
                $dest_path = $uploadFileDir . $newFileName;

                // Move the file to the upload directory
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Save the image path to the database
                    $imagePath = 'uploads/' . $newFileName;

                    $stmt = $pdo->prepare("UPDATE users SET image_path = :image_path WHERE email = :email");
                    $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
                    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        echo "Profile picture updated successfully!";
                        header("Location: ../profile_picture.php"); // Redirect to the profile page
                        exit;
                    } else {
                        echo "Failed to update the database.";
                    }
                } else {
                    echo "Error moving the file to the upload directory.";
                }
            } else {
                echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
            }
        } else {
            echo "No file uploaded or an error occurred during upload.";
        }
    }
} catch (\PDOException $e) {
    // Handle potential errors
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

?>