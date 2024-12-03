<?php
require_once "admin/functions/db.php";

// Check if $pdo is a valid PDO instance
if (!$pdo instanceof PDO) {
    die("Database connection is not a valid PDO object.");
}

// Default pagination variables
$limit = 10; // Number of posts per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Search query
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

try {
    $sql = "SELECT * FROM posts WHERE title LIKE :search OR content LIKE :search LIMIT :start, :limit";
    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $stmt->bindValue(':start', $start, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Total count for pagination
    $countSql = "SELECT COUNT(*) as total FROM posts WHERE title LIKE :search OR content LIKE :search";
    $countStmt = $pdo->prepare($countSql);
    $countStmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $countStmt->execute();
    $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

    $totalPages = ceil($totalCount / $limit);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="images/icon.png">
    <title>Life Loop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Blog, Pagination, Search" />
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/custom.css" rel="stylesheet" type="text/css" media="all" />
    <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
    <link href="css/font-awesome.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        .search-bar {
            margin-bottom: 30px;
        }

        .search-bar input[type="text"] {
            width: calc(100% - 120px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
        }

        .search-bar button {
            width: 100px;
            padding: 10px;
            border: none;
            background: #007bff;
            color: #fff;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .search-bar button:hover {
            background: #0056b3;
        }

        .post-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f9f9f9;
            transition: transform 0.3s ease;
        }

        .post-card:hover {
            transform: scale(1.02);
        }

        .post-card h4 {
            font-size: 1.5em;
            color: #007bff;
            margin-bottom: 10px;
        }

        .post-card h4:hover {
            color: #0056b3;
        }

        .pagination {
            margin: 20px 0;
            display: flex;
            justify-content: center;
        }

        .pagination ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 10px;
        }

        .pagination li a {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #007bff;
            transition: background 0.3s ease;
        }

        .pagination li a.active, .pagination li a:hover {
            background: #007bff;
            color: #fff;
            border-color: #0056b3;
        }

        @media (max-width: 768px) {
            .post-card {
                padding: 15px;
            }

            .pagination ul {
                flex-wrap: wrap;
                gap: 5px;
            }

            .pagination li a {
                font-size: 0.9em;
            }
        }
    </style>
</head>
<body>
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
							<li class="active"><a href="blog.php">Blog</a></li>
							<li><a href="about.php">About</a></li>
							<li><a href="contact.php">Contact</a></li>
							<li><a href="admin/login.php">Sign In | Up</a></li>
						</ul>
						
					</nav>

					</div>
				</nav>
			</div>
		</div>
	</div>

    <div class="gallery">
        <div class="container">
            <h2 class="w3l_head w3l_head1">Blog</h2>
            <form method="get" action="blog.php" class="search-bar">
                <input type="text" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Search</button>
            </form>

            <div class="wthree_gallery_grids">
			<div class="container">
				<div class="row">
					<?php 
					if (empty($posts)) {
						echo "<p class='text-center' style='color: brown;'>Sorry, no posts found! Please try a different search.</p>";
					} else {
						$key = 0; // Initialize the counter

						foreach ($posts as $post) {
							// Check if it's time to start a new row (every 4 posts)
							if ($key % 4 == 0 && $key != 0) {
								echo '</div><div class="row">'; // Close the previous row and start a new row
							}

							echo '
							<div class="col-md-3 col-sm-6 col-xs-12">
								<div class="post-card">
									<a href="single.php?id=' . $post["id"] . '">
										<h4>' . $post["title"] . '</h4>
									</a>
									<p>' . substr($post["content"], 0, 150) . '...</p>
									<h6 style="color: orange;">' . $post["author"] . ' | ' . $post["date"] . '</h6>
								</div>
							</div>';

							$key++; // Increment the key
						}

						// Close the last row if it's open
						if ($key % 4 != 0) {
							echo '</div>';
						}
					}
					?>
					</div>
				</div>


                <!-- Pagination -->
                <div class="pagination">
                    <?php if ($totalPages > 1): ?>
                        <ul>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li><a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" <?= $i == $page ? 'class="active"' : '' ?>><?= $i ?></a></li>
                            <?php endfor; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
