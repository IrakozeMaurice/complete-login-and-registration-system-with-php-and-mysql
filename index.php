<?php $title = 'Homepage'; ?>
<?php require_once('resource/session.php'); ?>
<?php  include_once('shared/header.php'); ?>

<section class="jumbotron text-center">
  <h2 class="text-primary">User Authentication System</h2><hr><br>
  <?php if(!isset($_SESSION['username'])): ?>
    <p>You are currently not signed in. <a href="login.php">Login </a> or <a href="signup.php">Sign up </a>Here.</p>
  <?php else: ?>
    <p>You are logged in as <?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?> <a href="logout.php">Logout</a> </p>
<?php endif ?>
</section>

<?php  include_once('shared/footer.php'); ?>
