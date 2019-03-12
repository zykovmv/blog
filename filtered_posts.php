<?php include('config.php'); ?>
<?php include('includes\public_functions.php'); ?>
<?php include('includes\head_section.php'); ?>
<?php
    if (isset($_GET['topic'])){
        $topic_id = $_GET['topic'];
        $posts = getPublishedPostsByTopic($topic_id);
    }
?>
    <title>LifeBlog | Home</title>
</head>
<body>
<!-- Navbar -->
<?php include( ROOT_PATH . '/includes/navbar.php'); ?>
<!-- // Navbar -->
	<div class="container main-content">
		<!-- content -->
		<!-- <div class="container-fluid"> -->
			<h2 class="content-title">
				Articles on <u><?php echo getTopicNameById($topic_id); ?></u>
			</h2>
			<hr>
					<div class="row text-center">  
					<?php foreach ($posts as $post): ?>
						<div class="col-12 col-sm-12 col-md-6 col-lg-4">
							<img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image" alt="">
								<a href="single_post.php?post-slug=<?php echo $post['slug']; ?>">
									<div class="col">
										<h3><?php echo $post['title'] ?></h3>
										<div class="col">
											<span><?php echo date("F j, Y ", strtotime($post["created_at"])); ?></span>
											<span class="read_more">Read more...</span>
										</div>
									</div>
								</a>
						</div>
					<?php endforeach ?>
					</div>
	</div>
	<!-- // content -->
<!-- // container -->

<!-- Footer -->
	<?php include( ROOT_PATH . '/includes/footer.php'); ?>
<!-- // Footer -->    