<?php

function check_empty_fields($required_fields){
  $form_errors = [];
  foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] == NULL) {
      $form_errors[] = $field . ' is required';
    }
  }
  return $form_errors;
}

function check_min_length($fields){
  $form_errors = [];
  foreach ($fields as $field => $min) {
    if (strlen(trim($_POST[$field])) < $min) {
      $form_errors[] = $field . ' must be at least ' . $min . ' characters';
    }
  }
  return $form_errors;
}

function check_email($data){
  $form_errors = [];
  $key = 'email';
  if (array_key_exists($key, $data)) {
    if ($_POST[$key] != null) {
      $key = filter_var($key, FILTER_SANITIZE_EMAIL);
      if (filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false) {
        $form_errors[] = $key . ' is not a valid email';
      }
    }
  }
  return $form_errors;
}

function show_errors($form_errors){
  $errors = "<p><ul style='color:red;'>";
  foreach ($form_errors as $err) {
    $errors .= "<li>{$err}</li>";
  }
  $errors .= "</ul><p>";
  return $errors;
}

function show_msg($message,$passOrFail = 'fail'){
  if ($passOrFail === 'pass') {
    $result = "<div class='alert alert-success'>{$message}</p>";
  }else {
    $result = "<div class='alert alert-danger'>{$message}</p>";
  }
  return $result;
}

function redirect_to($url){
  header("Location: " . $url);
}

function check_duplicate_entries($table, $col_name, $value, $db){
  try {
    $query = "select * from " . $table . " where " . $col_name . "=:col_name";
    $statement = $db->prepare($query);
    $statement->execute([':col_name' => $value]);
    if($row = $statement->fetch()){
      return true;
    }
    return false;
  } catch (PDOException $e) {
    //handle exception
  }
}

function rememberMe($user_id){
  $encryptCookieData = base64_encode("jaSDFase438H8dHeu4e2K{$user_id}");
  //cookie set expire in 30 days
  setcookie("rememberUserCookie", $encryptCookieData, time()+60*24*100, "/");
}

function isCookieValid($db){
  $isValid = false;
  if (isset($_COOKIE['rememberUserCookie'])) {
    // decode cookie and extract user ID
    $decryptCookieData = base64_decode($_COOKIE['rememberUserCookie']);
    $userID = explode("jaSDFase438H8dHeu4e2K", $decryptCookieData)[1];
    //check if id exists in the database
    $query = "select * from users where id = :id";
    $statement = $db->prepare($query);
    $statement->execute([':id' => $userID]);

    if ($row = $statement->fetch()) {
      //id exists
      $id = $row['id'];
      $username = $row['username'];
      //create session for the user
      $_SESSION['id'] = $id;
      $_SESSION['username'] = $username;
      $isValid = true;
    }else {
      $isValid = false;
      signout();
    }
  }
  return $isValid;
}

function signout(){
  unset($_SESSION['username']);
  unset($_SESSION['id']);
  if (isset($_COOKIE['rememberUserCookie'])) {
    unset($_COOKIE['rememberUserCookie']);
    setcookie('rememberUserCookie', null, -1, '/');
  }
  session_destroy();
  session_regenerate_id(true);
  redirect_to('index.php');
}

function guard(){
  $isValid = true;
  $inactive = 60 * 10;  //10 min
  $fingerprint = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

  if (isset($_SESSION['$fingerprint']) && $_SESSION['$fingerprint'] != $fingerprint) {
    $isValid = false;
    signout();
  }else if ((isset($_SESSION['last_active']) && (time() - $_SESSION['last_active']) > $inactive) && $_SESSION['username']) {
    $isValid = false;
    signout();
  }else {
    $_SESSION['last_active'] = time();
  }
  return $isValid;
}
 ?>
