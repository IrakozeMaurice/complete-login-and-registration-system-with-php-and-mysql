<?php $title = 'Register Page'; ?>
    <?php include_once('shared/header.php'); ?>
    <?php require_once('resource/Database.php'); ?>
    <?php require_once('resource/utilities.php'); ?>

    <!-- process form data -->
    <?php

    if (isset($_POST['signupBtn'])) {
      $errors = [];
      $required_fields = ['email', 'username', 'password'];
      $errors = array_merge($errors, check_empty_fields($required_fields));
      if (empty($errors)) {
        $errors = array_merge($errors,check_min_length(['username'=>4, 'password'=>6]));
      }
      $errors = array_merge($errors, check_email($_POST));
      //check duplicate email or username

      if (empty($errors)) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $hash_password = password_hash($password,PASSWORD_DEFAULT);

        try {
          $query = "insert into users (username, email, password, join_date)
                    values(:username, :email, :password, now())";
          $statement = $db->prepare($query);
          $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $hash_password));
          if ($statement->rowCount() == 1) {

            //call sweetalert
            $result = "<script type=\"text/javascript\">
            swal({
                title: \"Congratulations {$username}\",
                text: \"Registration completed successfully.\",
                type: \"success\",
                confirmButtonText: \"Thank you\" });
            </script>";
          }
        } catch (PDOException $e) {
          // $result = show_msg("Failed to signup: an error occured." . $e->getMessage());
          if (check_duplicate_entries("users", "email", $_POST['email'], $db)) {
            $result = show_msg("Email is already taken.Try another one.");
          }else if (check_duplicate_entries("users", "username", $_POST['username'], $db)) {
            $result = show_msg("Username is already taken.Try another one.");
          }
        }
      }else {
        $result = show_msg("There are errors in the form.");
      }
    }
  ?>
  <div>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($errors)) echo show_errors($errors); ?>
  </div>
  <div class="clearfix"></div>
    <section class="col-lg-7">
      <h3>Registration Form</h3><br>
      <form action="signup.php" method="post">
        <div class="form-group">
          <label for="mail">Email</label>
          <input type="email" class="form-control" id="mail" name="email" placeholder="Email">
        </div>
        <div class="form-group">
          <label for="user">Username</label>
          <input type="text" class="form-control" id="user" name="username" placeholder="username">
        </div>
        <div class="form-group">
          <label for="pass">Password</label>
          <input type="password" class="form-control" id="pass" name="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary pull-right" name="signupBtn">Sign up</button>
      </form><br><br>
        <p> <a href="index.php" class="btn btn-default">Back</a> </p>
    </section>

    <?php include_once('shared/footer.php'); ?>
