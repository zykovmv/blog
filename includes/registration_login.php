<?php 
	//объявление переменной
	$username = "";
	$email    = "";
	$errors = array(); 

	// РЕГИСТРАЦИЯ ПОЛЬЗОВАТЕЛЯ
	if (isset($_POST['reg_user'])) {
		// получить все входные значения из формы
		$username = esc($_POST['username']);
		$email = esc($_POST['email']);
		$password_1 = esc($_POST['password_1']);
		$password_2 = esc($_POST['password_2']);

		// проверка формы: убедитесь, что форма заполнена правильно
		if (empty($username)) {  array_push($errors, ":(...нам нужно знать ваше имя..."); }
		if (empty($email)) { array_push($errors, "Упс...похоже вы забыли вписать свой email"); }
		if (empty($password_1)) { array_push($errors, "Ох-ох...вы забыли указать свой пароль"); }
		if ($password_1 != $password_2) { array_push($errors, "Пароли не совпадают :(");}

		// Убедитесь, что ни один пользователь не зарегистрирован дважды.
		// адрес электронной почты и имена пользователей должны быть уникальными
		$user_check_query = "SELECT * FROM users WHERE username='$username' 
								OR email='$email' LIMIT 1";

		$result = mysqli_query($conn, $user_check_query);
		$user = mysqli_fetch_assoc($result);

		if ($user) { // если пользователь существует
			if ($user['username'] === $username) {
			  array_push($errors, "Username already exists");
			}
			if ($user['email'] === $email) {
			  array_push($errors, "Email already exists");
			}
		}
		// зарегистрировать пользователя, если в форме нет ошибок
		if (count($errors) == 0) {
			$password = md5($password_1);//зашифровать пароль перед сохранением в базе данных
			$query = "INSERT INTO users (username, email, password, created_at, updated_at) 
					  VALUES('$username', '$email', '$password', now(), now())";
			mysqli_query($conn, $query);

			// получить идентификатор созданного пользователя
			$reg_user_id = mysqli_insert_id($conn); 

			// положить зарегистрированного пользователя в сессию
			$_SESSION['user'] = getUserById($reg_user_id);

			// если пользователь является администратором, перенаправить в админку
			if ( in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
				$_SESSION['message'] = "Вы вошли как администратор";
				// перенаправить в админку
				header('location: ' . BASE_URL . 'admin/dashboard.php');
				exit(0);
			} else {
				$_SESSION['message'] = "Вы вошли как обычный пользователь";
				// перенаправить в публичную зону
				header('location: index.php');				
				exit(0);
			}
		}
	}

	// LOG USER IN
	if (isset($_POST['login_btn'])) {
		$username = esc($_POST['username']);
		$password = esc($_POST['password']);

		if (empty($username)) { array_push($errors, "Имя пользователя обязательно"); }
		if (empty($password)) { array_push($errors, "Пароль обязателен"); }
		if (empty($errors)) {
			$password = md5($password); // зашифровать пароль
			$sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";

			$result = mysqli_query($conn, $sql);
			if (mysqli_num_rows($result) > 0) {
				// получить идентификатор созданного пользователя
				$reg_user_id = mysqli_fetch_assoc($result)['id']; 

				// положить зарегистрированного пользователя в сессионный массив
				$_SESSION['user'] = getUserById($reg_user_id); 

				// если пользователь является администратором, перенаправить в админку
				if ( in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
					$_SESSION['message'] = "Вы вошли как администратор";
					// перенаправить в админку
					header('location: ' . BASE_URL . '/admin/dashboard.php');
					exit(0);
				} else {
					$_SESSION['message'] = "Вы вошли как обычный пользователь";
					// перенаправить в публичную зону
					header('location: index.php');				
					exit(0);
				}
			} else {
				array_push($errors, 'Неверные данные');
			}
		}
	}
	// избежать значения из формы
	function esc(String $value)
	{	
		// привести в действие глобальный объект db connect
		global $conn;

		$val = trim($value); // удалить пустое пространство вокруг строки
		$val = mysqli_real_escape_string($conn, $value);

		return $val;
	}
	// Получить информацию о пользователе от идентификатора пользователя
	function getUserById($id)
	{
		global $conn;
		$sql = "SELECT * FROM users WHERE id=$id LIMIT 1";

		$result = mysqli_query($conn, $sql);
		$user = mysqli_fetch_assoc($result);

		// возвращает пользователя в формате массива:
		// ['id'=>1 'username' => 'Awa', 'email'=>'a@a.com', 'password'=> 'mypass']
		return $user; 
	}
?>