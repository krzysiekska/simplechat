<?php

class Chat {

	//dane do polaczenia
	public $localhost = "localhost";
	public $root = "root";
	public $dbpass = "";
	public $dbname = "pdo";

	//połącznie z baza danych
	public $db;

	//tablica, dzieki ktorej potem wyswietlamy zawartosc tabeli (czyli wiadomosci z chatu)
	public $result = array();

	//zmienne globalne przechowujące pobrane informacje metodą $_POST[''];
	public $messagePost;
	public $fromPost;

	//zmienne globalne przechowujące ostateczną informację po czyszczeniu kodu
	public $messageX;
	public $fromX;
	public $getHoursX;
	public $getMinutesX;

	//konstruktor -> funkcja wykonuje sie automatycznie w momencie utworzenia obiektu, np. $obiekt = new Chat();
	public function __construct(){
		$this->getConnect();
		$this->getMessage();
	}

	//metoda odpowiedzialna za połączenie z baza danych
	public function getConnect(){
		$this->db = new mysqli($this->localhost, $this->root, $this->dbpass, $this->dbname);

		if($this->db->connect_error){
			die("Connection failed: " . $this->db->connect_error);
		}
	}

	//metoda odpowiedzialna za odebranie wiadomości z chatu oraz oczyszczenie kodu 
	public function getMessage(){
		$message = $_POST['message'] ?? null;
		$this->messagePost = $message;

		$messageHtml = htmlentities($message, ENT_QUOTES, "UTF-8");
		$messageTrim = trim($messageHtml);
		$this->messageX = $messageTrim;
		$this->getFrom();
	}

	//metoda odpowiedzialna za odebranie nazwy wysylajacego wiadomosc z chatu oraz oczyszczenie kodu 
	public function getFrom(){
		$from = $_POST['from'] ?? null;
		$this->fromPost = $from;

		$fromHtml = htmlentities($from, ENT_QUOTES, "UTF-8");
		$fromTrim = trim($fromHtml);
		$this->fromX = $fromTrim;
		$this->getTime();
	}

	//pobranie godziny i minuty wysłanej wiadomości
	public function getTime(){
		$getHours = $_POST['getHours'] ?? null;
		$getMinutes = $_POST['getMinutes'] ?? null;
		$this->getHoursX = $getHours;
		$this->getMinutesX = $getMinutes;
		$this->setData();
	}

	//dodanie nazwy usera, wiadomosci, godziny i minuty do bazy danych
	public function setData(){
		$this->getConnect();
		if(!empty($this->messagePost) && !empty($this->fromPost))
		{
			$sql = "INSERT INTO users VALUES ('','".$this->fromX."','".$this->messageX."','".$this->getHoursX."','".$this->getMinutesX."')";
			$this->result['send_status'] = $this->db->query($sql);
		}
		$this->getData();
	}

	//wyswietlenie wiadomosci, jakie zostały wysłane przez użytkownika a następnie wyświetlenie tego w .JSON
	public function getData(){
		$start = $_GET['start'] ?? 0;
		$items = $this->db->query("SELECT * FROM users WHERE id > " . $start);

		while($row = $items->fetch_assoc()){
			$this->result['items'][] = $row;
		}

		$this->db->close();

		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/json');

		echo json_encode($this->result);
	}
}

$obiekt = new Chat();
?>