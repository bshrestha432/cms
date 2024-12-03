<?php 
    ob_start();
    require_once "functions/db.php";

    // Initialize the session
    session_start();

    // If session variable is not set, redirect to the login page
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header("location: login.php");
        exit;
    }

    $email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/icon.png">
    <title>Life Loop</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
    <!-- Wizard CSS -->
    <link href="../plugins/bower_components/jquery-wizard-master/css/wizard.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/blue.css" id="theme" rel="stylesheet">
</head>

<body>
    <!-- Preloader -->
    <!-- <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div> -->
    <div id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> 
                <a class="navbar-toggle hidden-sm hidden-md hidden-lg" href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse">
                    <i class="ti-menu"></i>
                </a>
                <div class="top-left-part">
                    <a class="logo" href="index.php">
                        <b><img src="../plugins/images/icon.png" style="width: 30px; height: 30px;" alt="home" /></b>
                        <span class="hidden-xs"><b>LifeLoop</b></span>
                    </a>
                </div>
                <ul class="nav navbar-top-links navbar-left hidden-xs">
                    <li>
                        <form role="search" class="app-search hidden-xs">
                            <input type="text" placeholder="Search..." class="form-control"> 
                            <a href=""><i class="fa fa-search"></i></a> 
                        </form>
                    </li>
                </ul>
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li class="right-side-toggle"> 
                        <a class="waves-effect waves-light" href="javascript:void(0)">
                            <i class="ti-settings"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">
                    <li class="sidebar-search hidden-sm hidden-md hidden-lg">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..."> 
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
                            </span> 
                        </div>
                    </li>
                    <li class="user-pro">
                        <?php 
                            $stmt = $pdo->prepare("SELECT image_path FROM users WHERE email = :email");
                            $stmt->bindParam(':email', $_SESSION['email'], PDO::PARAM_STR);
                            $stmt->execute();
                            $result = $stmt->fetch();
                            $path = $result['image_path'] ?? '../plugins/images/user.jpg';
                        ?>
                        <a href="#" class="waves-effect">
                            <img src="<?php echo $path; ?>" alt="user-img" class="img-circle"> 
                            <span class="hide-menu"> Account <span class="fa arrow"></span></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li><a href="profile_picture.php"><i class="ti-user"></i> Profile Picture Change</a></li>
                            <li><a href="settings.php"><i class="ti-settings"></i> Account Setting</a></li>
                            <li><a href="login.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-small-cap m-t-10">--- Main Menu</li>
                    <li> <a href="index.php" class="waves-effect"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </span></a></li>
                    <li> 
                        <a href="#" class="waves-effect active"><i data-icon="&#xe00b;" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Blog <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="posts.php">All Posts</a></li>
                            <li><a href="new-post.php">Create Post</a></li>
                            <li><a href="comments.php" class="waves-effect">Comments</a></li>
                        </ul>
                    </li>
                    <li><a href="inbox.php" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Messages</span></a></li>
                    <li><a href="subscribers.php" class="waves-effect"><i data-icon="n" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Subscribers</span></a></li>
                    <li class="nav-small-cap">--- Other</li>
                    <li> 
                        <a href="#" class="waves-effect"><i data-icon="H" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Access <span class="fa arrow"></span></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="users.php">Administrators</a></li>
                            <li><a href="new-user.php">Create Admin</a></li>
                        </ul>
                    </li>
                    <li><a href="login.php" class="waves-effect"><i class="icon-logout fa-fw"></i> <span class="hide-menu">Log out</span></a></li>
                </ul>
            </div>
        </div>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title"><?php echo htmlspecialchars($email); ?></h4> 
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Posts</a></li>
                            <li class="active">New</li>
                        </ol>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">Create New Blog Post</h3>
                            <p class="text-muted m-b-30 font-13">A blog post contains the author, title, and its content.</p>
                            <div>
                                <form id="blogPostForm" action="functions/new_post.php" method="post">
                                    <div class="form-group">
                                        <label for="author">Author Name</label>
                                        <input type="text" class="form-control" id="author" name="author" placeholder="Enter author name or leave blank for anonymous">
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Post Title</label>
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editor">Content</label>
                                        <textarea id="editor" name="content" class="form-control" rows="5"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit Post</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="footer text-center"> © 2024 Life Loop </footer>
    </div>

<!-- <script>
    CKEDITOR.replace('editor');
</script> -->
<!-- jQuery -->
 
<script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
<!-- <script src="js/jquery.min.js"></script> -->
<!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/tether.min.js"></script>
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    // Initialize CKEditor
    ClassicEditor.create(document.querySelector('#editor'))
        .then(editor => {
            // Add a form submit handler to sync data
            const form = document.getElementById('blogPostForm');
            form.addEventListener('submit', function (event) {
                // Ensure the hidden textarea is updated with the editor data
                const hiddenTextarea = document.querySelector('#editor');
                hiddenTextarea.value = editor.getData();
            });
        })
        .catch(error => {
            console.error(error);
        });
</script>


</body>
</html>
