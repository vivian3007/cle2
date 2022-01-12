<?php
/* collect the content of the database through database.php */
/** @var $db */
require_once "includes/database.php";

/* see if there's an id to get the details of one specific reservation */
if(isset($_GET['id'])) {
    $reservationId = mysqli_escape_string($db, $_GET['id']);
    $guestId = mysqli_escape_string($db, $_GET['name']);
} else{
    header('Location: reservations.php');
    exit;
}

/* delete the right reservation */

$queryDeleteReservation = "DELETE FROM reservations WHERE id = '$reservationId'";

$deleteReservation = mysqli_query($db, $queryDeleteReservation)
    or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryDeleteReservation);

/* delete the right guest */

$queryDeleteGuest = "DELETE FROM guests WHERE id = '$guestId'";

$deleteGuest = mysqli_query($db, $queryDeleteGuest)
or die('Error: ' . mysqli_error($db) . 'with query: ' . $queryDeleteGuest);


/* close connection to the database */
mysqli_close($db);

header('Location: reservations.php');
exit;
?>

