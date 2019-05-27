<?php
session_start();

// Jos istunnossa on leffa
unset ( $_SESSION ["leffa"] );
?>
<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8">
<title>Virhe</title>
<meta name="author" content="Abdulsatar Qaderzada">
<link rel="stylesheet" type="text/css" href="leffa_arkisto.css">
</head>

<body>

	<header>LEFFA-ARKISTO</header>

	<nav>
		<a href="index.php">Etusivu</a>&nbsp;&nbsp;&nbsp; <a
			href="uusiLeffa.php">Uusi leffa</a>&nbsp;&nbsp;&nbsp; <a
			href="listaaLeffat.php">Listaa kaikki leffat</a>
	</nav>

	<aside>
		<img src="kuvat/lumiukko.jpg" width="140" height="200" alt="Lumiukko">
		<img src="kuvat/vihreamaili.jpg" width="140" height="200"
			alt="Vihreä maili"> <img src="kuvat/hullumaailma.jpg" width="140"
			height="200" alt="Hullu maailma">
	</aside>

	<article>
		<h2>Ongelmia</h2>

<?php
if (isset ( $_GET ["virhe"] )) {
	$virhe = $_GET ["virhe"];
	@$sivu = $_GET ["sivu"];
} else {
	$virhe = "Tuntematon virhe";
	$sivu = "Ei tieto";
}

print ("<p><b>$sivu</b>: $virhe</p>") ;
?>

<p>Siirrytään etusivulle 5 sekunnin kuluttua.</p>
	</article>

	<footer>
		Abdulsatar Qaderzada<br> PHP Web-ohjelmointi
	</footer>

</body>
</html>

<?php
header ( "refresh:5; url=index.php?virhe=kylla");
exit;
?>
