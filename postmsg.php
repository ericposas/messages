<?php

session_start();

if(isset($_POST['Msg']) && !empty($_POST['Msg'])) {
  $msg = filter_input(INPUT_POST, 'Msg', FILTER_DEFAULT);
  if(trim($msg) != '' && trim($_SESSION['User']) != ''){
    $cleanmsg = filter_var($msg, FILTER_SANITIZE_STRING);
    $cleanmsg_chars = str_split($cleanmsg);
    $cleanspans = "<div class='userid'>".$_SESSION['User'].": "."<span class='msg'>";
    for ($i = 0; $i < count($cleanmsg_chars); $i+=1){
      $cleanspans .= "<span class='char'>".$cleanmsg_chars[$i]."</span>";
    }
    $cleanspans .= "</span></div>"."\n";

    if(!file_put_contents("msgs.html", $cleanspans, FILE_APPEND | LOCK_EX)){
      echo "Error: could not write to file.";
    }else{
      echo "OK";
    }
  }
}
