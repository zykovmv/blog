<?php  include('config.php'); ?>
<?php  include('includes/public_functions.php'); ?>
<?php require_once( ROOT_PATH . '\includes\registration_login.php') ?>
<?php 
	if (isset($_GET['post-slug'])) {
		$post = getPost($_GET['post-slug']);
	}
	$topics = getAllTopics();
?>
<?php include('includes/head_section.php'); ?>
<title> <?php echo $post['title'] ?> | LifeBlog</title>
</head>
<body>
	<!-- Navbar -->
	<?php include( ROOT_PATH . '\includes\navbar.php'); ?>
	<!-- // Navbar -->
<div class="container main-content">
				<div class="row justify-content-center">
					<div class="col-12 col-sm-12 col-lg-10 col-xl-10 text-justify" >
							<!-- Page wrapper -->
							<div class="post-wrapper">
								<!-- full post div -->
								<div class="full-post-div">
										<?php if ($post['published'] == false): ?>
											<h2 class="post-title">Sorry... This post has not been published</h2>
										<?php else: ?>
											<h2 class="post-title"><?php echo $post['title']; ?></h2>
											<div class="post-body-div">
												<?php echo html_entity_decode($post['body']); ?>
											</div>
										<?php endif ?>
								</div>
								<!-- // full post div -->
								
								<!-- comments section -->
								<!--  coming soon ...  -->
							</div>
							<!-- // Page wrapper -->
					</div>
					<!-- post sidebar -->
					<div class="post-sidebar col col-sm col-lg col-xl">
							<div class="card">
								<div class="card-header">
									<h6>Темы, которые могут заинтересовать</h6>
								</div>
								<aside class="card-content">
									<?php foreach ($topics as $topic): ?>
										<a 
											href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>">
											<?php echo $topic['name']; ?>
										</a> 
									<?php endforeach ?>
								</aside>
									<p style="padding-top: 5px; margin-bottom:2px;">Поделиться:</p>
									<div 
										class="ya-share2" data-direction="vertical" data-services="collections,vkontakte,facebook,odnoklassniki,whatsapp,telegram" data-counter="">
									</div>
							</div>
							
					</div>
						<!-- // post sidebar -->
				</div>
</div>
<!-- // content -->

<?php include( ROOT_PATH . '/includes/footer.php'); ?>
