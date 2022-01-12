<?php

if(isset($_POST['submit'])) {

    /* collect the content of the database through database.php */
    /** @var $db */
    require_once "includes/database.php";

    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    $errors = [];
    if($email == '') {
        $errors['email'] = 'Voer een gebruikersnaam in';
    }
    if($password == '') {
        $errors['password'] = 'Voer een wachtwoord in';
    }

    if(empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

        $result = mysqli_query($db, $query)
        or die('Db Error: '.mysqli_error($db).' with query: '.$query);

        if ($result) {
            header('Location: login.php');
            exit;
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
    <link rel="stylesheet" type="text/css" href="css/login.css"/>
    <title>Registreren</title>
</head>
<body>
<nav>
    <a id="logo"><img id="yashima-logo" src="yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Registreren</a>
</nav>
<section>
<h1>Registeren</h1>
<form action="" method="post">
    <div class="data-field">
        <label for="email">Email</label><br>
        <input class="input" id="email" type="text" name="email" value="<?= $email ?? '' ?>"/>
        <span class="errors"><?= $errors['email'] ?? '' ?></span>
    </div>
    <div class="data-field">
        <label for="password">Wachtwoord</label><br>
        <input class="input" id="password" type="password" name="password" value="<?= $password ?? '' ?>"/>
        <span class="errors"><?= $errors['password'] ?? '' ?></span>
    </div>
    <div class="data-submit">
        <input class="button" id="submit-button" type="submit" name="submit" value="Registreren"/>
        <a class="button" href="staff/login.php">Inloggen</a>
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
