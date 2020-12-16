<?php

$title = "Password Reset";
include_once('private/shared/header.php');
require_once('resource/Database.php');
require_once('resource/utilities.php');

if (isset($_POST['resetBtn'])) {
  //initialize array to store errors
  $errors = [];
  //form validation
  $required_fields = ['email', 'password'];
  $errors = array_merge($errors, check_empty_fields($required_fields));
  if(empty($errors)){
    $errors = array_merge($errors, check_min_length(['password' => 6]));
  }
  $errors = array_merge($errors, check_email($_POST));

  //check if no errors
  if(empty($errors)){
    if ($_POST['password'] !== $_POST['confirm_password']) {
      $result = show_msg("Please enter the same confirm password as your new password.");
    }else {
      try {
        //check if the email exists in the database
        $query = "select email from users where email=:email";
        $statement = $db->prepare($query);
        $statement->execute([':email' => $_POST['email']]);
        if ($statement->rowCount() == 1) {
          // the user exists
          $hash_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
          //update the password
          $queryUpdate = "update users set password =:password where email=:email";
          $statement = $db->prepare($queryUpdate);
          $statement->execute([':password' => $hash_password, ':email' => $_POST['email']]);
          $result = show_msg("Password reset successful.", "pass");
        }else {
          $result = show_msg("The email address does not exist.");
        }
      } catch (PDOException $e) {

      }

    }
  }else {
    $result = show_msg("There are errors in the form.");
  }

}

?>

<h3>Password Reset Form</h3>
<?php if(isset($result)) echo $result; ?>
<?php if(!empty($errors)) echo show_errors($errors); ?>
<form action="" method="post">
  <table>
    <tr>
      <td>Email:</td><td><input type="email" name="email"> </td>
    </tr>
    <tr>
      <td>New Password:</td><td><input type="password" name="password"> </td>
    </tr>
    <tr>
      <td>Confirm Password:</td><td><input type="password" name="confirm_password"> </td>
    </tr>
    <tr>
      <td></td><td><input type="submit" name="resetBtn" value="Reset Password"> </td>
    </tr>
  </table>
</form>
<p><a href="login.php">Back</a> </p>

<?php include_once('private/shared/footer.php'); ?>
