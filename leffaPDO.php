<?php
require_once "leffa.php";

class leffaPDO {
	
	private $db;
	private $lkm;
	
	function __construct($dsn = "mysql:host=localhost;dbname=leffat", $user = "root", $password = "salainen") {
		// Ota yhteys kantaan
		$this->db = new PDO ( $dsn, $user, $password );
		
		// Virheiden jäljitys kehitysaikana
		$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		
		// MySQL injection estoon (paramerit sidotaan PHP:ssä ennen SQL-palvelimelle lähettämistä)
		$this->db->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
		
		// Tulosrivien määrä
		$this->lkm = 0;
	}
	
	// Metodi palauttaa tulosrivien määrän
	function getLkm() {
		return $this->lkm;
	}
	
	public function kaikkiLeffat() {
		$sql = "SELECT id, nimi, ohjaaja, vuosi, kesto, kuvaus
		        FROM leffa";
		
		// Valmistellaan lause
		if (! $stmt = $this->db->prepare ( $sql )) {
			$virhe = $this->db->errorInfo ();
			
			throw new PDOException ( $virhe [2], $virhe [1] );
		}
		
		// Ajetaan lauseke
		if (! $stmt->execute ()) {
			$virhe = $stmt->errorInfo ();
			
			throw new PDOException ( $virhe [2], $virhe [1] );
		}
		
		// Käsittellään hakulausekkeen tulos
		$tulos = array ();
		
		// Pyydetään haun tuloksista kukin rivi kerrallaan
		while ( $row = $stmt->fetchObject () ) {
			// Tehdään tietokannasta haetusta rivistä leffa-luokan olio
			$leffa = new Leffa ();
			
			$leffa->setId ( $row->id );
			$leffa->setNimi ( utf8_encode ( $row->nimi ) );
			$leffa->setOhjaaja ( utf8_encode ( $row->ohjaaja ) );
			$leffa->setVuosi ( $row->vuosi );
			$leffa->setKesto ( $row->kesto );
			$leffa->setKuvaus ( utf8_encode ( $row->kuvaus ) );
			
			// Laitetaan olio tulos taulukkoon (olio-taulukkoon)
			$tulos [] = $leffa;
		}
		
		$this->lkm = $stmt->rowCount ();
		
		return $tulos;
	}
	
	public function haeLeffat($nimi) {
		$sql = "SELECT id, nimi, ohjaaja, vuosi, kesto, kuvaus
		        FROM leffa
				WHERE nimi like :nimi";
		
		// Valmistellaan lause, prepare on PDO-luokan metodeja
		if (! $stmt = $this->db->prepare ( $sql )) {
			$virhe = $this->db->errorInfo ();
			throw new PDOException ( $virhe [2], $virhe [1] );
		}
		
		// Sidotaan parametrit
		$ni = "%" . utf8_decode ( $nimi ) . "%";
		$stmt->bindValue ( ":nimi", $ni, PDO::PARAM_STR );
		
		// Ajetaan lauseke
		if (! $stmt->execute ()) {
			$virhe = $stmt->errorInfo ();
			
			if ($virhe [0] == "HY093") {
				$virhe [2] = "Invalid parameter";
			}
			
			throw new PDOException ( $virhe [2], $virhe [1] );
		}
		
		// Käsittellään hakulausekkeen tulos
		$tulos = array ();
		
		while ( $row = $stmt->fetchObject () ) {
			$leffa = new Leffa ();
			
			$leffa->setId ( $row->id );
			$leffa->setNimi ( utf8_encode ( $row->nimi ) );
			$leffa->setOhjaaja ( utf8_encode ( $row->ohjaaja ) );
			$leffa->setVuosi ( $row->vuosi );
			$leffa->setKesto ( $row->kesto );
			$leffa->setKuvaus ( utf8_encode ( $row->kuvaus ) );
			
			// Laitetaan olio tulos taulukkoon (olio-taulukkoon)
			$tulos [] = $leffa;
		}
		
		$this->lkm = $stmt->rowCount ();
		
		return $tulos;
	}
	
	function lisaaLeffa($leffa) {
		$sql = "insert into leffa (nimi, ohjaaja, vuosi, kesto, kuvaus)
		        values (:nimi, :ohjaaja, :vuosi, :kesto, :kuvaus)";
		
		// Valmistellaan SQL-lause
		if (! $stmt = $this->db->prepare ( $sql )) {
			$virhe = $this->db->errorInfo ();
			throw new PDOException ( $virhe [2], $virhe [1] );
		}
		
		// Parametrien sidonta
		$stmt->bindValue ( ":nimi", utf8_decode ( $leffa->getNimi () ), PDO::PARAM_STR );
		$stmt->bindValue ( ":ohjaaja", utf8_decode ( $leffa->getOhjaaja () ), PDO::PARAM_STR );
		$stmt->bindValue ( ":vuosi", $leffa->getVuosi (), PDO::PARAM_INT );
		$stmt->bindValue ( ":kesto", $leffa->getKesto (), PDO::PARAM_STR );
            $stmt->bindValue ( ":kuvaus", utf8_decode ( $leffa->getKuvaus () ), PDO::PARAM_STR );
		
		// Jotta id:n saa lisäyksestä, täytyy laittaa tapahtumankäsittely päälle
		$this->db->beginTransaction();
		
		// Suoritetaan SQL-lause (insert)
		if (! $stmt->execute ()) {
			$virhe = $stmt->errorInfo ();
			
			if ($virhe [0] == "HY093") {
				$virhe [2] = "Invalid parameter";
			}
			// Perutaan tapahtuma
			$this->db->rollBack();
			
			throw new PDOException ( $virhe [2], $virhe [1] );
		}
		
		// id täytyy ottaa talteen ennen tapahtuman päättämistä
		$id = $this->db->lastInsertId ();
		
		$this->db->commit();
		
		// Palautetaan lisätyn ilmoituksen id
		return $id;
	}
}
?>