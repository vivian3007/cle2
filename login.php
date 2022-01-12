<?php
session_start();

if(isset($_SESSION['loggedInUser'])) {
    $login = true;
} else {
    $login = false;
}

/* collect the content of the database through database.php */
/** @var $db */
require_once "includes/database.php";

if (isset($_POST['submit'])) {
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    $errors = [];
    if($email == '') {
        $errors['email'] = 'Voer een gebruikersnaam in';
    }
    if($password == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }

    if(empty($errors))
    {
        //Get record from DB based on first name
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($db, $query);
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
//            echo $user['password']; exit;
//            var_dump(password_verify($password, $user['password']));exit;
//            print_r($user);exit;
            if (password_verify($password, $user['password'])) {
                $login = true;

                $_SESSION['loggedInUser'] = [
                    'email' => $user['email'],
                    'id' => $user['id']
                ];

                header('location: reservations.php');
                exit;
            } else {
                //error onjuiste inloggegevens
                $errors['loginFailed'] = 'De combinatie van email en wachtwoord is bij ons niet bekend';
            }
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
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="login.css"/>
    <title>Login</title>
</head>
<body>
<nav>
    <a id="logo"><img id="yashima-logo" src="yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Inloggen</a>
</nav>
<section>
<h1>Inloggen</h1>
<?php if ($login) { ?>
    <p>Je bent ingelogd!</p>
    <p><a class="button" href="logout.php">Uitloggen</a> / <a class="button" href="reservations.php">Reserveringslijst</a></p>
<?php } else { ?>
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
            <a class="button" href="register.php">Registreren</a>
        </div>
    </form>
<?php } ?>
</section>
<footer>
    <div>
        <a href="logout.php">Uitloggen</a>
    </div>
    <div>
        © 2018 – 2022 Restaurant Yashima
    </div>
</footer>
</body>
</html>
