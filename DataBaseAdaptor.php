 <?php
	// Quotes Enhanced: a Dynamic Website that is Part 1 of a final project 
	// as a final project, except there is no AJAX in this example.
	//
	// Author: Rick Mercer and Hassanain Jamal
	//
	// TODO: Handle the two new forms for 
	// registering 
	// logging in
	// flagging one quote
	// unflagging all quotes
	// logging out
	//
	// CREATE DATABASE quotes;
	// USE quotes;
	// CREATE TABLE users(id int(11) NOT NULL auto_increment, username varchar(64) NOT NULL default '', password varchar(255) NOT NULL default '', PRIMARY KEY (id), UNIQUE KEY username (username));
	// CREATE TABLE quote(id bigint(20) NOT NULL AUTO_INCREMENT, quote varchar(255) NOT NULL default '', author varchar(64) NOT NULL default '', points bigint, dateadded varchar(255), isflagged boolean, PRIMARY KEY (id));
	class DatabaseAdaptor {
		// The instance variable used in every one of the functions in class DatbaseAdaptor
		private $DB;
		// Make a connection to an existing data based named 'quotes' that has
		// table quote. In this assignment you will also need a new table named 'users'
		public function __construct() {
			$db = 'mysql:dbname=quotes;host=127.0.0.1';
			$user = 'root';
			$password = '';
			
			try {
				$this->DB = new PDO ( $db, $user, $password );
				$this->DB->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			} catch ( PDOException $e ) {
				echo ('Error establishing Connection');
				exit ();
			}
		}
		
		// Return all quote records as an associative array.
		// Example code to show id and flagged columns of all records:
		// $myDatabaseFunctions = new DatabaseAdaptor();
		// $array = $myDatabaseFunctions->getQuotesAsArray();
		// foreach($array as $record) {
		// echo $record['id'] . ' ' . $record['flagged'] . PHP_EOL;
		// }
		//
		public function getQuotesAsArray() {
			// possible values of flagged are 't', 'f';
			$stmt = $this->DB->prepare ( "SELECT * FROM quote WHERE isflagged=0 ORDER BY points DESC, dateadded" );
			$stmt->execute ();
			return $stmt->fetchAll ( PDO::FETCH_ASSOC );
		}

		public function getUsersAsArray() {
			$stmt = $this->DB->prepare("SELECT * FROM users");
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		
		// Insert a new quote into the database
		public function addNewQuote($quote, $author) {
			$stmt = $this->DB->prepare ( "INSERT INTO quote (dateadded, quote, author, points, isflagged ) values(now(), :quote, :author, 0, 0)" );
			$stmt->bindParam ( 'quote', $quote );
			$stmt->bindParam ( 'author', $author );
			$stmt->execute ();
		}
		
		// Raise the rating of the quote with the given $ID by 1
		public function raiseRating($ID) {
			$stmt = $this->DB->prepare ( "UPDATE quote SET points=points+1 WHERE id= :ID" );
			$stmt->bindParam ( 'ID', $ID );
			$stmt->execute ();
		}
		
		// Lower the rating of the quote with the given $ID by 1
		public function lowerRating($ID) {
			$stmt = $this->DB->prepare ( "UPDATE quote SET points=points-1 WHERE id= :ID" );
			$stmt->bindParam ( 'ID', $ID );
			$stmt->execute ();
		}

		public function addUser($user, $password) {
			if (!$this->doesUserExist($user)) {
				$stmt = $this->DB->prepare("INSERT INTO users (username, password) values(:user, :password)");
				$stmt->bindParam('user', $user);
				$stmt->bindParam('password', $password);
				$stmt->execute();
			} else {
				return "user already exists";
			}
		}

		public function isPasswordCorrect($user, $pass) {
			if ($this->doesUserExist($user)) {
				$array = $this->getUsersAsArray();
				foreach($array as $record) {
					if ($record['username'] == $user && $record['password'] == $pass) {
						return true;
					}
				}
			}

			return false;
		}

		public function doesUserExist($user) {
			$users = $this->getUsersAsArray();
			foreach($users as $record) {
				if ($record['username'] == $user) {
					return true;
				}
			}
			return false;
		}

		public function flag($ID) {
			$stmt = $this->DB->prepare ( "UPDATE quote SET isflagged=1 WHERE id= :ID" );
			$stmt->bindParam ( 'ID', $ID );
			$stmt->execute ();
		}

		public function unflag() {
			$stmt = $this->DB->prepare ( "UPDATE quote SET isflagged=0" );
			$stmt->execute ();
		}
		
	} // end class DatabaseAdaptor

	$myDatabaseFunctions = new DatabaseAdaptor ();

	
	// Test code can only be used temporarily here. If kept, deleting account 'fourth' from anywhere would 
	// cause these asserts to generate error messages. And when did you find out 'fourth' is registered?
	// assert ( $myDatabaseFunctions->verified ( 'fourth', '4444' ) );
	// assert ( ! $myDatabaseFunctions->canRegister ( 'fourth' ) );


	?>
