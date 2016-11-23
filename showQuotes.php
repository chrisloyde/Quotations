<!-- 
Authors: Chris Peterson and Beau Mejias-Brean
-->

<?php
require_once './DataBaseAdaptor.php';
$myDatabaseFunctions = new DatabaseAdaptor();
$arrayOfQuotes = $myDatabaseFunctions->getQuotesAsArray ();
?>

<h1>Quotes</h1>
<?php
session_start ();
?>

<span class="login">logged in as:
	<?php  if (isset ($_SESSION['username'])) {
		echo $_SESSION['username'];
	} else {
		echo "Guest";
	}
	?>
</span>

<!-- Add a horizontal menu -->
<div class="navBar">
<a href="index.php?mode=new">Add Quote</a>
<a href="index.php?mode=login">Login</a>
<a href="index.php?mode=register">Register</a>
	<?php
	if (isset($_SESSION['username'])) {
		echo "<a href='controller.php?action=logout'>Logout</a>";
		echo " <a href='controller.php?action=unflag'>Unflag All</a>";
	}
	?>
	<br>
</div>
<!--  Show all quotes on a separate row -->
<?php foreach($arrayOfQuotes as $quote) { ?>
<div class="container">
		 <?= '"'. $quote['quote'] . '"' ?>  
     <br>
	<p class="author"> 
     &nbsp;&nbsp;--
     <?= $quote['author'] ?>  
     <br>
	</p>

	<form action="controller.php" method="get">
		<input type="hidden" name="ID" value="<?= $quote['id']?>">
		&nbsp;&nbsp;&nbsp;
		<button name="action" value="increase">+</button>

		 <span id="rating"> <?= $quote['points']?> </span>
		<button name="action" value="decrease">-</button>
		<?php
		if (isset($_SESSION['username'])) {
			echo "<button name='action' value='flag'>flag</button>";
		}
		?>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</form>

</div>
<br>

<?php } // End for loop  ?>

</body>
</html>