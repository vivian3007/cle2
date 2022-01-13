<?php
/* collect the content of the database through database.php */
/** @var $db */
require_once "../includes/database.php";

/* show all the content in table reservations and the guests that match the reservations */
$queryReservations = "SELECT *, reservations.id as reservations_id, guests.id as guests_id FROM reservations INNER JOIN guests on reservations.guest_id = guests.id ORDER BY date, time";
$resultReservations = mysqli_query($db, $queryReservations)
or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryReservations);

/* save the content of the database in a new array */
$reservations = [];
while ($row = mysqli_fetch_assoc($resultReservations)) {
    $reservations[] = $row;
}

/* close connection to the database */
mysqli_close($db);
?>
<!doctype html>
<html lang="nl">
<head>
    <title>Reservations</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="../css/reservations.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&display=swap" rel="stylesheet">
</head>
<body>
<nav>
    <a id="logo" href="reservations.php"><img id="yashima-logo" src="../includes/yashima-logo.png" alt="logo"></a>
    <a class="nav-text">Reserveringen</a>
</nav>
<section>
<h1>Reserveringen</h1>
<table>
    <thead>
    <tr>
        <th>Datum</th>
        <th>Tijd</th>
        <th>Naam</th>
        <th>Tafelnummer</th>
        <th>Aantal personen</th>
        <th>Telefoonnummer</th>
        <th>Opmerkingen</th>
    </tr>
    </thead>
    <tbody>
    <!-- loop through the reservations with a foreach loop -->
    <?php foreach ($reservations as $reservation) { ?>
        <tr>
            <td><?= date('d-m-Y', strtotime($reservation['date'])) ?></td>
            <td><?php $time = substr($reservation['time'],0,-3);
                echo $time?></td>
            <td><?= $reservation['name'] ?></td>
            <td><?php if($reservation['table_number'] == 0) {
                    echo '';
                } else{
                echo $reservation['table_number'];
                }?></td>
            <td><?= $reservation['people'] ?></td>
            <td><?= $reservation['phone_number'] ?></td>
            <td><?php if($reservation['remarks'] ==! ''){
                        echo "*";
                    } else{
                        echo "";
                } ?></td>
            <td class="link"><a href="details.php?id=<?= $reservation['reservations_id']?>&name=<?= $reservation['guests_id'] ?>">Details</a></td>
            <td class="link"><a href="delete.php?id=<?= $reservation['reservations_id']?>&name=<?= $reservation['guests_id'] ?>">Verwijder</a></td>
            <td class="link"><a href="edit.php?id=<?= $reservation['reservations_id']?>&name=<?= $reservation['guests_id'] ?>">Wijzigen</a></td>
            <!--<td>
                <form action="" method="post" id="checkbox">
                    <div>
                        <input class="checkbox" type="checkbox" name="check" id="check">
                        <label for="check"></label>
                    </div>
                    <input type="submit">
                </form>
            </td>-->
        </tr>
    <?php } ?>
    </tbody>
</table>
    <a id="new-reservation" class="button" href="create.php">Maak een nieuwe reservering</a>
</section>
<footer>
    <div>
        <a class="logout" href="../staff/logout.php">Uitloggen</a>
    </div>
    <div>
        © 2018 – 2022 Restaurant Yashima
    </div>
</footer>
</body>
</html>

