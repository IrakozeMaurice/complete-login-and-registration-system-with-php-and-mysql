<?php $title = 'Register Page'; ?>
    <?php include_once('private/shared/header.php'); ?>
    <?php require_once('resource/Database.php'); ?>
    <?php require_once('resource/utilities.php'); ?>

    <!-- process form data -->
    <?php

    if (isset($_POST['signupBtn'])) {
      $errors = [];
      $required_fields = ['email', 'username', 'password'];
      $errors = array_merge($errors, check_empty_fields($required_fields));
      $errors = array_merge($errors,check_min_length(['username'=>4, 'password'=>6]));
      $errors = array_merge($errors, check_email($_POST));
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
            $result = "<p style='padding:20px; color:green;'>Registration successful.</p>";
          }
        } catch (PDOException $e) {
          $result = "<p style='padding:20px; color:red;'>Failed to signup: an error occured." . $e->getMessage() . "</p>";
        }
      }else {
        $result = "<p style='color:red;> there was errors in the form.</p><br>'";
      }
    }
  ?>
    <h3>Registration Form</h3>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($errors)) echo show_errors($errors); ?>
    <form action="signup.php" method="post">
      <table>
        <tr>
          <td>Email:</td><td> <input type="email" name="email" value=""> </td>
        </tr>
        <tr>
          <td>Username:</td><td> <input type="text" name="username" value=""> </td>
        </tr>
        <tr>
          <td>Password:</td><td> <input type="password" name="password" value=""> </td>
        </tr>
        <tr>
          <td></td><td> <input style="float: right;" type="submit" name="signupBtn" value="Signup"> </td>
        </tr>
      </table>
    </form>
    <p> <a href="index.php">Back</a> </p>
    <?php include_once('private/shared/footer.php'); ?>
