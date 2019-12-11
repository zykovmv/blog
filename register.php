<?php  include('config.php'); ?>
<!-- Source code for handling registration and login -->
<?php  include('includes\registration_login.php'); ?>

<?php include('includes\head_section.php'); ?>

<title>LifeBlog | Sign up </title>
</head>
<body>
	<!-- Navbar -->
	<?php include( ROOT_PATH . '\includes\navbar.php'); ?>
	<!-- // Navbar -->
<div class="container-fluid	main-content">
	<div style="width: 40%; margin: 20px auto;">
		<form method="post" action="register.php" >
			<h2>Зарегистрироваться</h2>
			<?php include(ROOT_PATH . '\includes\errors.php') ?>
			<input  type="text" name="username" value="<?php echo $username; ?>"  placeholder="Username">
			<input type="email" name="email" value="<?php echo $email ?>" placeholder="Email">
			<input type="password" name="password_1" placeholder="Password">
			<input type="password" name="password_2" placeholder="Password confirmation">
			<button type="submit" class="btn" name="reg_user">Регистрация</button>
			<p>
				Уже зарегистрированы? <a href="login.php">Войти</a>
			</p>
		</form>
	</div>
</div>
<!-- // container -->
<!-- Footer -->
	<?php include( ROOT_PATH . '\includes\footer.php'); ?>
<!-- // Footer -->