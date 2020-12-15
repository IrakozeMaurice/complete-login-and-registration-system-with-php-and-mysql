    <?php $title = 'Login Page'; ?>
    <?php include_once('private/shared/header.php'); ?>
    <?php require_once('resource/session.php'); ?>
    <?php require_once('resource/Database.php'); ?>
    <?php require_once('resource/utilities.php'); ?>

    <?php

    if (isset($_POST['loginBtn'])) {
      $errors = [];
      $required_fields = ['username', 'password'];
      $errors = array_merge($errors, check_empty_fields($required_fields));

      if (empty($errors)) {
        // check if user exists in the database
        $query = "select * from users where username = :username";
        $statement = $db->prepare($query);
        $statement->execute([':username' => $_POST['username']]);
        $row = $statement->fetch();
        if( isset($row) &&  password_verify($_POST['password'], $row['password']) ) {
            // create session for current user
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");

        }else {
          $result = "<p style='color:red;'>Invalid username or password.</p>";
        }
      }else {
        $result = "<p style='color:red;'>There are empty field(s) in the form </p>";
      }
    }

   ?>
    <h3>Login Form</h3>
    <?php if(isset($result)) echo $result; ?>
    <?php if(!empty($errors)) echo show_errors($errors); ?>
    <form action="login.php" method="post">
      <table>
        <tr>
          <td>Username:</td><td> <input type="text" name="username" value=""> </td>
        </tr>
        <tr>
          <td>Password:</td><td> <input type="password" name="password" value=""> </td>
        </tr>
        <tr>
          <td></td><td> <input style="float: right;" type="submit" name="loginBtn" value="Signin"> </td>
        </tr>
      </table>
    </form>
    <p> <a href="forgot_password.php">Forgot Password?</a> </p>
    <p> <a href="index.php">Back</a> </p>
    <?php include_once('private/shared/footer.php'); ?>
