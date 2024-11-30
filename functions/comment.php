

<?php

require_once "../admin/functions/db.php";

$post_id = $_POST['post_id'];
$name = $_POST['name'];
$comment = $_POST['comment'];

if (isset($_POST['submit'])) {
	
	$sql = "INSERT INTO comments(name, comment, post_id)
    VALUES (?,?,?)";

    $stmt = $db->prepare($sql);


    try {
      $stmt->execute([$name, $comment, $post_id]);
      header('Location:../blog.php');
      // echo "DONE!!";

      }

     catch (Exception $e) {
        $e->getMessage();
        echo "Error";
    }	

}







?>