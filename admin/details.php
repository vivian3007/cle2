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

/* see if there's an id to get the details of one specific reservation */
if(isset($_GET['id'])) {
    $reservationId = mysqli_escape_string($db, $_GET['id']);
    $guestId = mysqli_escape_string($db, $_GET['name']);
} else{
    header('Location: index.php');
    exit;
}

/* show the right reservation from the table reservations */
$queryReservations = "
    SELECT * 
    FROM reservations 
    WHERE id = ". $reservationId;
$resultReservations = mysqli_query($db, $queryReservations);
$reservation = mysqli_fetch_assoc($resultReservations);

/* show the right guest from the table guests */
$queryGuests = "
    SELECT * 
    FROM guests 
    WHERE id = ". $guestId;
$resultGuests = mysqli_query($db, $queryGuests);
$guest = mysqli_fetch_assoc($resultGuests);

/* save all the content from the database in variables */
$date = mysqli_escape_string($db, $reservation['date']);
$time = mysqli_escape_string($db, substr($reservation['time'], 0, -3));
$table = mysqli_escape_string($db, $reservation['table_number']);
$name = mysqli_escape_string($db, $guest['name']);
$people = mysqli_escape_string($db, $reservation['people']);
$phone = mysqli_escape_string($db, $guest['phone_number']);
$remarks = mysqli_escape_string($db, $reservation['remarks']);

/* close connection to the database */
mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <title>Details</title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/details.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<nav>
    <a id="logo"><img id="yashima-logo" src="../includes/yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Details</a>
</nav>
<section>
    <!--show all the content stored in the variables-->
<h1>Details - <?= htmlentities($name) ?></h1>
<div>
    <ul>
        <li>Datum: <?= htmlentities($date) ?></li>
        <li>Tijd: <?= htmlentities($time) ?></li>
        <li>Tafelnummer: <?php if($table == 0) {
                echo '';
            } else{
                echo htmlentities($table);
            } ?></li>
        <li>Naam: <?= htmlentities($name) ?></li>
        <li>Aantal personen: <?= htmlentities($people) ?></li>
        <li>Telefoonnummer: <?= htmlentities($phone) ?></li>
        <li>Opmerkingen: <?= htmlentities($remarks) ?></li>
    </ul>
</div>
    <a class="button" href="index.php">Terug</a>
</section>
<footer>
    <div>
        <a class="logout" href="../login/logout.php">Uitloggen</a>
        <span>|</span>
        <a class="logout" href="register.php">Registreren</a>
    </div>
    <div>
        ?? 2018 ??? 2022 Restaurant Yashima
    </div>
</footer>
</body>
</html>

