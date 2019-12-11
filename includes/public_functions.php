<?php
function getPublishedPosts(){
    global $conn;
    $sql = "SELECT * FROM posts WHERE published=true ORDER BY updated_at ASC";
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    $final_posts = array();
    foreach ($posts as $post){
        $post['topic'] = getPostTopic($post['id']);
        array_push($final_posts, $post);
    }
    return $final_posts;
}
function getPostTopic($post_id){
    global $conn;
    $sql = "SELECT * FROM topics WHERE id = 
    (SELECT topic_id FROM post_topic WHERE post_id = $post_id) LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic; 
}

/* * * * * * * * * * * * * * * *
* Возвращает все сообщения в теме
* * * * * * * * * * * * * * * * */
function getPublishedPostsByTopic($topic_id) {
	global $conn;
	$sql = "SELECT * FROM posts ps 
			WHERE ps.id IN 
			(SELECT pt.post_id FROM post_topic pt 
				WHERE pt.topic_id=$topic_id GROUP BY pt.post_id 
				HAVING COUNT(1) = 1)";
	$result = mysqli_query($conn, $sql);
	// получить все сообщения в виде ассоциативного массива $posts
	$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

	$final_posts = array();
	foreach ($posts as $post) {
		$post['topic'] = getPostTopic($post['id']); 
		array_push($final_posts, $post);
	}
	return $final_posts;
}
/* * * * * * * * * * * * * * * *
* Возвращает название темы по идентификатору темы
* * * * * * * * * * * * * * * * */
function getTopicNameById($id)
{
	global $conn;
	$sql = "SELECT name FROM topics WHERE id=$id";
	$result = mysqli_query($conn, $sql);
	$topic = mysqli_fetch_assoc($result);
	return $topic['name'];
}
/* * * * * * * * * * * * * * *
* Возвращает один пост
* * * * * * * * * * * * * * */
function getPost($slug){
	global $conn;
	// Получить слаг
	$post_slug = $_GET['post-slug'];
	$sql = "SELECT * FROM posts WHERE slug='$post_slug' AND published=true";
	$result = mysqli_query($conn, $sql);

	// получить результаты запроса в виде ассоциативного массива.
	$post = mysqli_fetch_assoc($result);
	if ($post) {
		// получить тему, к которой принадлежит этот пост
		$post['topic'] = getPostTopic($post['id']);
	}
	return $post;
}
/* * * * * * * * * * * *
*  Возвращает все темы
* * * * * * * * * * * * */
function getAllTopics()
{
	global $conn;
	$sql = "SELECT * FROM topics";
	$result = mysqli_query($conn, $sql);
	$topics = mysqli_fetch_all($result, MYSQLI_ASSOC);
	return $topics;
}

/* * * * * * * * * * * *
*  Возвращает URL страницы
* * * * * * * * * * * * */
function currentURL(){
	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') 
                === FALSE ? 'http' : 'https';
	$host     = $_SERVER['HTTP_HOST'];
	$script   = $_SERVER['SCRIPT_NAME'];
	$params   = $_SERVER['QUERY_STRING'];
	
	$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	
	echo $currentUrl;
}
?>