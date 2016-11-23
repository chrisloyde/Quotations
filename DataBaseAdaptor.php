 <?php
	// Authors: Chris Peterson and Beau Mejias-Brean
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
		    $password = password_hash($password, PASSWORD_DEFAULT);
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
					if ($record['username'] == $user) {
					    $hash = password_hash($pass, PASSWORD_DEFAULT);
					    if (password_verify($pass, $hash)) {
                            return true;
                        }
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

	?>
