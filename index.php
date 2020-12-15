<?php $title = 'Homepage'; ?>
<?php  include_once('private/shared/header.php'); ?>
<?php require_once('resource/session.php'); ?>

  <?php if(!isset($_SESSION['username'])): ?>
    <p>You are currently not signed in. <a href="login.php">Login </a> or <a href="signup.php">Sign up </a>Here.</p>
  <?php else: ?>
    <p>You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?> <a href="logout.php">Logout</a> </p>
<?php endif ?>
  <?php  include_once('private/shared/footer.php'); ?>
