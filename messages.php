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
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
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
    echo "back</button><br><br>";
}else{
    echo "set username".
         "</button><br><br>".
         "<div class='sorry'>Sorry, you need to set a username to see<br> or post a message.</div>";
}?>
<iframe id="iframe" class="" style="display:block;" src="msgs.html" scrolling="no"></iframe>
  <script>
    var _iframe = document.getElementById('iframe');
    _iframe.onload = function () {
      _iframe.contentWindow.scrollTo( 0, 999999 );
    }
  </script>
<br>
<br>
<?php
if(isset($_SESSION['User']) && !empty($_SESSION['User'])){
    echo "<form method='post' action=" . $_SERVER['PHP_SELF'] . ">" .
         "<div class='user-input'>" . "User: " . $_SESSION['User'] . "<span class='small user-input'>type message below:</span></div>" .
            "<textarea id='msg-area' class='textarea' name='Msg'>" .
            "</textarea><br><br>" .
            "<div class='send-msg-div'><input class='send-msg-btn' type='submit' name='submit' value='send'></div>" .
         "</form>" .
         "<br>" .
         "<br>";
}?>

<script>
  window.onload = function () {
    var iframe = document.getElementById('iframe');
    document.getElementById('msg-area').focus();
    reloadSrc(1);
    function reloadSrc(delay) {
      function reloadThis(_delay) {
        iframe.src = (iframe.src == 'msgs.html' ? 'msgs.html?v1.1.1' : iframe.src);
        iframe.contentWindow.scrollTo(0, 999999);
        TweenLite.delayedCall(_delay, function () {
          reloadThis(_delay);
        });
      }
    }
  }
</script>