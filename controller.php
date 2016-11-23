<?php
// This file contains a bridge between the view and the model and redirects back to the proper page
// with after processing whatever form this codew absorbs. This is the C in MVC, the Controller.
//
// Authors: Rick Mercer and Hassanain Jamal
// 
// TODO: Add control the new expected behavior to
// register
// log in
// flag one quote
// unflag all quotes
// log out
//
session_start();
require_once './DataBaseAdaptor.php';
/*
$pwd = $_POST['pwd'];
$hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);

function password_verify($pwd, $hash){
    $pwd = $_POST['pwd'];
    $hash = password_hash($pwd, PASSWORD_DEFAULT);

    echo password_verify('A' . $pwd, $hash) . PHP_EOL;
    echo password_verify($pwd, $hash) . PHP_EOL;
}
*/
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
}elseif (isset ($_GET['username']) && isset($_GET['pwd'])) {
    echo "test";
    $user = $_GET['username'];
    $pass = $_GET['pwd'];

    if ($_GET['mode'] == "register") {
        if (!$myDatabaseFunctions->doesUserExist($user)) {
            $myDatabaseFunctions->addUser($user, $pass);
            $_SESSION['username'] = $user;
            $_SESSION['password'] = $pass;
            header("Location: ./index.php?mode=showQuotes");
        } else {
            header("Location: ./index.php?mode=register");
        }
    } elseif ($_GET['mode'] == "login") {
        if ($myDatabaseFunctions->doesUserExist($user)) {
            if ($myDatabaseFunctions->isPasswordCorrect($user, $pass)) {
                $_SESSION['username'] = $user;
                $_SESSION['password'] = $pass;
                header("Location: ./index.php?mode=showQuotes");
            } else {
                header("Location: ./index.php?mode=login");
            }
        } else {
            header("Location: ./index.php?mode=login");
        }
    }
}

?>