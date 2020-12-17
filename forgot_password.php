<?php

$title = "Password Reset";
include_once('shared/header.php');
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

          //call sweetalert
          $result = "<script type=\"text/javascript\">
          swal({
              title: \"Updated\",
              text: \"Password reset successful.\",
              type: \"success\",
              confirmButtonText: \"Thank you\" });
          </script>";
          // $result = show_msg("Password reset successful.", "pass");
        }else {

          //call sweetalert
          $result = "<script type=\"text/javascript\">
          swal({
              title: \"Oops\",
              text: \"The email address does not exist.\",
              type: \"error\",
              confirmButtonText: \"Ok\" });
          </script>";
          // $result = show_msg("The email address does not exist.");
        }
      } catch (PDOException $e) {

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
  <h3>Password reset Form</h3><br>
  <form action="forgot_password.php" method="post">
    <div class="form-group">
      <label for="mail">Email</label>
      <input type="email" class="form-control" id="mail" name="email" placeholder="Enter your Email">
    </div>
    <div class="form-group">
      <label for="new_pass">New Password</label>
      <input type="password" class="form-control" id="new_pass" name="password" placeholder="Enter new Password">
    </div>
    <div class="form-group">
      <label for="confirm_pass">Confirm Password</label>
      <input type="password" class="form-control" id="confirm_pass" name="confirm_password" placeholder="Re-enter Password">
    </div>
    <button type="submit" class="btn btn-primary pull-right" name="resetBtn">Reset Password</button>
  </form><br><br>
    <p> <a href="login.php" class="btn btn-default">Back</a> </p>
</section>

<?php include_once('shared/footer.php'); ?>
