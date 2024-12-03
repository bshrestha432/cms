<?php
session_start();

$captcha_code = rand(1000, 9999);
$_SESSION['captcha'] = $captcha_code;

$path = '/admin/functions/db.php';
$data = require_once __DIR__ .$path;
$error = $success = '';

// Form processing
if (isset($_POST['submit'])) {
    echo 'here';
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
    } elseif ($captcha !== $_SESSION['captcha']) {
        $error = "Invalid CAPTCHA.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database using PDO
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashed_password,
            ]);
            
            $success = "User registered successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate email error code
                $error = "Email is already registered.";
            } else {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}

// Generate a new CAPTCHA image
function generateCaptchaImage($captcha_code)
{
// print_r($_SESSION);
    // Create an image
    $image = imagecreatetruecolor(120, 40);

    // Colors
    $bg_color = imagecolorallocate($image, 255, 255, 255); // White background
    $text_color = imagecolorallocate($image, 0, 0, 0); // Black text
    $noise_color = imagecolorallocate($image, 100, 100, 100); // Gray noise

    // Fill background
    imagefilledrectangle($image, 0, 0, 120, 40, $bg_color);

    // Add noise
    for ($i = 0; $i < 100; $i++) {
        imagesetpixel($image, rand(0, 120), rand(0, 40), $noise_color);
    }

    // Add text
    $font = __DIR__ . '/fonts/apercumovistarbold.ttf';
    imagettftext($image, 20, rand(-10, 10), 10, 30, $text_color, $font, $captcha_code);

    // Save image to file
    ob_start();
    imagepng($image);
    $data = ob_get_clean();
    imagedestroy($image);

    return 'data:image/png;base64,' . base64_encode($data);
}

$captcha_image = generateCaptchaImage($captcha_code);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="icon" href="images/icon.png">
<title>Life Loop</title>
<!-- custom-theme -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Coalition Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //custom-theme -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- js -->
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<!-- //js -->
<!-- font-awesome-icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome-icons -->
<link href="//fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- banner -->
	<div class="banner1">
		<div class="container">
			<div class="w3_agile_banner_top">
				<div class="agile_phone_mail">
					<ul>
						<li><i class="fa fa-phone" aria-hidden="true"></i>+(254) 002 100 500</li>
						<li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:info@Companyonline.net">info@example.com</a></li>
					</ul>
				</div>
			</div>
			<div class="agileits_w3layouts_banner_nav">
				<nav class="navbar navbar-default">
					<div class="navbar-header navbar-left">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<h1><a class="navbar-brand" href="index.php"><img src="images/logo.png" style=" width: 400px;height: 250px;"  class="img-responsive"></a></h1>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
						<nav class="cl-effect-13" id="cl-effect-13">
						<ul class="nav navbar-nav">
							<li><a href="index.php">Home</a></li>
							<li><a href="blog.php">Blog</a></li>
							<li><a href="about.php">About</a></li>
							<li><a href="contact.php">Contact</a></li>
							<li  class="active"><a href="admin/login.php">Sign In | Up</a></li>
						</ul>
						
					</nav>

					</div>
				</nav>
			</div>
		</div>
	</div>
<!-- //banner -->
<!-- mail -->
	<div class="mail">
		<div class="container">
			<h2 class="w3l_head w3l_head1">Register with Us</h2>
			<?php
				if (isset($_GET["sent"])) {
					echo 
					'<div class="alert alert-success" >
                          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                         <strong>SENT!! </strong><p> Thank you for registering.</p>
                    </div>'
					;
				}

                // Add a container to show errors
                if (isset($_GET["error"])) {
                    $errorMessages = json_decode($_GET["error"], true);
                    if ($errorMessages && is_array($errorMessages['errors'])) {
                        echo '<div class="alert alert-danger">';
                        echo '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
                        echo '<strong>Error!</strong>';
                        echo '<ul>';
                        foreach ($errorMessages['errors'] as $error) {
                            echo '<li>' . htmlspecialchars($error) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                }
			?>
				<div class="agileits_mail_grids">
				<div class="agileits_mail_grid_left">
					<form action="functions/register.php" method="post">
                        <h4>Name*</h4>
                        <input type="text" id="name" name="name" required>

                        <h4>Email*</h4>
                        <input type="email" id="email" name="email" required>

                        <h4>Password*</h4>
                        <input type="password" id="password" name="password" required>

                        <h4>Confirm Password*</h4>
                        <input type="password" id="confirm_password" name="confirm_password" required>

                        <!-- <div class="captcha"><?= $_SESSION['captcha'] ?></div> -->
                        <div class="captcha-container">
                            <img src="<?= $captcha_image ?>" alt="CAPTCHA">
                            <h4>Enter Captcha: *</h4>
                            <input type="text" id="captcha" name="captcha" required>
                        </div>

                        <input type="submit" name="submit" value="Register">
					</form>
				</div>
			</div>
		</div>
	</div>
<!-- //mail -->
<!-- map -->
	<div class="w3l-map">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2569.9254122544357!2d-97.14398022455238!3d49.90020362683141!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x52ea715dbbfef9f7%3A0xa264c0c30d60f12c!2s160%20Princess%20St%2C%20Winnipeg%2C%20MB%20R3B%201K9!5e0!3m2!1sen!2sca!4v1732640484228!5m2!1sen!2sca" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
	</div>
<!-- map -->

<!-- footer -->
	
	<?php 
		include("footer.php");
	?>

