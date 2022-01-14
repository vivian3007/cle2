<?php
session_start();

/* check if the user had already logged in */
if(isset($_SESSION['loggedInUser'])) {
    $login = true;
} else {
    $login = false;
}

/* collect the content of the database through database.php */
/** @var $db */
require_once "../includes/database.php";

/* check if the form has been submitted and collect the data from the form */
if (isset($_POST['submit'])) {
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    /* create errors for every required field */
    $errors = [];
    if($email == '') {
        $errors['email'] = 'Voer een gebruikersnaam in';
    }
    if($password == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }

    /* check for errors */
    if(empty($errors)) {
        /* select the required data from table users */
        $query = "
            SELECT * 
            FROM users 
            WHERE email='$email'";
        $result = mysqli_query($db, $query);
        /* check if there's 1 row in the database with this emailadress */
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            /* check if the combination of username and password is correct */
            if (password_verify($password, $user['password'])) {
                $login = true;

                /* save the login info of the logged in user in a session */
                $_SESSION['loggedInUser'] = [
                    'email' => $user['email'],
                    'id' => $user['id'],
                    'admin' => $user['admin']
                ];

                /* if the user is admin --> send the user to the admin page, if not --> to the staff page */
                if($user['admin']){
                    header('location: ../admin/index.php');
                    exit;
                } else {
                    header('location: ../staff/index.php');
                    exit;
                }
                /* show error */
            } else {
                //error onjuiste inloggegevens
                $errors['loginFailed'] = 'De combinatie van email en wachtwoord is bij ons niet bekend';
            }
            /* show error */
        } else {
            //error onjuiste inloggegevens
            $errors['loginFailed'] = 'De combinatie van email en wachtwoord is bij ons niet bekend';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/login.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<nav>
    <a id="logo"><img id="yashima-logo" src="../includes/yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Inloggen</a>
</nav>
<section>
<h1>Inloggen</h1>
    <form action="" method="post">
        <div>
            <label for="email">Email</label><br>
            <input class="input" id="email" type="text" name="email" value="<?= $email ?? '' ?>"/>
            <span class="errors"><?= $errors['email'] ?? '' ?></span>
        </div>
        <div>
            <label for="password">Wachtwoord</label><br>
            <input class="input" id="password" type="password" name="password" />
            <span class="errors"><?= $errors['password'] ?? '' ?></span>
        </div>
        <div>
            <p class="errors"><?= $errors['loginFailed'] ?? '' ?></p>
            <input id="submit-button" class="button" type="submit" name="submit" value="Login"/>
        </div>
    </form>
</section>
<footer>
    <div>
        © 2018 – 2022 Restaurant Yashima
    </div>
</footer>
</body>
</html>
