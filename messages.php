<?php
session_start();

if(isset($_POST['User']) && !empty($_POST['User'])) {
    $user = filter_input(INPUT_POST, 'User', FILTER_DEFAULT);
    $user = trim($user);
    $_SESSION['User'] = filter_var($user, FILTER_SANITIZE_STRING);
}
if(isset($_POST['Msg']) && !empty($_POST['Msg'])) {
    $msg = filter_input(INPUT_POST, 'Msg', FILTER_DEFAULT);
    if(trim($msg) != '' && trim($_SESSION['User']) != ''){
        if (!file_put_contents('msgs.html', "<div class='msg'>" . $_SESSION['User'] . ": " . filter_var($msg, FILTER_SANITIZE_STRING) . "</div>" . "\n", FILE_APPEND | LOCK_EX)) {
            echo "<div> Error: could not write to file. </div>";
        }
    }
}?>

<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Message Log</title>
<script src="gs.js"></script>
<link href='style.css' rel='stylesheet'>

<?php
if(!isset($_SESSION['User']) || empty($_SESSION['User'])) {
    echo "<style>.msg{ display: none; }#iframe,#preloaded-iframe{ visibility:hidden; }</style>";
}?>

</head>
<body>
<button id='swap-btn'>
<script>
    var swap_btn = document.getElementById('swap-btn');
    swap_btn.addEventListener('click', function () {
        window.open('index.php', '_self');
    });
</script>
<?php
if(isset($_SESSION['User']) && !empty($_SESSION['User'])) {
    echo "<- back";
    echo "</button><br><br>";
}else{
    echo "set username".
         "</button><br><br>".
         "<div class='sorry'>Sorry, you need to set a username to see<br> or post a message.</div>";
}?>
<iframe id="preloaded-iframe" style="display:none;" src="msgs.html"></iframe>
<iframe id="iframe" style="display:block;" src="msgs.html"></iframe>
<script>
  document.getElementById("preloaded-iframe").onload = function () { this.contentWindow.scrollTo(0, 99999) };
  document.getElementById("iframe").onload = function () { this.contentWindow.scrollTo(0, 99999) };
</script>
<br>
<br>
<?php
if(isset($_SESSION['User']) && !empty($_SESSION['User'])){
    echo "<form method='post' action=" . $_SERVER['PHP_SELF'] . ">" .
         "<div class='user-input'>" . "User: " . $_SESSION['User'] . "<span class='small user-input'>type message below:</span></div>" .
            "<textarea class='textarea' name='Msg'>" .
            "</textarea><br><br>" .
            "<div class='send-msg-div'><input class='send-msg-btn' type='submit' name='submit' value='send'></div>" .
         "</form>" .
         "<br>" .
         "<br>";
}?>

<script>
  var preloaded = document.getElementById('preloaded-iframe');
  var iframe = document.getElementById('iframe');
  var everyNSec = 5;
  setInterval(swapSrc, (everyNSec * 1000));
  function swapSrc() {
    function swap() {
      if (preloaded.style.display == 'none') {
        iframe.src = preloaded.src;
        iframe.style.display = 'none';
        preloaded.style.display = 'block';
      } else {
        preloaded.src = iframe.src;
        iframe.style.display = 'block';
        preloaded.style.display = 'none';
      }
    }
    swap();
    TweenLite.delayedCall(0.5, swap);
  }
</script>