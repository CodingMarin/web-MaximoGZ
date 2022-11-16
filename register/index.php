<?php
include('includes/configuration.php');
include('includes/classes.class');

if(!empty($_POST['inscrire']))
{
	if(empty($_POST['identifiant']) || empty($_POST['mdp']) || empty($_POST['mail']))
	{
		$erreur = "Username, Password or Email is empty!";
	}
	elseif(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
	{
		$erreur = "The Email is incorrect!";
	}
	elseif( empty($_POST['prenom']) || empty($_POST['age']))
	{
		$erreur = "Name or Age is empty!";
	}
	elseif($_POST['age'] < 1 || $_POST['age'] > 100 || !is_numeric($_POST['age']))
	{
		$erreur = "The Age is incorrect!";
	}
	else
	{
		$requete = $bdd->prepare('SELECT UserID, Email FROM Account WHERE UserID = :uid OR Email = :mail');
		$requete->execute(array(
		"uid" => $_POST['identifiant'],
		"mail" => $_POST['mail']
		));
		$donnees = $requete->fetch();

		if($donnees['UserID'] == $_POST['identifiant'])
		{
			$console = "Username already in use!";
		}
		elseif($donnees['Email'] == $_POST['mail'])
		{
			$console = "Email already in use!";
		}
		else
		{
			$requete->closeCursor();
			$requete = $bdd->prepare('INSERT INTO Account(UserID, UGradeID, PGradeID, RegDate, Email, Name, Age) VALUES(:UserID, :UGradeID, :PGradeID, getdate(), :Email, :Name, :Age)');
			$requete->execute(array(
			':UserID' => $_POST['identifiant'],
			':UGradeID' => 0,
			':PGradeID' => 0,
			':Email' => $_POST['mail'],
			':Name' => $_POST['prenom'],
			':Age' => $_POST['age']
			));
			$requete->closeCursor();

			$requete = $bdd->prepare('SELECT AID FROM Account WHERE UserID = :UserID');
			$requete->execute(array(
			':UserID' => $_POST['identifiant']
			));
			$donnees = $requete->fetch();

			$requete1 = $bdd->prepare('INSERT INTO Login(UserID, AID, Password) VALUES(:UserID, :AID, :Password)');
			$requete1->execute(array(
			':UserID' => $_POST['identifiant'],
			':AID' => $donnees['AID'],
			':Password' => $_POST['mdp']
			));
			$requete->closeCursor();
			$requete1->closeCursor();
			$console = "Account registered!";
		}
	}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?php echo htmlentities(gunz_name); ?>'s registering page</title>
<link rel="stylesheet" type="text/css" href="style/style.css" />
</head>

<body>
	<div id="conteneur">
		<header>
			<nav>
				<ul>
					<li id="enregistrer">Register</li>
					<li id="rankings">Rankings</li>
					<li id="telecharger">Download</li>
				</ul>
			</nav>
		</header>

		<div id="form">
			<form id="formulaire" action="index.php" method="post">
				<h2>Account information</h2>
				<label for="identifiant">Username:</label>
				<input type="text" name="identifiant" id="identifiant" value="<?php echo htmlentities($_POST['identifiant']);?>">
				<label for="mdp">Password:</label>
				<input type="password" name="mdp" id="mdp">
				<label for="mail">Email:</label>
				<input type="text" name="mail" id="mail" value="<?php echo htmlentities($_POST['mail']);?>">

				<h2>Your information</h2>
				<label for="prenom">Name:</label>
				<input type="text" name="prenom" id="prenom" value="<?php echo htmlentities($_POST['prenom']);?>">
				<label for="age">Age:</label>
				<input type="text" name="age" id="age" value="<?php echo htmlentities($_POST['age']);?>">
				<input type="submit" name="inscrire" value="Register!">

				<?php
				if(!empty($erreur))
				{
					echo '<div class="erreur"><p>'.$erreur.'</p></div>';
				}

				if(!empty($msg))
				{
					echo '<div class="msg"><p>'.$msg.'</p></div>';
				}
				?>
			</form>
		</div>

		<div id="telecharger_contenu">
			<h2>Download <?php echo htmlentities(gunz_name); ?></h2>
			<nav id="dl">
				<ul>
					<li><a href="<?php echo htmlentities(dl_1);?>">Mirror 1</a></li>
					<li><a href="<?php echo htmlentities(dl_2);?>">Mirror 2</a></li>
					<li><a href="<?php echo htmlentities(dl_3);?>">Mirror 3</a></li>
					<li><a href="<?php echo htmlentities(dl_4);?>">Mirror 4</a></li>
				</ul>
			</nav>

			<h2>Controls</h2>
			<img src="style/images/controls.gif" alt="controls">
		</div>

		<div id="ranks">
			<h2>Player Ranking</h2>
			<nav id="dl">
				<?php
				echo "<table style='border: none;'>";
				echo "<tr><th>Rank</th><th>Name</th><th>Level</th><th>XP</th><th>Kills</th><th>Deaths</th></tr>";
		
				$requete = $bdd->prepare('SELECT TOP 5 row_number() OVER(ORDER BY Level DESC) Ranking, Name, Level, XP, KillCount, DeathCount FROM Character WHERE DeleteFlag=0');
				$requete->execute();
				
				$donnees = $requete->setFetchMode(PDO::FETCH_ASSOC);
				
				foreach(new TableRows(new RecursiveArrayIterator($requete->fetchAll())) as $k=>$v) {
					echo $v;
				}
				echo "</table>";
				$requete->closeCursor();
				?>
			</nav>

			<h2>Clan Ranking</h2>
			<nav id="dl">
				<?php
				echo "<table style='border: none;'>";
				echo "<tr><th>Rank</th><th>Name</th><th>Points</th><th>Wins</th><th>Losses</th><th>Draws</th></tr>";
		
				$requete = $bdd->prepare('SELECT TOP 5 Ranking, Name, Point, Wins, Losses, Draws FROM Clan WHERE DeleteFlag=0 ORDER BY Point DESC');
				$requete->execute();
				
				$donnees = $requete->setFetchMode(PDO::FETCH_ASSOC);
				
				foreach(new TableRows(new RecursiveArrayIterator($requete->fetchAll())) as $k=>$v) {
					echo $v;
				}
				echo "</table>";
				$requete->closeCursor();
				?>
			</nav>
		</div>

		<?php
		if(!empty($console))
		{
			echo '<div id="console">';
			echo '<p id="cons0">- Connecting to Database...</p>';
			echo '<p id="cons1">- Connected!</p>';
			echo '<p id="cons2">- Looking for "dbo.Login" and "dbo.Account" tables...</p>';
			echo '<p id="cons3">- Tables found!</p>';
			echo '<p id="cons4">- Trying to register the account "<strong>'.htmlentities($_POST['identifiant']).'</strong>"...</p>';
			echo '<p id="cons5">'.$console.'</p>';
			echo '</div>';
		}
		?>
	</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="script/sweyz.js"></script>
</body>
</html>
