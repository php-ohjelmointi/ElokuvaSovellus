<?php
require_once "leffa.php";
 
// Käynnistetään istunto luokkien tuonnin jälkeen
session_start ();

// Onko painettu talleta-nimistä painiketta
if (isset ( $_POST ["talleta"] )) {
	
	$leffa = new Leffa ( $_POST ["nimi"], $_POST ["ohjaaja"], $_POST ["vuosi"], $_POST ["kesto"], $_POST ["kuvaus"] );
	
	// Laitetaan istuntoon olio
	$_SESSION ["elokuva"] = $leffa;
	
	$nimiVirhe = $leffa->checkNimi ();
	$ohjaajaVirhe = $leffa->checkOhjaaja ();
	$vuosiVirhe = $leffa->checkVuosi ();
	$kestoVirhe = $leffa->checkKesto ();
	// Kommenttia ei ole pakko antaa, siksi parametrina false
	$kuvausVirhe = $leffa->checkKuvaus ( false );
	
	// Jos ei ole virheitä lähdetään näyttämään tiedot toisella sivulla
	if ($nimiVirhe == 0 && $ohjaajaVirhe == 0 && $vuosiVirhe == 0 && $kestoVirhe == 0 && $kuvausVirhe == 0) {
		
		try {
			require_once "leffaPDO.php";
			
			$kantakasittely = new leffaPDO ();
			
			$id = $kantakasittely->lisaaLeffa ( $leffa );
			
			// Muutetaan istunnossa olevan olion id lisäykseltä saaduksi id:ksi
			$_SESSION ["elokuva"]->setId ( $id );
		} catch ( Exception $error ) {
			session_write_close ();
			header ( "location: virhe.php?sivu=" . urlencode ( "Lisäys" ) . "&virhe=" . $error->getMessage () );
			exit ();
		}
		
		// Suljetaan istunto, koska sitä ei tarvita tällä sivulla
		session_write_close ();
		header ( "location: naytaLeffa.php" );
		exit ();
	}
}
// Onko painettu peruuta-nimistä painiketta
elseif (isset ( $_POST ["peruuta"] )) {
	
	// Jos poistetaan vain lomake istunnosta
	unset ( $_SESSION ["elokuva"] );
} 
// Sivulle tultiin etusivulta tai joltain toiselta sivulta
else {
	// Tutkitaan, onko istunnossa elokuvaa
	if (isset ( $_SESSION ["elokuva"] )) {
		// Otetaan istunnosta olio
		$leffa = $_SESSION ["elokuva"];
		
		$nimiVirhe = $leffa->checkNimi ();
		$ohjaajaVirhe = $leffa->checkOhjaaja ();
		$vuosiVirhe = $leffa->checkVuosi ();
		$kestoVirhe = $leffa->checkKesto ();
		// Kuvausta ei ole pakko antaa, siksi parametrina false
		$kuvausVirhe = $leffa->checkKuvaus ( false );
	} else {
		// Tehdään tyhjä leffa
		$leffa = new Leffa ();
		
		$nimiVirhe = 0;
		$ohjaajaVirhe = 0;
		$vuosiVirhe = 0;
		$kestoVirhe = 0;
		$kuvausVirhe = 0;
	}
}

// print_r($leffa);
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8">
<title>Uusi leffa</title>
<style type="text/css">
label {
	width: 8em;
	display: block;
	float: left;
}

.pun {
	color: red;
}
</style>
<meta name="author" content="Abdulsatar Qaderzada">
<link rel="stylesheet" type="text/css" href="leffa_arkisto.css">
</head>

<body>
	<header>LEFFA-ARKISTO</header>

	<nav>
		<a href="index.php">Etusivu</a>&nbsp;&nbsp;&nbsp; Uusi
		leffa&nbsp;&nbsp;&nbsp; <a href="listaaLeffat.php">Listaa leffat</a>&nbsp;&nbsp;&nbsp;
	</nav>

	<aside>
		<img src="kuvat/lumiukko.jpg" width="140" height="200" alt="Lumiukko">
		<img src="kuvat/vihreamaili.jpg" width="140" height="200"
			alt="Vihreä maili"> <img src="kuvat/hullumaailma.jpg" width="140"
			height="200" alt="Hullu maailma">
	</aside>

	<article>
		<h2>Uusi leffa</h2>
		<form action="uusiLeffa.php" method="post">

			<!-- Laitetaan piilokenttään leffan id -->
			<input type="hidden" name="id"
				value="<?php print($leffa->getId()); ?>">

			<p>
				<label>Leffan nimi</label> <input type="text" name="nimi" size="30"
					value="<?php print(htmlentities($leffa->getNimi(), ENT_QUOTES, "UTF-8"));?>">
<?php
print ("<span class='pun'>" . $leffa->getError ( $nimiVirhe ) . "</span>") ;
?>
</p>

			<p>
				<label>Ohjaaja</label> <input type="text" name="ohjaaja" size="30"
					value="<?php print(htmlentities($leffa->getOhjaaja(), ENT_QUOTES, "UTF-8"));?>">
<?php
print ("<span class='pun'>" . $leffa->getError ( $ohjaajaVirhe ) . "</span>") ;
?>
</p>

			<p>
				<label>Valmistusvuosi</label> <input type="text" name="vuosi"
					size="4" maxlength="4"
					value="<?php print(htmlentities($leffa->getVuosi(), ENT_QUOTES, "UTF-8"));?>">
<?php
print ("<span class='pun'>" . $leffa->getError ( $vuosiVirhe ) . "</span>") ;
?>
</p>

			<p>
				<label>Kesto</label> <input type="text" name="kesto" size="4"
					maxlength="4"
					value="<?php print(htmlentities($leffa->getKesto(), ENT_QUOTES, "UTF-8"));?>">
<?php
print ("<span class='pun'>" . $leffa->getError ( $kestoVirhe ) . "</span>") ;
?>
</p>

			<p>
				<label>Kuvaus</label>
				<textarea rows="5" cols="40" name="kuvaus"><?php print(htmlentities($leffa->getKuvaus(), ENT_QUOTES, "UTF-8"));?></textarea>
<?php
print ("<span class='pun' style='vertical-align:top'>" . $leffa->getError ( $kuvausVirhe ) . "</span>") ;
?>
</p>

			<p>
				<label>&nbsp;</label> <input type="submit" name="talleta"
					value="Tallenna"> <input type="submit" name="peruuta"
					value="Peruuta">
			</p>

		</form>

	</article>

	<footer>
	Abdulsatar Qaderzada<br> PHP Web-ohjelmointi
	</footer>

</body>
</html>
