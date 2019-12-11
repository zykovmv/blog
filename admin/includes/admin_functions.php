<?php 
// переменные для пользователя User
$admin_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
// общие переменные
$errors = [];
// переменные для Topics
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";


//Логика  пользователя Admin

// Если пользователь нажал "Create"
if (isset($_POST['create_admin'])) {
	createAdmin($_POST);
}
// Если пользователь нажал "Edit"
if (isset($_GET['edit-admin'])) {
	$isEditingUser = true;
	$admin_id = $_GET['edit-admin'];
	editAdmin($admin_id);
}
// Если пользователь нажал "Update"
if (isset($_POST['update_admin'])) {
	updateAdmin($_POST);
}
// Если пользователь нажал "Delete"
if (isset($_GET['delete-admin'])) {
	$admin_id = $_GET['delete-admin'];
	deleteAdmin($admin_id);
}


//Логика Topic

// Если пользователь нажал "Create topic"
if (isset($_POST['create_topic'])) { createTopic($_POST); }
// Если пользователь нажал "Edit topic"
if (isset($_GET['edit-topic'])) {
	$isEditingTopic = true;
	$topic_id = $_GET['edit-topic'];
	editTopic($topic_id);
}
// Если пользователь нажал "Update topic"
if (isset($_POST['update_topic'])) {
	updateTopic($_POST);
}
// Если пользователь нажал "Delete topic"
if (isset($_GET['delete-topic'])) {
	$topic_id = $_GET['delete-topic'];
	deleteTopic($topic_id);
}


//Логика Admin users

/* * - Получить данные о новом Admin из формы
* - Создать нового Admin
* - Показать всех Admin пользователей **/ 

function createAdmin($request_values){
	global $conn, $errors, $role, $username, $email;
	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);

	if(isset($request_values['role'])){
		$role = esc($request_values['role']);
	}
	// Валидация формы
	if (empty($username)) { array_push($errors, "Хммм...нужно заполнить поле Имя пользователя"); }
	if (empty($email)) { array_push($errors, "Oops.. Email is missing"); }
	if (empty($role)) { array_push($errors, "Role is required for admin users");}
	if (empty($password)) { array_push($errors, "uh-oh you forgot the password"); }
	if ($password != $passwordConfirmation) { array_push($errors, "The two passwords do not match"); }
	// Проверка на зарегистрированного пользователя. 
	// email должен быть уникальным
	$user_check_query = "SELECT * FROM users WHERE username='$username' 
							OR email='$email' LIMIT 1";
	$result = mysqli_query($conn, $user_check_query);
	$user = mysqli_fetch_assoc($result);
	if ($user) { // если пользователь уже зарегистрирован
		if ($user['username'] === $username) {
		  array_push($errors, "Такой пользователь уже существует");
		}

		if ($user['email'] === $email) {
		  array_push($errors, "Email уже используется...");
		}
	}
	// регистрация пользователя если ошибок не обнаружено 
	if (count($errors) == 0) {
		$password = md5($password);//шифрование пароля перед отправкой в БД
		$query = "INSERT INTO users (username, email, role, password, created_at, updated_at) 
				  VALUES('$username', '$email', '$role', '$password', now(), now())";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Admin пользователь создан";
		header('location: users.php');
		exit(0);
	}
}
/* - - - - - - - - - - 
-  Логика Topics
- - - - - - - - - - -*/
// Получить все топики из БД
function getAllTopics() {
	global $conn;
	$sql = "SELECT * FROM topics";
	$result = mysqli_query($conn, $sql);
	$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $topics;
}
function createTopic($request_values){
	global $conn, $errors, $topic_name;
	$topic_name = esc($request_values['topic_name']);
	// Создаем "слаг"
	$topic_slug = makeSlug($topic_name);
	// Валидация формы
	if (empty($topic_name)) { 
		array_push($errors, "Название поста обязательно"); 
	}
	// Проверка на дублирование топика. 
	$topic_check_query = "SELECT * FROM topics WHERE slug='$topic_slug' LIMIT 1";
	$result = mysqli_query($conn, $topic_check_query);
	if (mysqli_num_rows($result) > 0) { // if topic exists
		array_push($errors, "Пост с таким названием уже существует");
	}
	// Создаем топик если нет ошибок
	if (count($errors) == 0) {
		$query = "INSERT INTO topics (name, slug) 
				  VALUES('$topic_name', '$topic_slug')";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Пост опубликован!";
		header('location: topics.php');
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - принимаем идентификатор темы в качестве параметра
* - извлекаем тему из базы данных
* - устанавливаем поля темы в форме для редактирования
* * * * * * * * * * * * * * * * * * * * * */
function editTopic($topic_id) {
	global $conn, $topic_name, $isEditingTopic, $topic_id;
	$sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	// установить значения формы ($ topic_name) в форме для обновления
	$topic_name = $topic['name'];
}
function updateTopic($request_values) {
	global $conn, $errors, $topic_name, $topic_id;
	$topic_name = esc($request_values['topic_name']);
	$topic_id = esc($request_values['topic_id']);
	// createСоздаем "слаг"
	$topic_slug = makeSlug($topic_name);
	// validate form
	if (empty($topic_name)) { 
		array_push($errors, "Topic name required"); 
	}
	// зарегистрировать пост, если в форме нет ошибок
	if (count($errors) == 0) {
		$query = "UPDATE topics SET name='$topic_name', slug='$topic_slug' WHERE id=$topic_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Пост обновлен";
		header('location: topics.php');
		exit(0);
	}
}
// Удалить пост
function deleteTopic($topic_id) {
	global $conn;
	$sql = "DELETE FROM topics WHERE id=$topic_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "Пост удален";
		header("location: topics.php");
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * *
* - принимаем ID администратора в качестве параметра
* - извлекаем админа из базы данных
* - устанавливаем поля администратора в форме для редактирования
* * * * * * * * * * * * * * * * * * * * * */
function editAdmin($admin_id)
{
	global $conn, $username, $role, $isEditingUser, $admin_id, $email;

	$sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
	$result = mysqli_query($conn, $sql);
	$admin = mysqli_fetch_assoc($result);

	// установить значения формы ($ username и $ email) в форме для обновления
	$username = $admin['username'];
	$email = $admin['email'];
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Получает запрос администратора от формы и обновлений в базе данных
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function updateAdmin($request_values){
	global $conn, $errors, $role, $username, $isEditingUser, $admin_id, $email;
	// получить идентификатор администратора для обновления
	$admin_id = $request_values['admin_id'];
	// установить состояние редактирования на false
	$isEditingUser = false;


	$username = esc($request_values['username']);
	$email = esc($request_values['email']);
	$password = esc($request_values['password']);
	$passwordConfirmation = esc($request_values['passwordConfirmation']);
	if(isset($request_values['role'])){
		$role = $request_values['role'];
	}
	// зарегистрировать пользователя, если в форме нет ошибок
	if (count($errors) == 0) {
		//зашифровать пароль (в целях безопасности)
		$password = md5($password);

		$query = "UPDATE users SET username='$username', email='$email', role='$role', password='$password' WHERE id=$admin_id";
		mysqli_query($conn, $query);

		$_SESSION['message'] = "Admin user updated successfully";
		header('location: users.php');
		exit(0);
	}
}
// удаляем администратора 
function deleteAdmin($admin_id) {
	global $conn;
	$sql = "DELETE FROM users WHERE id=$admin_id";
	if (mysqli_query($conn, $sql)) {
		$_SESSION['message'] = "User successfully deleted";
		header("location: users.php");
		exit(0);
	}
}
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * 
* - Возвращаем всех пользователей с правами администратора и их соответствующие роли
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function getAdminUsers(){
	global $conn, $roles;
	$sql = "SELECT * FROM users WHERE role IS NOT NULL";
	$result = mysqli_query($conn, $sql);
	$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

	return $users;
}
/* * * * * * * * * * * * * * * * * * * * *
* - Экранируем отправленное значение, следовательно, предотвращая внедрение SQL
* * * * * * * * * * * * * * * * * * * * * */
function esc(String $value){
	global $conn;
	// удалить пустое пространство вокруг строки
	$val = trim($value); 
	$val = mysqli_real_escape_string($conn, $value);
	return $val;
}
// Получаем строку типа 'Some Sample String'
// и возвращает 'some-sample-string'
function makeSlug(String $string){
	$string = strtolower($string);
	$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
	return $slug;
}
?>