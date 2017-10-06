<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Send Eric a Message!</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
        <link href='style.css' rel='stylesheet'>
    </head>
    <body>

    <?php
    session_start();

    if(isset($_POST['logout']) && !empty($_POST['logout'])){
        unset($_SESSION['User']);
    }
    if(isset($_SESSION['User']) && !empty($_SESSION['User'])){
    ?>
      <div class='user-input'>Current User: <?php echo $_SESSION['User']; ?></div>
      <form method='post' action=<?php echo $_SERVER['PHP_SELF']; ?>>
        <input class='user-input' type='submit' name='logout' value='log out username'>
      </form>
      <br>
      <form method="post" action="messages.php">
    <?php
    }else{
      ?>
      <form method="post" action="messages.php">
      <div class="user-input">Username:</div>
      <input class="user-input" id="username" type="text" name="User" value="">
      <br>
      <br>
      <input class="user-input" id="submit-index" type="submit" name="submit" value="Login">
      </form>
      <br>
    <?php }

    if(isset($_SESSION['User']) && !empty($_SESSION['User'])){
      echo "<button class='user-input' id=\"see-msgs-btn\">See Messages</button>";
    } ?>

    <script>
      window.onload = function () {
        var btn = document.getElementById('see-msgs-btn') || null;
        if (btn) {
          btn.addEventListener('click', function () {
            window.open('messages.php', '_self');
          });
        }
      };
    </script>

    </body>
</html>
