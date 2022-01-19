<?php
session_start();

/* check if you may visit this page */
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: ../login/index.php");
    exit;
}
if(($_SESSION['loggedInUser']['admin'] == 0)){
    header("Location: ../login/index.php");
    exit;
}

/* collect the content of the database through database.php */
/** @var $db */
require_once "../includes/database.php";

/* define the variables from the form */
$name = $people = $phone = $remarks = $date = $time = '';
$newReservation = $newGuest = '';
$starttime  = strtotime('17:00');
$endtime    = strtotime('19:30');

/* check if the form has been submitted and collect the data from the form */
if (isset($_POST['submit'])) {
    $date = mysqli_escape_string($db, $_POST['date']);
    $people = mysqli_escape_string($db, $_POST['people']);
    $time = mysqli_escape_string($db, $_POST['time']);
    $name = mysqli_escape_string($db, $_POST['name']);
    $phone = mysqli_escape_string($db, $_POST['phone']);
    $remarks = mysqli_escape_string($db, $_POST['remarks']);

    /* create an error for every required field */
    $errors = [];
    if ($date == "") {
        $errors['date'] = "Vul aub een datum in";
    }
    if ($time == "") {
        $errors['time'] = "Klik aub een tijd aan";
    }
    if ($name == "") {
        $errors['name'] = "Vul aub uw naam in";
    }
    if ($people == "") {
        $errors['people'] = "Vul aub het aantal personen in";
    }
    if ($phone == "") {
        $errors['phone'] = "Vul aub uw telefoonnummer in";
    }

    /* add the required data to the database */
    if (empty($errors)) {
        $queryGuests = "
            INSERT INTO guests (name, phone_number)
            VALUES ('$name', '$phone')";

        $newGuest = mysqli_query($db, $queryGuests)
        or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryGuests);

        $guest_id = mysqli_insert_id($db);

        $queryReservation = "
            INSERT INTO reservations (date, time, people, guest_id, remarks)
            VALUES ('$date', '$time', '$people', '$guest_id', '$remarks')";

        $newReservation = mysqli_query($db, $queryReservation)
        or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryReservation);
    }

    /* check if the new reservation has been added to the database */
    if ($newReservation && $newGuest) {
        $success = "De nieuwe reservering is toevoegd.";
        $name = $people = $phone = $remarks = $date = $time = '';
        header('location: ../admin/index.php');
        exit;
    } else {
        $errors['db'] = mysqli_error($db);
    }
}
/* close connection to the database */
mysqli_close($db);
?>

<!doctype html>
<html lang="nl">
<head>
    <title>Create</title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/create.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<nav>
    <a id="logo"><img id="yashima-logo" src="../includes/yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Wijzigen</a>
</nav>
<body>
<section>
    <h1>Reserveren</h1>
    <!-- show errors if necessary -->
    <div><?php
        if (isset($errors['db'])) {
            echo $errors['db'];
        } else {
            echo '';
        }
        ?></div>
    <form action="" method="post" id="reservation">
        <div>
            <label for="date">Kies een datum*</label><br>
            <input class="input" type="date" id="date" name="date" value="<?= htmlentities($date) ?>" min="<?= date("d/m/Y"); ?>">
            <span class="error"><?= $errors['date'] ?? '' ?></span>
        </div>
        <div>
            <label for="people">Aantal personen* </label><br>
            <p>Bij meer dan 8 personen graag even bellen naar 078–303 8421.</p>
            <input class="input" type="number" name="people" id="people" max="8" value="<?= htmlentities($people) ?>">
            <span class="error"><?= $errors['people'] ?? '' ?></span>
        </div>
        <div>
            <label for="time">Tijd*</label><br>

            <!--show the timeslots-->
            <?php
            while($starttime <= $endtime) {
                $label = date('H:i', $starttime);
                ?>
                <input type="radio" name="time" id="<?= $label ?>" value="<?= $label ?>" <?= $label == $time ? 'checked' : '' ?>>
                <label for="<?= $label ?>"><?= $label ?></label><br>

                <?php
                /*add 15 minutes*/
                $starttime += 15 * 60;
            }
            ?>
            <span class="error"><?= $errors['time'] ?? '' ?></span>
        </div>
        <div>
            <label for="name">Voor- en achternaam* </label><br>
            <input class="input" type="text" name="name" id="name" value="<?= htmlentities($name) ?>">
            <span class="error"><?= $errors['name'] ?? '' ?></span>
        </div>
        <div>
            <label for="phone">Mobiele telefoonnummer* </label><br>
            <input class="input" type="tel" name="phone" id="phone" value="<?= htmlentities($phone) ?>">
            <span class="error"><?= $errors['phone'] ?? '' ?></span>
        </div>
        <div>
            <label for="remarks">Opmerkingen </label><br>
            <textarea class="input" name="remarks" id="remarks"><?= htmlentities($remarks) ?></textarea>
        </div>
        <div>
            <input class="button" id="submit-button" name="submit" type="submit" value="Reserveer">
            <a class="button" href="index.php">Terug</a>
        </div>
    </form>
</section>
<footer>
    <div>
        <a class="logout" href="../login/logout.php">Uitloggen</a>
        <span>|</span>
        <a class="logout" href="register.php">Registreren</a>
    </div>
    <div>
        © 2018 – 2022 Restaurant Yashima
    </div>
</footer>
</body>
</html>

