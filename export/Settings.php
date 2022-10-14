<?php
class Export{

	public $host   = "localhost";
	public $dbuser   = "root";
	public $dbpass   = "";
	public $dbname = "pdo";

	public $con;

	public $jsonTable = array();

	public function __construct(){
		$this->getConnect();
	}

	//połączniee z baza danych
	public function getConnect(){
		$this->con = new mysqli($this->host, $this->dbuser, $this->dbpass, $this->dbname);
		mysqli_set_charset($this->con,'utf-8');

		if($this->con->connect_error){
			die("Connect failed: " . $this->con->connect_error);
		}
	}

	public function export(){
		if(isset($_POST['export'])){
			$get = trim($_POST['nazwaTabeli']);
			$get = htmlentities($get, ENT_QUOTES, "UTF-8");

			if(isset($_POST['bialeznaki'])){
				echo $this->allTableWithoutWhiteSpace($get);
			} else {
				echo $this->allTable($get);
			}
		}
	}

	public function exportPole(){
		if(isset($_POST['exportPole'])){
			$get = trim($_POST['nazwaTabeli']);
			$get = htmlentities($get, ENT_QUOTES, "UTF-8");

			$getPole = trim($_POST['nazwaPola']);
			$getPole = htmlentities($getPole, ENT_QUOTES, "UTF-8");

			if(isset($_POST['bialeznaki'])){
				echo $this->getFieldWithoutSpace($get, $getPole);
			} else {
				echo $this->getField($get, $getPole);
			}
		}
	}

	public function exportID(){
		if(isset($_POST['exportID'])){
			$get = trim($_POST['nazwaTabeli']);
			$get = htmlentities($get, ENT_QUOTES, "UTF-8");

			$getPole = trim($_POST['wyswietlID']);
			$getPole = htmlentities($getPole, ENT_QUOTES, "UTF-8");

			if(isset($_POST['bialeznaki'])){
				echo $this->getIDWithoutSpace($get, $getPole);
			} else {
				echo $this->getID($get, $getPole);
			}
		}
	}

	public function exportIlosc(){
		if(isset($_POST['exportIlosc'])){
			$get = trim($_POST['nazwaTabeli']);
			$get = htmlentities($get, ENT_QUOTES, "UTF-8");

			$getPole = trim($_POST['ilosc']);
			$getPole = htmlentities($getPole, ENT_QUOTES, "UTF-8");

			if(isset($_POST['bialeznaki'])){
				echo $this->getLoopWithoutSpace($get, $getPole);
			} else {
				echo $this->getLoop($get, $getPole);
			}
		}
	}

	public function exportLike(){
		if(isset($_POST['exportLike'])){
			$get = trim($_POST['nazwaTabeli']);
			$get = htmlentities($get, ENT_QUOTES, "UTF-8");

			$getPole = trim($_POST['nazwaPola']);
			$getPole = htmlentities($getPole, ENT_QUOTES, "UTF-8");


			$getLike = trim($_POST['nazwaLike']);
			$getLike = htmlentities($getLike, ENT_QUOTES, "UTF-8");

			$this->getLikeRecord($get, $getPole, $getLike);
			
		}
	}

	public function exportBetween(){
		if(isset($_POST['exportBetween'])){
			$get = trim($_POST['nazwaTabeli']);
			$get = htmlentities($get, ENT_QUOTES, "UTF-8");

			$getPole = trim($_POST['nazwaPola']);
			$getPole = htmlentities($getPole, ENT_QUOTES, "UTF-8");

			$getFirst = trim($_POST['firstPole']);
			$getFirst = htmlentities($getFirst, ENT_QUOTES, "UTF-8");

			$getSecond = trim($_POST['secondPole']);
			$getSecond = htmlentities($getSecond, ENT_QUOTES, "UTF-8");

			if(is_numeric($getFirst) AND is_numeric($getSecond)){
				
				$this->getBetweenRecord($get, $getPole, $getFirst, $getSecond);
				
			} else{
				print("<script>alert('Podane dane nie są liczbą!');</script>");
			}
			
		}
	}

	//wyświetlanie całej tabeli, ze znakami białymi
	public function allTable($nameTable){
		$sql = "SELECT * FROM $nameTable";
		$request = $this->con->query($sql);

		while($row = $request->fetch_array())
	    {
	        $this->jsonTable[] = $row;
	    }

	    $this->downloadAndReadFileJson();
	}

	//wyświetlanie całej tabeli, bez znaków białych
	public function allTableWithoutWhiteSpace($nameTable){
		$sql = "SELECT * FROM $nameTable";
		$request = $this->con->query($sql);

		while($row = $request->fetch_array())
	    {
	    	//zamiana białych znaków
	        $string = str_replace(' ', '', $row);
	        $this->jsonTable[] = $string;
	    }

	    $this->downloadAndReadFileJson();
	}

	//wyświetlanie całego konkretnego pola
	public function getField($nameTable, $nameField){
		$sql = "SELECT $nameField FROM $nameTable";
		if($request = $this->con->query($sql)){
			while($row = $request->fetch_array())
		    {
		        $this->jsonTable[] = $row;
		    }

		    $this->downloadAndReadFileJson();
		} else {
			print('jakis blad');
		}
	}

	//wyświetlanie całego konkretnego pola bez znaków białyc
	public function getFieldWithoutSpace($nameTable, $nameField){
		$sql = "SELECT $nameField FROM $nameTable";
		$request = $this->con->query($sql);

		while($row = $request->fetch_array())
	    {
	        $string = str_replace(' ', '', $row);
	        $this->jsonTable[] = $string;
	    }

	    $this->downloadAndReadFileJson();
	}

	//wyświetlanie danych po numerze ID użytkownika
	public function getID($nameTable, $selectID){
		$sql = "SELECT * FROM $nameTable WHERE id=$selectID";
		$request = $this->con->query($sql);

		while($row = $request->fetch_array())
	    {
	        $this->jsonTable[] = $row;
	    }

	    $this->downloadAndReadFileJson();
	}

	//wyświetlanie danych po numerze ID użytkownika bez znaków białych
	public function getIDWithoutSpace($nameTable, $selectID){
		$sql = "SELECT * FROM $nameTable WHERE id=$selectID";
		$request = $this->con->query($sql);

		while($row = $request->fetch_array())
	    {
	        $string = str_replace(' ', '', $row);
	        $this->jsonTable[] = $string;
	    }

	    $this->downloadAndReadFileJson();
	}

	//Wyświetlanie tabel z bazy danych
	public function getTables(){
		$sqlTable = "show tables";
		$requestTable = $this->con->query($sqlTable);

		while($table = $requestTable->fetch_array()){
			echo "<tr><td>". $table[0] . "</td></tr>";// $table[0];
		}
		mysqli_close($this->con);
	}

	//wyświetlanie ze znakami bialymi
	public function getLikeRecord($nameTable, $nameField, $recordLike){
		$sql = "SELECT * FROM $nameTable WHERE $nameField LIKE '$recordLike%';";

		if($request = $this->con->query($sql)){
			while($row = $request->fetch_array())
		    {
		        $this->jsonTable[] = $row;
		    }

		    $this->downloadAndReadFileJson();
		} else {
			print('jakis blad');
		}
	}

	//wyświetlanie bez znakow bilaych
	public function getLikeRecordWithoutSpace($nameTable, $nameField, $recordLike){
		$sql = "SELECT * FROM $nameTable WHERE $nameField LIKE '$recordLike%';";

		if($request = $this->con->query($sql)){
			while($row = $request->fetch_array())
		    {
		    	$string = str_replace(' ', '', $row);
		        $this->jsonTable[] = $row;
		    }

		    $this->downloadAndReadFileJson();
		} else {
			print('jakis blad');
		}
	}

	//wyświetlanie Between ze znakami bialymi
	public function getBetweenRecord($nameTable, $nameField, $firstNumber, $secondNumber){
		$sql = "SELECT * FROM $nameTable WHERE $nameField BETWEEN '$firstNumber' AND '$secondNumber'";

		if($request = $this->con->query($sql)){
			while($row = $request->fetch_array())
		    {
		        $this->jsonTable[] = $row;
		    }

		    $this->downloadAndReadFileJson();
		} else {
			print('jakis blad');
		}
	}

	//wyświetlanie Between bez znakami bialymi
	public function getBetweenRecordWithoutSpace($nameTable, $nameField, $firstNumber, $secondNumber){
		$sql = "SELECT * FROM $nameTable WHERE $nameField BETWEEN '$firstNumber' AND '$secondNumber'";

		if($request = $this->con->query($sql)){
			while($row = $request->fetch_array())
		    {
		    	$string = str_replace(' ', '', $row);
		        $this->jsonTable[] = $row;
		    }

		    $this->downloadAndReadFileJson();
		} else {
			print('jakis blad');
		}
	}


	//pobranie lub wyświetlenie gotowego pliku JSON, domyslnie ustawione pobranie do pliku .json
	public function downloadAndReadFileJson(){
		//wyświetlanie pliku JSON
	    //echo json_encode($this->jsonTable); 
	    
		$file = 'raport.json';

	    $fp = fopen($file, 'w');
	    fwrite($fp, json_encode($this->jsonTable));
	    fclose($fp);
	    
		if (file_exists($file)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($file).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($file));
		    readfile($file);
		    exit;
		}

	    print("<script>alert('Pomyślnie wyeksportowane dane do pliku JSON');</script>");
	          
	    mysqli_close($this->con);
	}
}

$obiekt = new Export();
?>