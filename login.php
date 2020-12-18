    <?php $title = 'Login Page'; ?>
    <?php require_once('resource/session.php'); ?>
    <?php require_once('resource/Database.php'); ?>
    <?php require_once('resource/utilities.php'); ?>
    <?php include_once('shared/header.php'); ?>

    <?php

    if (isset($_POST['loginBtn'])) {
      $errors = [];
      $required_fields = ['username', 'password'];
      $errors = array_merge($errors, check_empty_fields($required_fields));
      if (empty($errors)) {
        $remember = isset($_POST['remember']) ? $_POST['remember'] : "";
        // check if user exists in the database
        $query = "select * from users where username = :username";
        $statement = $db->prepare($query);
        $statement->execute([':username' => $_POST['username']]);
        $row = $statement->fetch();
        if( isset($row) &&  password_verify($_POST['password'], $row['password']) ) {
            // create session for current user
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            //check if remember me checkbox is checked
            if ($remember === "yes") {
              rememberMe($row['id']);
            }
            //call sweetalert
            echo $welcome = "<script type=\"text/javascript\">
            swal({
                title: \"Welcome back {$_SESSION['username']}\",
                text: \"You're being logged in\",
                type: \"success\",
                closeOnConfirm: false });

                setTimeout(function () {
                  window.location.href = 'index.php';
                }, 2000);
            </script>";
        }else {
          $result = show_msg("Invalid username or password.", "fail");
        }
      }else {
        $result = show_msg("There are empty field(s) in the form.", "fail");
      }
    }

   ?>

  <div>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($errors)) echo show_errors($errors); ?>
  </div>
  <div class="clearfix"></div>
  <section class="col-lg-7">
    <h3>Login Form</h3><br>
    <form action="login.php" method="post">
      <div class="form-group">
        <label for="user">Username</label>
        <input type="text" class="form-control" id="user" name="username" placeholder="username">
      </div>
      <div class="form-group">
        <label for="pass">Password</label>
        <input type="password" class="form-control" id="pass" name="password" placeholder="Password">
      </div>
      <div class="checkbox">
        <label>
          <input type="checkbox" value="yes" name="remember"> Remember me
        </label>
      </div>
      <a href="forgot_password.php">Forgot Password?</a>
      <button type="submit" class="btn btn-primary pull-right" name="loginBtn">Sign in</button>
    </form><br><br>
      <p> <a href="index.php" class="btn btn-default">Back</a> </p>
</section>

<?php include_once('shared/footer.php'); ?>
