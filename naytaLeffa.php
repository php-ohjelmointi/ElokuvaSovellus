<?php
require_once "leffa.php";
 
session_start ();

// Tutkitaan, onko istunnossa elokuvaa
if (isset ( $_SESSION ["elokuva"] )) {
	// Otetaan istunnosta olio
	$leffa = $_SESSION ["elokuva"];
} else {
	// Tehdään tyhjä leffa
	$leffa = new Leffa ();
}

// Poistetaan lomakkeen tiedot sisältävä olio sessiosta
unset ( $_SESSION ["elokuva"] );

// Laitetaan evästeisiin lisätyn elokuvan nimi ja lisäysaika
setcookie ( "elokuva", $leffa->getNimi (), time () + 60 * 60 * 24 * 30 );
$aika = date ( "d.m.Y", time () );
setcookie ( "aika", $aika, time () + 60 * 60 * 24 * 30 );
?>

<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8">
<title>Uusi leffa</title>
<meta name="author" content="Abdulsatar Qaderzada">
<link rel="stylesheet" type="text/css" href="leffa_arkisto.css">
</head>

<body>

	<header>LEFFA-ARKISTO</header>

	<nav>
		<a href="index.php">Etusivu</a>&nbsp;&nbsp;&nbsp; <a
			href="uusiLeffa.php">Uusi leffa</a>&nbsp;&nbsp;&nbsp; <a
			href="listaaLeffat.php">Listaa leffat</a>&nbsp;&nbsp;&nbsp;
	</nav>

	<aside>
		<img src="kuvat/lumiukko.jpg" width="140" height="200" alt="Lumiukko">
		<img src="kuvat/vihreamaili.jpg" width="140" height="200"
			alt="Vihreä maili"> <img src="kuvat/hullumaailma.jpg" width="140"
			height="200" alt="Hullu maailma">
	</aside>

	<article>

		<h2>Tiedot on talletettu</h2>

<?php
print ("<p>Id: " . $leffa->getId ()) ;
print ("<br>Nimi: " . $leffa->getNimi ()) ;
print ("<br>Ohjaaja: " . $leffa->getOhjaaja ()) ;
print ("<br>Vuosi: " . $leffa->getVuosi ()) ;
print ("<br>Kesto: " . $leffa->getKesto ()) ;
print ("<br>Kuvaus: " . $leffa->getKuvaus () . "</p>") ;
?>

<p>Siirrytään etusivulle 5 sekunnin kuluttua.</p>

	</article>

	<footer>
	Abdulsatar Qaderzada<br> PHP Web-ohjelmointi
	</footer>

</body>
</html>
<?php
// Ohjataan selain etusivulle 5 sekunnin kuluttua. Laitetaan kyslymerkkijonoon lisatty ja nimi.
header ( "refresh:5; url=index.php?lisatty=kylla&nimi=" . $leffa->getNimi());
exit;
?>
