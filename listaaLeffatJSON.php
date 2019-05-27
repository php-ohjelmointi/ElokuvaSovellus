<!DOCTYPE html>
 
<html>
<head>
<meta charset="UTF-8">
<title>Kaikki leffat</title>
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
		<h2>Kaikki leffat</h2>

		<div id="lista"></div>
		<p>
			<a href="index.php">Etusivulle</a>
		</p>

	</article>

	<footer>
	Abdulsatar Qaderzada<br> PHP Web-ohjelmointi
	</footer>
	
	<script type="text/javascript">

		$(document).on("ready", function() {
		
			$.ajax({
                url: "leffatJSON.php",  // PHP-sivu, jota haetaan AJAX:lla
                method: "get",
				dataType: "json",
                timeout: 5000
            })
			.done(function(data) {
				// Tyhjennetään elementti, johon vastaus laitetaan (id="lista")
				$("#lista").html("");

				// Käsitellään taulukko, 
				for(var i = 0; i < data.length; i++) {
					// Lisätään id="lista" elementtiin sisältöä
					$("#lista").append("<p>Nimi: " + data[i].nimi +
					"<br>Ohjaaja: " + data[i].ohjaaja +
					"<br>Vuosi: " + data[i].vuosi +
					"<br>Kuvaus: " + data[i].kuvaus + "</p>");
				}
            })
			.fail(function() {
 			    $("#lista").html("<p>Listausta ei voida tehdä</p>");
			});
			
		});
	</script>
</body>
</html>
