<?php  include('includes\head_section.php'); ?>
            <?php if (isset($_SESSION['user']['username'])) { ?>
<header>
  <nav class="navbar navbar-expand-lg   navbar-dark bg-dark shadow-lg">
    <a class="navbar-brand" href="index.php"><img src="./static/images/4.png" alt="Logo" width= 50 height= 50></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Домашняя<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Новости</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Контакты</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">О нас</a>
              </li>
            </ul>
            <h5 class="user-name">Приветствую <?php echo $_SESSION['user']['username']?></h5>
            <button class="button" onclick="window.location.href='./logout.php'">Выйти</button> 
      </div>
  </nav>
</header>
     
<?php }else{ ?>
  <header>
        <nav class="navbar navbar-expand-lg   navbar-dark bg-dark shadow-lg">
          <a class="navbar-brand" href="index.php"><img src="./static/images/4.png" alt="Logo" width= 50 height= 50></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Домашняя<span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a  class="nav-link" href="#">Новости</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Контакты</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">О нас</a>
              </li>
            </ul>
                <div style="width: 60%; margin: 0px auto;">
                  <?php include(ROOT_PATH . '\includes\errors.php') ?>
                </div>
            <button type="button"  class="button" data-toggle="modal" data-target="#exampleModalCentered">Войти</button>
        </div>
        </nav>
  </header>
<?php } ?>



<!-- Modal -->
<div class="modal" id="exampleModalCentered" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenteredLabel">Войдите или зарегистрируйтесь</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
            <div class="modal-body">
                      <div class="login_div">
                        <form action="<?php echo BASE_URL . 'index.php'; ?>" method="post" >
                            <h2 style="text-align: center;">Уже зарегистрированы?</h2>
                              <div style="width: 60%; margin: 0px auto;">
                                    <?php include(ROOT_PATH . '\includes\errors.php') ?>
                              </div>
                                <input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">
                                <input type="password" name="password"  placeholder="Password"> 
                                <button class="btn-primary button" type="submit" name="login_btn" onclick="">Войти</button>
                        </form>
                      </div>
            </div>
      
              <div class="modal-footer">
                <h6>Ещё не зарегистрированы?</h6>
                <button class="button" onclick="window.location.href='./register.php'">Регистрация</button>
              </div>
    </div>
  </div>
</div>
