<?php
try { 
	require_once "leffaPDO.php";
	
	// Luodaan tietokanta-luokan olio
	$kantakasittely = new leffaPDO ();

	// Jos sivua pyytaneelta tuli hae 
	// eli kyseessa on nimellä leffojen hakeminen
	// web-sivulla on ajax-komennossa {nimi: $("#nimi").val()}, josta saadaan haettava elokuva
	if (isset ( $_GET ["nimi"] )) {

		// Tehdään kantahaku
		$tulos = $kantakasittely->haeLeffat ( $_GET ["nimi"] );
		
		// Palautetaan vastaus JSON-tekstina
		print (json_encode ( $tulos )) ;
	} 	
	// Kyseessa on kaikkien leffojen haku kannasta
	else {
		$tulos = $kantakasittely->kaikkiLeffat ();
	
		// Palautetaan vastaus JSON-tekstinä
		print json_encode ( $tulos );
	}
} catch ( Exception $error ) {
}
?>

