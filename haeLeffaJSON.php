<!DOCTYPE html>

<html>
<head> 
<meta charset="UTF-8">
<title>Hae leffa JSON:lla</title>
<meta name="keywords" content="Leffa" />
<meta name="author" content="Abdulsatar Qaderzada">
<link rel="stylesheet" type="text/css" href="leffa_arkisto.css">

<!-- Käytä uusinta, näet sen osoitteesta http://code.jquery.com -->
<script src="http://code.jquery.com/jquery-2.2.3.min.js"
		integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="
		crossorigin="anonymous"></script>
</head>

<body>
	<header>LEFFA-ARKISTO JSON:lla</header>

	<nav></nav>

	<article>
		<h2>Hae leffa</h2>
		<form action="" method="post">
			<input type="text" id="nimi" name="nimi">
			<!-- onclick kertoo, että painikkeen painalluksen käsittelee haeNimella-funktio -->
			<input type="button" id="hae" name="hae" value="Hae">
		</form>
		<br>
		<div style="margin-bottom:0.5cm" id="lista"></div>
		<p>
			<a href="index.php">Etusivulle</a>
		</p>
	</article>

	<footer>
		Abdulsatar Qaderzada<br> PHP Web-ohjelmointi
	</footer>
	
	<script type="text/javascript">

		$(document).on("ready", function() {
			
			// Kun painiketta id="hae" painetaan
			$("#hae").on("click", function() {
				$.ajax({
					url: "leffatJSON.php",  // PHP-sivu, jota haetaan AJAX:lla
					method: "get",
					data: {nimi: $("#nimi").val()},  // Hakukriteeri on nimi, jonka arvona on lomakekentän id="nimi" arvo
					dataType: "json",
					timeout: 5000
				})
				// AJAX haku onnistui
				.done(function(data) {
					// Tyhjennetään elementti, johon vastaus laitetaan
					$("#lista").html("");

					// Käsitellään vastauksena tullut taulukko
					for(var i = 0; i < data.length; i++) {
						// Lisätään attribuutilla id="lista" elementtiin sisältöä
						$("#lista").append("<p>Nimi: " + data[i].nimi +
						"<br>Ohjaaja: " + data[i].ohjaaja +
						"<br>Vuosi: " + data[i].vuosi +
						"<br>Kuvaus: " + data[i].kuvaus + "</p>");
					}
					// Jos vastauksena ei tullut yhtään riviä eli vastaus oli tyhjä taulukko
					if (data.length == 0) {
						$("#lista").append("<p>Haku ei tuottanut yhtään elokuvaa</p>")
					}
				})
				// AJAX haku ei onnistunut
				.fail(function() {
					$("#lista").html("<p>Listausta ei voida tehdä</p>");
				});
				
			});
		});
	</script>

</body>
</html>
