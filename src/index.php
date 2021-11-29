<?php
session_start();
$num1 = rand(3, 13);
$num2 = rand(15, 80);
$t = rand(1, 3);
$answer = 0;
if ($t == 1) {
    $answer = $num1 + $num2;
    $captcha = $num1 . ' plus ' . $num2;
} else if ($t == 2) {
    $answer = $num1 * $num2;
    $captcha = $num1 . 'x' . $num2;
} else if ($t == 3) {
    $answer = $num2 - $num1;
    $captcha = $num2 . ' minus ' . $num1;
}
$_SESSION['captcha-answer-real-contact-form'] = $answer;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
    <style>
        * {
            font-family: '.Aqua Kana', '.AppleSystemUIFont', 'system-ui', 'Roboto', 'Helvetica New', 'Helvetica Neue', 'Helvetica', sans-serif;
            text-align: center;
            vertical-align: middle;
        }
        body {
            background:
                    radial-gradient(black 15%, transparent 16%) 0 0,
                    radial-gradient(black 15%, transparent 16%) 8px 8px,
                    radial-gradient(rgba(255,255,255,.1) 15%, transparent 20%) 0 1px,
                    radial-gradient(rgba(255,255,255,.1) 15%, transparent 20%) 8px 9px;
            background-color:#282828;
            background-size:16px 16px;
            overflow: hidden;
        }
        .card {
            width: 85%;
            background-color: white;
            margin: auto;
            vertical-align: middle;
            text-align: center;
            padding: 15px;
            height: 85vh;
            margin-top: 5vh;
            overflow: auto;
            box-shadow: 2px 2px 3px 3px black;
            border-radius: 15px;
        }

        input, textarea {
            resize: none;
            width: 25%;
            outline: none;
            border: 2px solid #bdbdbd;
            background-color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.25s;
        }
        input:focus, textarea:focus {
            border: 2px solid #4dc4e5;
            cursor: text;
            width: 95%;
            font-size: 1.5em;
            transition: 0.25s;
        }

        textarea {
            height: 5vh;
        }

        textarea:focus {
            height: 35%;
        }

        button {
            background: #606060;
            color: white;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            padding: 5px;
            width: 75%;
            font-size: 1.5em;
            transition: 0.25s;
        }

        button:hover {
            background: #4dc4e5;
        }

        button:active {
            background: #23758c;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Contact</h1>
        <form action="send.php" method="post">
            <p>Your Email Address:</p>
            <input type="email" name="email" required placeholder="example@example.com">
            <p>Your Full Name:</p>
            <p><small>Please enter your full name (First and Last). If you do not enter your full name (First and Last), this email will be automatically marked as spam.</small></p>
            <input type="text" name="name" required placeholder="John Smith">
            <p>Subject of Message:</p>
            <input type="text" name="subject" required placeholder="Subject">
            <p>Message:</p>
            <textarea name="message" cols="30" rows="10" placeholder="It was a dark and stormy night..."></textarea>
            <p>What is <b><?=$captcha?></b>?</p>
            <input type="number" name="captcha" required>
            <input type="hidden" hidden style="display: none;" name="phone_number" id="phone_number">
            <p>Submit:</p>
            <button type="submit">Send</button>
        </form>
        <p>Please note that any spam that may be sent may be publicly posted with your email and IP address.</p>
    </div>
</form>
</body>
</html>
