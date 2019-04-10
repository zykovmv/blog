<?php require_once('config.php');?>
<?php require_once( ROOT_PATH . '\includes\public_functions.php');?>
<?php require_once( ROOT_PATH . '\includes\registration_login.php') ?>
<?php $posts = getPublishedPosts();?>
<!DOCTYPE html>
<html lang="en">
<?php require_once( ROOT_PATH . '\includes\head_section.php');?>
    <title>Blog | Home</title>
</head>
<body>
    <!-- Navbar -->
    <?php include( ROOT_PATH . '\includes\navbar.php');?>
    
    <div class="container main-content">
       <!-- Banner -->
    <?php /* include( ROOT_PATH . '\includes\banner.php'); */?>
    
<?php if (isset($_SESSION['user']['username'])) { ?>
    <!-- Page content -->
        <h2 class="content-title">Последние посты</h2>
            <hr>
        <!-- <div class="container-fluid"> -->
            <div class="row text-center">   
                    <?php foreach ($posts as $post): ?>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                        <img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image" alt="">
                        <!-- Added this if statement... -->
                            <?php if (isset($post['topic']['name'])): ?>
                                <a 
                                    href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $post['topic']['id'] ?>"
                                    class="category">
                                    <?php echo $post['topic']['name'] ?>
                                </a>
                            <?php endif ?>
                        <a class="post_title" href="single_post.php?post-slug=<?php echo $post['slug']; ?>">
                            <div class="col">
                                <h3><?php echo $post['title'] ?></h3>
                                    <div class="col">
                                        <span><?php echo date("F j, Y ", strtotime($post["created_at"])); ?></span>
                                        <span class="read_more">Узнать больше...</span>
                                    </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach ?>
            </div>
        <!-- </div> -->
        <!-- End of Page content -->
<?php }else{ ?>
    <h1>Lorem ipsum dolor sit amet consectetur, adipisicing elit. In sed odit minima iure, hic placeat consectetur ut nobis quae necessitatibus tempora error dolore repellat corrupti asperiores architecto unde earum distinctio. Itaque, voluptatum quo? Explicabo corporis molestiae eaque voluptas quae. Inventore modi sunt sit architecto vero aliquid quas quidem dicta magni tempore rem, atque reiciendis iure corrupti eaque ut? Necessitatibus id nam nisi consequatur, reprehenderit doloribus, aspernatur unde, numquam inventore optio quod reiciendis harum? Nulla amet in, soluta debitis vitae mollitia neque voluptatem provident eaque id fugiat quasi quidem eos accusantium repudiandae dolore expedita aspernatur nesciunt, praesentium recusandae nisi cupiditate quas!</h1>
<?php } ?>    
    </div>
    <!-- Footer -->
        <?php include( ROOT_PATH . '\includes\footer.php');?>
        