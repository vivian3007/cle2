<?php
/* collect the content of the database through database.php */
/** @var $db */
require_once "../../includes/database.php";

/* see if there's an id to get the details of one specific reservation */
if(isset($_GET['id'])) {
    $reservationId = mysqli_escape_string($db, $_GET['id']);
    $guestId = mysqli_escape_string($db, $_GET['name']);
} else{
    header('Location: reservations.php');
    exit;
}

/* delete the right reservation */
if(isset($_POST['submit'])) {
    $queryDeleteReservation = "DELETE FROM reservations WHERE id = '$reservationId'";

    $deleteReservation = mysqli_query($db, $queryDeleteReservation)
    or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryDeleteReservation);

    /* delete the right guest */

    $queryDeleteGuest = "DELETE FROM guests WHERE id = '$guestId'";

    $deleteGuest = mysqli_query($db, $queryDeleteGuest)
    or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryDeleteGuest);

    header('Location: reservations.php');
    exit;
}

/* close connection to the database */
mysqli_close($db);
?>

<!doctype html>
<html lang="nl">
<head>
    <title>Reservations</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="../../css/reservations.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<nav>
    <a id="logo"><img id="yashima-logo" src="../../includes/yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Verwijderen</a>
</nav>
<section class="delete">
    <form action="" method="post" id="reservation">
        <div>
            <p>Deze reservering verwijderen?</p>
            <input class="button" id="submit-button" name="submit" type="submit" value="Ja">
            <a class="button" href="reservations.php">Terug</a>
        </div>
</section>
<footer>
    <div>
        <a class="logout" href="../logout.php">Uitloggen</a>
    </div>
    <div>
        © 2018 – 2022 Restaurant Yashima
    </div>
</footer>
</body>
</html>


