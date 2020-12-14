<?php $title = 'Register Page'; ?>
    <?php include_once('private/shared/header.php'); ?>
    <?php require_once('resource/Database.php'); ?>
    <!-- process form data -->
    <?php
    if (isset($_POST['email'])) {
      $email = $_POST['email'];
      $username = $_POST['username'];
      $password = $_POST['password'];
      try {
        $query = "insert into users (username, email, password, join_date)
                  values(:username, :email, :password, now())";
        $statement = $db->prepare($query);
        $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $password));
        if ($statement->rowCount() == 1) {
          $result = "<p style='padding:20px; color:green;'>Registration successful.</p>";
        }
      } catch (PDOException $e) {
        $result = "<p style='padding:20px; color:red;'>Failed to signup: an error occured." . $e->getMessage() . "</p>";
      }
    }
  ?>
    <h3>Registration Form</h3>
    <?php if(isset($result)) echo $result; ?>
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
          <td></td><td> <input style="float: right;" type="submit"  value="Signup"> </td>
        </tr>
      </table>
    </form>
    <p> <a href="index.php">Back</a> </p>
    <?php include_once('private/shared/footer.php'); ?>
