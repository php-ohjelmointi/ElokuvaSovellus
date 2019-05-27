<!DOCTYPE html>

<html> 
<head>
<meta charset="UTF-8">
<title>Kaikki leffat</title>
<meta name="keywords" content="Leffa"/>
<meta name="author" content="Abdulsatar Qaderzada">
<link rel="stylesheet" type="text/css" href="leffa_arkisto.css">
</head>

<body>
<header>LEFFA-ARKISTO</header>
<nav>
<a href="index.php">Etusivu</a>&nbsp;&nbsp;&nbsp;
<a href="uusiLeffa.php">Uusi leffa</a>&nbsp;&nbsp;&nbsp;
Listaa leffat&nbsp;&nbsp;&nbsp;
</nav>

<aside>
<img src="kuvat/lumiukko.jpg" width="140" height="200" alt="Lumiukko">
<img src="kuvat/vihreamaili.jpg" width="140" height="200" alt="Vihreä maili">
<img src="kuvat/hullumaailma.jpg" width="140" height="200" alt="Hullu maailma">
</aside>
<article>
<h2>Kaikki leffat</h2>
<?php
try
{
   require_once "leffaPDO.php";

   $kantakasittely = new leffaPDO();

   $rivit = $kantakasittely->kaikkiLeffat();

   foreach ($rivit as $leffa) {
   	print("<p>Nimi: " . $leffa->getNimi());
   	print("<br>Ohjaaja: " . $leffa->getOhjaaja());
   	print("<br>Vuosi: " . $leffa->getVuosi());
	print("<br>Kuvaus: " . $leffa->getKuvaus() . "</p>\n");
   }
} catch (Exception $error) {
	 header("location: virhe.php?sivu=Listaus&virhe=" . $error->getMessage());
	 exit;
}

?>
</article>
<footer>
Abdulsatar Qaderzada<br> PHP Web-ohjelmointi
</footer>
</body>
</html>
