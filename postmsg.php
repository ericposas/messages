<?php

session_start();

if(isset($_POST['Msg']) && !empty($_POST['Msg'])) {
  $msg = filter_input(INPUT_POST, 'Msg', FILTER_DEFAULT);
  if(trim($msg) != '' && trim($_SESSION['User']) != ''){
    if (!file_put_contents('msgs.html', "<div class='msg'>" . $_SESSION['User'] . ": " . filter_var($msg, FILTER_SANITIZE_STRING) . "</div>" . "\n", FILE_APPEND | LOCK_EX)) {
      echo "Error: could not write to file.";
    }else{
      echo "OK";
    }
  }
}
