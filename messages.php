<?php
session_start();

if(isset($_POST['User']) && !empty($_POST['User'])) {
  $user = filter_input(INPUT_POST, 'User', FILTER_DEFAULT);
  $user = trim($user);
  $_SESSION['User'] = filter_var($user, FILTER_SANITIZE_STRING);
} ?>

<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<title>Message Log</title>
<script src="utils.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
<link href='style.css' rel='stylesheet'>

<?php
if(!isset($_SESSION['User']) || empty($_SESSION['User'])) {
  echo "<style>.msg,.userid{ display: none; }#posted-msgs-container{ visibility:hidden; }</style>";
} ?>

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
?>
  back</button><br><br>
<?php }else{ ?>
  set username</button>
   <br>
   <br>
   <div class='sorry'>Sorry, you need to set a username to see<br> or post a message.</div>
<?php } ?>
  <div id="posted-msgs-container">
    <div id="posted-msgs"></div>
  </div>
  <br>
  <br>
<?php
if(isset($_SESSION['User']) && !empty($_SESSION['User'])){
?>
  <form method="post">
   <div class='user-input'>User: <?php echo $_SESSION['User']; ?> <span class='small user-input'>type message below:</span></div>
   <textarea id='msg-area' class='textarea'>
   </textarea>
   <br>
   <br>
   <div class='send-msg-div'>
    <input id="send-msg-btn" class='send-msg-btn' type='submit' value='send'>
   </div>
  </form>
  <br>
  <br>
<?php } ?>

<script>
  window.onload = function () {
    var app = {
      readmsgs : [],
      animationInProgress : false
    };
    var send_msg_btn = document.getElementById('send-msg-btn');
    var msg_area = document.getElementById('msg-area');
    var posted_msgs = document.getElementById('posted-msgs');
    var posted_msgs_container = document.getElementById('posted-msgs-container');

    if(msg_area && send_msg_btn){
      msg_area.focus();
      msg_area.value = '';
      updateMessages();
      send_msg_btn.addEventListener('click', postMessage);
    }

    routineUpdateMsgs(); //update messages pane every 10 seconds
    function routineUpdateMsgs() {
      updateMessages();
      TweenLite.delayedCall(10, routineUpdateMsgs);
    }

    function postMessage(e) {
      e.preventDefault();
      var http = new XMLHttpRequest();
      var params = "Msg="+msg_area.value;
      http.open("POST", "postmsg.php", true);
      http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          updateMessages();
          msg_area.focus();
          msg_area.value = '';
        }
      }
      http.send(params);
    }
    
    function updateMessages() {
      var http = new XMLHttpRequest();
      var cache_bust = new Date().getTime();
      http.open("GET", "msgs.html?randstr=" + cache_bust, true);
      http.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200 && app.animationInProgress == false) {
          posted_msgs.innerHTML = http.responseText;
          //check if read by client
          var lastmsg = getLastMsg();
          if(contains.call(app.readmsgs,lastmsg) == false){
            scrollMsgsToTop();
            animateByChar(getLastMsgChars());
            setLastMsg();
          }
        }
      }
      http.send();
    }
    
    function animateByChar(chars) {
      app.animationInProgress = true;
      for(var i = 0; i < chars.length; i++){
        TweenLite.from(chars[i], 0.1, { alpha:0, x:-20, delay:(i*0.025),
          onComplete: function (_i,_chars) {
            if(_i == _chars.length-1){
              app.animationInProgress = false;
            }
          },
          onCompleteParams: [i,chars]
        });
      }
    }
    
    function getLastMsgChars() {
      var last = document.getElementById(getLastMsg());
      var chars = last.getElementsByClassName("char");
      if(chars){
        return chars;
      }
    }

    function getLastMsg() {
      var allmsgs = document.getElementsByClassName("msg");
      var lastmsg = allmsgs[allmsgs.length-1];
      if(allmsgs && lastmsg){
        return lastmsg.id;
      }
    }

    function setLastMsg() {
      var last = getLastMsg();
      app.readmsgs.push(last);
    }

    function scrollMsgsToTop() {
      posted_msgs_container.scrollTop = posted_msgs_container.scrollHeight;
    }

  }
</script>
</body>
</html>