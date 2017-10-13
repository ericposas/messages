<?php

session_start();

if(!isset($_SESSION["msg_count"]) || empty($_SESSION["msg_count"])){
  $_SESSION["msg_count"] = 0;
}

if(isset($_POST['Msg']) && !empty($_POST['Msg'])) {
  $msg = filter_input(INPUT_POST, 'Msg', FILTER_DEFAULT);
  if(trim($msg) != '' && trim($_SESSION['User']) != ''){
    $_SESSION["msg_count"] += 1; #increment message count and place in msg id attr
    $_SESSION["msg_id"] = $_SESSION["msg_count"] . generateRandomString(4);
    #$cleanmsg = filter_var($msg, FILTER_SANITIZE_STRING);
    $msg_chars = str_split($msg);
    $spans = "<div class=\"userid\">".$_SESSION['User'].": "."<span id=\"".$_SESSION["msg_id"]."\" class='msg'>";
    for ($i = 0; $i < count($msg_chars); $i+=1){
      $spans .= "<span class='char'>".$msg_chars[$i]."</span>";
    }
    $spans .= "</span></div>"."\n";

    if(!file_put_contents("msgs.html", $spans, FILE_APPEND | LOCK_EX)){
      echo "Error: could not write to file.";
    }else{
      echo "OK";
    }
  }
}


function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

