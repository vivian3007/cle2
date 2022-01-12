<?php
$starttime  = strtotime('17:00');
$endtime    = strtotime('19:30');
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

/* show the right reservation from the table reservations */
$queryReservations = "SELECT * FROM reservations WHERE id = ". $reservationId;
$resultReservations = mysqli_query($db, $queryReservations);
$reservation = mysqli_fetch_assoc($resultReservations);

/* show the right guest from the table guests */
$queryGuests = "SELECT * FROM guests WHERE id = ". $guestId;
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

$editGuest = $editReservation = '';

/* check if the form has been submitted and collect the data from the form */
if (isset($_POST['submit'])){
    $date = mysqli_escape_string($db, $_POST['date']);
    $people = mysqli_escape_string($db, $_POST['people']);
    $time = mysqli_escape_string($db, $_POST['time']);
    $table = mysqli_escape_string($db, $_POST['table']);
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
        $errors['name'] = "Vul aub een naam in";
    }
    if ($people == "") {
        $errors['people'] = "Vul aub het aantal personen in";
    }
    if ($phone == "") {
        $errors['phone'] = "Vul aub een telefoonnummer in";
    }

    /* if there are no errors --> update the reservation and guest table with the info from the form */
    if(empty($errors)) {
        $queryReservationsEdit = "
                UPDATE reservations 
                SET 
                        date = '$date', 
                        people = '$people', 
                        time = '$time', 
                        table_number = '$table', 
                        remarks = '$remarks' 
                WHERE id = '$reservationId'";

        $editReservation = mysqli_query($db, $queryReservationsEdit)
            or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryReservationsEdit);

        $queryGuestsEdit = "UPDATE guests SET name = '$name', phone_number = '$phone' WHERE id = '$guestId'";

        $editGuest = mysqli_query($db, $queryGuestsEdit)
            or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryGuestsEdit);
    }

    /* check if the new reservation has been added to the database */
    if ($editReservation && $editGuest) {
        $success = "De reservering is aangepast";
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
    <meta charset="UTF-8">
    <title>Edit</title>
    <link rel="stylesheet" type="text/css" href="edit.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<nav>
    <a id="logo"><img id="yashima-logo" src="../../yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Wijzigen</a>
</nav>
<section>
    <h1>Wijzigen - <?= $name ?></h1>
    <!-- show if the edit was a success -->
    <div class="success"><?php
    if (isset($errors['db'])) {
        echo $errors['db'];
    } elseif (isset($success)) {
        echo $success;
    }
        ?></div>
    <form action="" method="post" id="reservation">
        <div>
            <label for="table">Voeg een tafelnummer toe</label><br>
            <input class="input" type="text" name="table" id="table" value="<?= $table ?>">
        </div>
        <div>
            <label for="date">Datum*</label><br>
            <input class="input" type="date" id="date" name="date" value="<?= $date ?>" min="<?= date("d/m/Y"); ?>">
            <span class="error"><?= $errors['date'] ?? '' ?></span>
        </div>
        <div>
            <label for="people">Aantal personen*</label><br>
            <input class="input" type="number" name="people" id="people" max="8" value="<?= $people ?>">
            <span class="error"><?= $errors['people'] ?? '' ?></span>
        </div>
        <div>
            <label for="time">Tijd*</label><br>
            <?php
            while($starttime <= $endtime) {
                $label = date('H:i', $starttime);
            ?>
                <input type="radio" name="time" id="<?= $label ?>" value="<?= $label ?>" <?= $label == $time ? 'checked' : '' ?>>
                <label for="<?= $label ?>"><?= $label ?></label><br>

            <?php
                // tijd ophogen
                $starttime += 15 * 60;
            }
            ?>
            <span class="error"><?= $errors['time'] ?? '' ?></span>
        </div>
        <div>
            <label for="name">Voor- en achternaam*</label><br>
            <input class="input" type="text" name="name" id="name" value="<?= $name ?>">
            <span class="error"><?= $errors['name'] ?? '' ?></span>
        </div>
        <div>
            <label for="phone">Mobiele telefoonnummer*</label><br>
            <input class="input" type="tel" name="phone" id="phone" value="<?= $phone ?>">
            <span class="error"><?= $errors['phone'] ?? '' ?></span>
        </div>
        <div>
            <label for="remarks">Opmerkingen</label><br>
            <textarea class="input" name="remarks" id="remarks"><?= $remarks ?></textarea>
        </div>
        <div>
            <input id="submit-button" name="submit" type="submit" value="Wijzig">
            <a class="button" href="../../reservations.php">Terug</a>
        </div>
    </form>
</section>
<footer>
    <div>
        <a href="../../logout.php">Uitloggen</a>
    </div>
    <div>
        © 2018 – 2022 Restaurant Yashima
    </div>
</footer>
</body>
</html>
