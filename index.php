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
        echo "<div class='user-input'>Current User: ".$_SESSION['User']."</div>".
             "<form method='post' action=" . $_SERVER['PHP_SELF'] . ">".
                "<input class='user-input' type='submit' name='logout' value='log out username'>".
             "</form>".
             "<br>";
        echo "<form method=\"post\" action=\"messages.php\">";
    }else{
        echo "<form method=\"post\" action=\"messages.php\">".
               "<div class='user-input'>Username:</div>".
               "<input class='user-input' id=\"username\" type=\"text\" name=\"User\" value=\"\">";
    }
    ?>

        <div class="user-input">Message:</div>
        <textarea class="user-input" id="msg" type="text" name="Msg" value=""></textarea>
        <div class="user-input" id="after-msg">please enter a message.</div>
        <br>
        <br>
        <input class="user-input" id="submit-index" type="submit" name="submit" value="Send">
    </form>
    <script>
        var form = document.getElementsByTagName('form')[0];
        var submit = document.getElementById('submit-index');
        var msg = document.getElementById('msg');
        var after = document.getElementById('after-msg');
        msg.addEventListener('input', handleMsgInput);
        function handleMsgInput() {
            if(msg.value != ''){
                submit.style.display = 'block';
                after.style.display = 'none';
                msg.classList.add('textarea');
                msg.classList.remove('user-input');
            }else{
                submit.style.display = 'none';
                after.style.display = 'block';
                if(msg.classList.contains('textarea'))
                    msg.classList.remove('textarea');
                    msg.classList.add('user-input');
            }
        }
        //run once.
        handleMsgInput();
    </script>

    <br>
    <?php
        if(isset($_SESSION['User']) && !empty($_SESSION['User'])){
            echo "<button class='user-input' id=\"see-msgs-btn\">See Messages</button>";
        }
    ?>
    <script>
        var btn = document.getElementById('see-msgs-btn') || null;
        if(btn){
            btn.addEventListener('click', function () {
                window.open('messages.php', '_self');
            });
        }
    </script>

    </body>
</html>
