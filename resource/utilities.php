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
 ?>
