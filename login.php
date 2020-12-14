<?php $title = 'Login Page'; ?>
    <?php include_once('private/shared/header.php'); ?>
    <h3>Login Form</h3>
    
    <form action="login.php" method="post">
      <table>
        <tr>
          <td>Username:</td><td> <input type="text" name="username" value=""> </td>
        </tr>
        <tr>
          <td>Password:</td><td> <input type="password" name="password" value=""> </td>
        </tr>
        <tr>
          <td></td><td> <input style="float: right;" type="submit" value="Signin"> </td>
        </tr>
      </table>
    </form>
    <p> <a href="index.php">Back</a> </p>
    <?php include_once('private/shared/footer.php'); ?>
