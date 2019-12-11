<?php 
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

// получить все посты из БД
function getAllPosts()
{
	global $conn;
	
// Администратор может просматривать все сообщения
// Автор может просматривать только свои сообщения
	if ($_SESSION['user']['role'] == "Admin") {
		$sql = "SELECT * FROM posts";
	} elseif ($_SESSION['user']['role'] == "Author") {
		$user_id = $_SESSION['user']['id'];
		$sql = "SELECT * FROM posts WHERE user_id=$user_id";
	}
	$result = mysqli_query($conn, $sql);
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['author'] = getPostAuthorById($post['user_id']);
		array_push($final_posts, $post);
	}
	return $final_posts;
}
// получить автора  поста
function getPostAuthorById($user_id)
{
	global $conn;
	$sql = "SELECT username FROM users WHERE id=$user_id";
	$result = mysqli_query($conn, $sql);
	if ($result) {
		return mysqli_fetch_assoc($result)['username'];
	} else {
		return null;
	}
}


// если пользователь нажимает кнопку создания поста
if (isset($_POST['create_post'])) { createPost($_POST); }
// если пользователь нажимает кнопку «Редактировать пост»
if (isset($_GET['edit-post'])) {
	$isEditingPost = true;
	$post_id = $_GET['edit-post'];
	editPost($post_id);
}
// если пользователь нажимает кнопку Обновить сообщения
if (isset($_POST['update_post'])) {
	updatePost($_POST);
}
// если пользователь нажимает кнопку Удалить пост
if (isset($_GET['delete-post'])) {
	$post_id = $_GET['delete-post'];
	deletePost($post_id);
}


function createPost($request_values)
	{
		global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;
		$title = esc($request_values['title']);
		$body = htmlentities(esc($request_values['body']));
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		if (isset($request_values['publish'])) {
			$published = esc($request_values['publish']);
		}
		// создаем слаг
		$post_slug = makeSlug($title);
		// проверить форму
		if (empty($title)) { array_push($errors, "Заголовок поста обязателен"); }
		if (empty($body)) { array_push($errors, "Тело поста обязательно"); }
		if (empty($topic_id)) { array_push($errors, "Тема поста обязательна"); }
		// Получить имя изображения
	  	$featured_image = $_FILES['featured_image']['name'];
	  	if (empty($featured_image)) { array_push($errors, "Требуется изображение"); }
	  	// каталог файлов изображений
	  	$target = "../static/images/" . basename($featured_image);
	  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
	  		array_push($errors, "Не удалось загрузить изображение. Пожалуйста, проверьте настройки вашего сервера");
	  	}
		// убеждаемся, что ни одно пост не было сохранено дважды.
		$post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
		$result = mysqli_query($conn, $post_check_query);

		if (mysqli_num_rows($result) > 0) {
			array_push($errors, "Пост с таким названием уже существует.");
		}
		// создать пост, если в форме нет ошибок
		if (count($errors) == 0) {
			$query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) VALUES(1, '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
			if(mysqli_query($conn, $query)){ // если сообщение создано успешно
				$inserted_post_id = mysqli_insert_id($conn);
				// создать связь между постом и темой
				$sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
				mysqli_query($conn, $sql);

				$_SESSION['message'] = "Пост успешно создан";
				header('location: posts.php');
				exit(0);
			}
		}
	}

	/* * * * * * * * * * * * * * * * * * * * *
	* - принимаем идентификатор записи в качестве параметра
	* - извлекаем сообщение из базы данных
	* - устанавливаем поля сообщения в форме для редактирования
	* * * * * * * * * * * * * * * * * * * * * */
	function editPost($role_id)
	{
		global $conn, $title, $post_slug, $body, $published, $isEditingPost, $post_id;
		$sql = "SELECT * FROM posts WHERE id=$role_id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$post = mysqli_fetch_assoc($result);
		// устанавливаем значения формы в форму для обновления
		$title = $post['title'];
		$body = $post['body'];
		$published = $post['published'];
	}

	function updatePost($request_values)
	{
		global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

		$title = esc($request_values['title']);
		$body = esc($request_values['body']);
		$post_id = esc($request_values['post_id']);
		if (isset($request_values['topic_id'])) {
			$topic_id = esc($request_values['topic_id']);
		}
		// создаем слаг
		$post_slug = makeSlug($title);

		if (empty($title)) { array_push($errors, "Заголовок сообщения обязателен"); }
		if (empty($body)) { array_push($errors, "Тело сообщения обязательно"); }
		// если предоставлено новое изображение
		if (isset($_POST['featured_image'])) {
			// Получить имя изображения
		  	$featured_image = $_FILES['featured_image']['name'];
		  	// каталог файлов изображений
		  	$target = "../static/images/" . basename($featured_image);
		  	if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
		  		array_push($errors, "Не удалось загрузить изображение. Пожалуйста, проверьте настройки вашего сервера");
		  	}
		}

		// зарегистрируем тему, если в форме нет ошибок
		if (count($errors) == 0) {
			$query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, image='$featured_image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
			// прикрепить тему к сообщению 
			if(mysqli_query($conn, $query)){ // если сообщение создано успешно
				if (isset($topic_id)) {
					$inserted_post_id = mysqli_insert_id($conn);
					// создать связь между постом и темой
					$sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
					mysqli_query($conn, $sql);
					$_SESSION['message'] = "Пост успешно создан";
					header('location: posts.php');
					exit(0);
				}
			}
			$_SESSION['message'] = "Пост успешно обновлен";
			header('location: posts.php');
			exit(0);
		}
	}
	// удалить запись в блоге
	function deletePost($post_id)
	{
		global $conn;
		$sql = "DELETE FROM posts WHERE id=$post_id";
		if (mysqli_query($conn, $sql)) {
			$_SESSION['message'] = "Пост успешно удален";
			header("location: posts.php");
			exit(0);
		}
	}
	// если пользователь нажимает кнопку Опубликовать
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
	$message = "";
	if (isset($_GET['publish'])) {
		$message = "Пост успешно опубликован";
		$post_id = $_GET['publish'];
	} else if (isset($_GET['unpublish'])) {
		$message = "Пост не опубликован";
		$post_id = $_GET['unpublish'];
	}
	togglePublishPost($post_id, $message);
}
// удаляем запись в блоге
function togglePublishPost($post_id, $message)
{
	global $conn;
	$sql = "UPDATE posts SET published=!published WHERE id=$post_id";
	
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = $message;
		header("location: posts.php");
		exit(0);
	}
}
?>