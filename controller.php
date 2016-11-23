<?php
//Authors: Chris Peterson and Beau Mejias-Brean
session_start();
require_once './DataBaseAdaptor.php';
$myDatabaseFunctions = new DatabaseAdaptor();

if (isset ( $_GET ['author'] ) && isset ( $_GET ['quote'] )) {
	$author = $_GET ['author'];
	$quote = $_GET ['quote'];
	$myDatabaseFunctions->addNewQuote ( $quote, $author );
	header ( "Location: ./index.php?mode=showQuotes" );
} elseif (isset ( $_GET ['action'] ) && isset ( $_GET ['ID'] )) {
	$action = $_GET ['action'];
	$ID = $_GET ['ID'];
	if ($action === 'increase') {
		$myDatabaseFunctions->raiseRating ( $ID );
	}
	if ($action === 'decrease') {
		$myDatabaseFunctions->lowerRating ( $ID );
	}
	if ($action === 'flag') {
		$myDatabaseFunctions->flag ( $ID );
	}
    header ( "Location: ./index.php?mode=showQuotes" );
} elseif (isset ($_GET['action'])) {
    $action = $_GET ['action'];
    if ($action === 'unflag') {
        $myDatabaseFunctions->unflag();
    }
    if ($action === 'logout') {
        session_unset();
        session_destroy();
    }
    header ( "Location: ./index.php?mode=showQuotes" );
}
/*
elseif (isset ($_GET['username']) && isset($_GET['pwd'])) {
    $user = $_GET['username'];
    $pass = $_GET['pwd'];

    if ($_GET['mode'] == "register") {
        if (!$myDatabaseFunctions->doesUserExist($user)) {
            $myDatabaseFunctions->addUser($user, $pass);
            $_SESSION['username'] = $user;
            header("Location: ./index.php?mode=showQuotes");
        } else {
            header("Location: ./index.php?mode=register");
        }
    } elseif ($_GET['mode'] == "login") {
        if ($myDatabaseFunctions->doesUserExist($user)) {
            if ($myDatabaseFunctions->isPasswordCorrect($user, $pass)) {
                $_SESSION['username'] = $user;
                header("Location: ./index.php?mode=showQuotes");
            } else {
                header("Location: ./index.php?mode=login");
            }
        } else {
            header("Location: ./index.php?mode=login");
        }
    }
}*/

?>