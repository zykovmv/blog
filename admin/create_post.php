<?php  include('../config.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php  include(ROOT_PATH . '/admin/includes/post_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<!-- Получить все темы -->
<?php $topics = getAllTopics();	?>
	<title>Администратор | Создать пост</title>
</head>
<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>

	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/admin/includes/menu.php') ?>

		<!-- форма - для создания и редактирования -->
		<div class="action create-post-div">
			<h1 class="page-title">Создать/редактировать пост</h1>
			<form method="post" enctype="multipart/form-data" action="<?php echo BASE_URL . 'admin/create_post.php'; ?>" >
				<!-- проверка на ошибки в форме -->
				<?php include(ROOT_PATH . '/includes/errors.php') ?>

				<!-- при редактировании поста для его идентификации требуется идентификатор -->
				<?php if ($isEditingPost === true): ?>
					<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
				<?php endif ?>

				<input type="text" name="title" value="<?php echo $title; ?>" placeholder="Title">
				<label style="float: left; margin: 5px auto 5px;">Изображение</label>
				<input type="file" name="featured_image" >
				<textarea name="body" id="body" cols="30" rows="10"><?php echo $body; ?></textarea>
				<select name="topic_id">
					<option value="" selected disabled>Выбрать тему</option>
					<?php foreach ($topics as $topic): ?>
						<option value="<?php echo $topic['id']; ?>">
							<?php echo $topic['name']; ?>
						</option>
					<?php endforeach ?>
				</select>
				
				<!-- Только администратор может просматривать поле ввода публикации -->
				<?php if ($_SESSION['user']['role'] == "Admin"): ?>
					<!-- отображать флажок в зависимости от того, была ли публикация опубликована или нет -->
					<?php if ($published == true): ?>
						<label for="publish">
							Опубликовать
							<input type="checkbox" value="1" name="publish" checked="checked">&nbsp;
						</label>
					<?php else: ?>
						<label for="publish">
							Опубликовать
							<input type="checkbox" value="1" name="publish">&nbsp;
						</label>
					<?php endif ?>
				<?php endif ?>
				
				<!-- при редактировании поста вместо кнопки создания отобразить кнопку обновления -->
				<?php if ($isEditingPost === true): ?> 
					<button type="submit" class="btn" name="update_post">Обновить</button>
				<?php else: ?>
					<button type="submit" class="btn" name="create_post">Сохранить</button>
				<?php endif ?>

			</form>
		</div>
		<!-- //форма - для создания и редактирования -->
	</div>
</body>
</html>

<script>
	CKEDITOR.replace('body');
</script>