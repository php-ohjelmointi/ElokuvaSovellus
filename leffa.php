<?php
class Leffa implements JsonSerializable {
	 
	// Taulukko, missä on virhekoodeja vastaavat virhetekstit
	private static $virhelista = array ( 
			- 1 => "Virheellinen tieto",
			0 => "",
			11 => "Nimi on pakollinen",
			12 => "Nimi on liian lyhyt",
			13 => "Nimi on liian pitkä",
			21 => "Ohjaaja on pakollinen",
			22 => "Ohjaajan nimi on liian lyhyt",
			23 => "Ohjaajan nimi on liian pitkä",
			24 => "Ohjaajan nimessa saa olla vain kirjaimia ja -",
			31 => "Vuosi on pakollinen",
			32 => "Vuosi muodossa vvvv (numeroilla)",
			33 => "Vuosi on liian pieni",
			34 => "Vuosi ei voi olla tulevaisuudessa",
			41 => "Kesto on pakollinen",
			42 => "Kesto muodossa t:mm (numeroilla)",
			43 => "Minuutit välillä 0-59",
			44 => "Kesto on liian lyhyt",
			51 => "Kuvaus on pakollinen",
			52 => "Kuvaus on liian lyhyt",
			53 => "Kuvaus on liian pitkä",
			54 => "Kuvaus saa olla vain kirjaimia, numeroita ja - ,.!?" 
	);
	
	// Attribuutit
	private $nimi;
	private $ohjaaja;
	private $vuosi;
	private $kesto;
	private $kuvaus;
	private $id; // Tehty kannan takia, on kannassa avainkenttänä
	             
	// Metodi, mikä muuttaa olion JSON-muotoon
	public function jsonSerialize() {
		return array ( 
				"nimi" => $this->nimi,
				"ohjaaja" => $this->ohjaaja,
				"vuosi" => $this->vuosi,
				"kesto" => $this->kesto,
				"kuvaus" => $this->kuvaus,
				"id" => $this->id 
		);
	}
	
	// Konstruktori
	function __construct($nimi = "", $ohjaaja = "", $vuosi = "", $kesto = "", $kuvaus = "", $id = 0) {
		$this->nimi = trim ( $nimi );
		$this->setOhjaaja ( $ohjaaja );
		$this->vuosi = trim ( $vuosi );
		$this->kesto = trim ( $kesto );
		$this->kuvaus = trim ( $kuvaus );
		$this->id = $id;
	}
	
	// Metodit
	public function setNimi($nimi) {
		$this->nimi = trim ( $nimi );
	}
	public function getNimi() {
		return $this->nimi;
	}
	
	// $empty kertoo, voiko kenttä olla tyhjä (oletuksena ei saa olla)
	// $min on nimen minimipituus (oletuksena 1)
	// $max on nimen maksimipituus (oletuksena 30)
	public function checkNimi($required = true, $min = 1, $max = 30) {
		// Jos saa olla tyhjä ja on tyhjä
		if ($required == false && strlen ( $this->nimi ) == 0) {
			return 0;
		}
			
		// Jos ei saa olla tyhjä ja on tyhjä
		if ($required == true && strlen ( $this->nimi ) == 0) {
			return 11;
		}
			
		// Jos nimi on liian lyhyt tai pitkä
		if (strlen ( $this->nimi ) < $min) {
			return 12;
		}
			
		// Jos nimi on liian pitkä
		if (strlen ( $this->nimi ) > $max) {
			return 13;
		}
			
		// Nimi voi olla millainen tahansa muodoltaan joten enempää tarkistuksia ei tarvita
		
		return 0;
	}
	public function setOhjaaja($ohjaaja) {
		// etukirjaimet suurilla kirjaimilla
		$Onimi = trim ( $ohjaaja );
		$Onimi = mb_convert_case ( $Onimi, MB_CASE_LOWER, "UTF-8" );
		$Onimi = mb_convert_case ( $Onimi, MB_CASE_TITLE, "UTF-8" );
		
		$this->ohjaaja = $Onimi;
	}
	public function getOhjaaja() {
		return $this->ohjaaja;
	}
	
	// $ohjaajaVirhe = $leffa->checkOhjaaja();
	// $ohjaajaVirhe = $leffa->checkOhjaaja(true, 4, 100);
	// Metodin oletusarvoilla samaa metodia voidaan käyttää ilman parametreja tai paramereilla
	public function checkOhjaaja($required = true, $min = 1, $max = 30) {
		// Jos saa olla tyhjä ja on tyhjä
		if ($required == false && strlen ( $this->ohjaaja ) == 0) {
			return 0;
		}
			
		// Jos ei saa olla tyhjä ja on tyhjä
		if ($required == true && strlen ( $this->ohjaaja ) == 0) {
			return 21;
		}
			
		// Jos ohjaajan nimi on liian lyhyt
		if (strlen ( $this->ohjaaja ) < $min) {
			return 22;
		}
			
		// Jos ohjaajan nimi on liian pitkä
		if (strlen ( $this->ohjaaja ) > $max) {
			return 23;
		}
			
		// Ohjaajan nimessä saa olla vain pieniä ja isoja kirjaimia, välilyönti ja -
		// Tutkitaan, onko ohjaajassa noihin kuulumattomia merkkejä
		if (preg_match ( "/[^a-zöåä \-]/i", $this->ohjaaja )) {
			return 24;
		}
		
		return 0;
	}
	
	public function setVuosi($vuosi) {
		$this->vuosi = $vuosi;
	}
	
	public function getVuosi() {
		return $this->vuosi;
	}
	
	// $empty kertoo, voiko kenttä olla tyhjä (oletuksena ei saa olla)
	// $min on vuoden minimiarvo (oletuksena 1895)
	public function checkVuosi($required = true, $min = 1895) {
		
		// Jos saa olla tyhjä ja on tyhjä
		if ($required == false && strlen ( $this->vuosi ) == 0) {
			return 0;
		}
			
		// Jos ei saa olla tyhjä ja on tyhjä
		if ($required == true && strlen ( $this->vuosi ) == 0) {
			return 31;
		}
			
		// Onko neljällä numerolla
		if (! preg_match ( "/^\d{4}$/", $this->vuosi )) {
			return 32;
		}
			
		// Jos vuosi on liian pieni
		if ($this->vuosi < $min) {
			return 33;
		}
			
		// Jos vuosi on liian suuri
		// ei strlen, koska ei tutkita merkkijonon pituutta vaan muuttujan arvoa
		$max = date ( "Y", time () );
		if ($this->vuosi > $max) {
			return 34;
		}
		
		return 0;
	}
	
	public function setKesto($kesto) {
		$this->kesto = $kesto;
	}
	
	public function getKesto() {
		return $this->kesto;
	}
	
	public function checkKesto($required = true) {
		// Jos saa olla tyhjä ja on tyhjä
		if ($required == false && strlen ( $this->kesto ) == 0) {
			return 0;
		}
			
		// Jos ei saa olla tyhjä ja on tyhjä
		if ($required == true && strlen ( $this->kesto ) == 0) {
			return 41;
		}
			
		// Keston muoto on t:mm
		if (! preg_match ( "/^\d:\d{2}$/", $this->kesto )) {
			return 42;
		}
			
		// Pilkotaan kesto kahteen osaan : perusteella
		list ( $tunnit, $minuutit ) = explode ( ":", $this->kesto );
		
		// Tutkitaan, että kestossa olevat minuutit ovat alle 60
		if ($minuutit > 59) {
			return 43;
		}
		
		return 0;
	}
	
	public function setKuvaus($kuvaus) {
		$this->kuvaus = trim ( $kuvaus );
	}
	
	public function getKuvaus() {
		return $this->kuvaus;
	}
	
	public function checkKuvaus($required = true, $min = 10, $max = 300) {
		// Jos saa olla tyhjä ja on tyhjä
		if ($required == false && strlen ( $this->kuvaus ) == 0) {
			return 0;
		}
			
		// Jos ei saa olla tyhjä ja on tyhjä
		if ($required == true && strlen ( $this->kuvaus ) == 0) {
			return 51;
		}
			
		// Jos kommentti on liian lyhyt
		if (strlen ( $this->kuvaus ) < $min) {
			return 52;
		}
			
		// Jos kommentti on liian pitkä
		if (strlen ( $this->kuvaus ) > $max) {
			return 53;
		}
			
		// Kommentissa saa olla vain kirjaimia, numeroita ja - ,.!?
		if (preg_match ( "/^[a-zöåä0-9\-.,!?]$/i", $this->kuvaus )) {
			return 54;
		}
		
		return 0;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	public function getId() {
		return $this->id;
	}
	
	// Metodilla näytetään virhekoodia vastaava teksti
	public static function getError($virhekoodi) {
		if (isset ( self::$virhelista [$virhekoodi] ))
			return self::$virhelista [$virhekoodi];
		
		return self::$virhelista [- 1];
	}
}
?>