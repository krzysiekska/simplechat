<?php
require("Settings.php");

$obiekt->export();

$obiekt->exportPole();

$obiekt->exportID();

$obiekt->exportLike();

$obiekt->exportBetween();
?>
<center>
	<h1>Obecne tabele w bazie danych</h1>
	<form action="#" method="POST">
	<input type="submit" name="showTables" value="Show Tables"/>
	</form>
	<?php if(isset($_POST['showTables'])){  ?>
	<table id="table">
				   <thead>
				      <tr>
				         <th>Nazwa tabeli</th>
				      </tr>
				   </thead>
				   <tbody>
				      <tr>
				         <td><?php $obiekt->getTables(); ?></td>
				      </tr>
				   </tbody>
				</table>
	<?php } ?>


<h1>Wyświetlanie całej tabeli</h1>
<form action="#" method="POST">
	<input type="text" name="nazwaTabeli" placeholder="Nazwa tabeli" /><Br />
	<input type="checkbox" name="bialeznaki"/>Wyświetl bez znaków białych<br />
	<input type="submit" name="export" value="Export"/>
</form>

<!-- Wyświetlanie zawartości z pola dla rekordów zaczynających się na konkretną literę bądź kila liter -->

<h1>Wyświetlanie zawartości z pola dla konketnych rekordów*</h1>
<form action="#" method="POST">
	<input type="text" name="nazwaTabeli" placeholder="Nazwa tabeli" /><Br />
	<input type="text" name="nazwaPola" placeholder="Nazwa pola" /><Br />
	<input type="text" name="nazwaLike" placeholder="Po czym szukac? Np. s%" /><Br />
	<input type="submit" name="exportLike" value="Export Pole"/>
</form>

<h1>Wyświetlanie zawartości z konkretnego zakresu*</h1>
<form action="#" method="POST">
	<input type="text" name="nazwaTabeli" placeholder="Nazwa tabeli" /><Br />
	<input type="text" name="nazwaPola" placeholder="Nazwa pola" /><Br />
	<input type="text" name="firstPole" placeholder="Podaj pierwszą wartość z zakresu, np. 5" /><Br />
	<input type="text" name="secondPole" placeholder="Podaj drugą wartość z zakresu, np. 10" /><Br />
	<input type="submit" name="exportBetween" value="Export Pole"/>
</form>

<h1>Wyświetlanie konkretnego pola</h1>
<form action="#" method="POST">
	<input type="text" name="nazwaTabeli" placeholder="Nazwa tabeli" /><Br />
	<input type="text" name="nazwaPola" placeholder="Nazwa pola" /><Br />
	<input type="checkbox" name="bialeznaki"/>Wyświetl bez znaków białych<br />
	<input type="submit" name="exportPole" value="Export Pole"/>
</form>

<h1>Wyświetlanie dane po ID</h1>
<form action="#" method="POST">
	<input type="text" name="nazwaTabeli" placeholder="Nazwa tabeli" /><Br />
	<input type="text" name="wyswietlID" placeholder="Podaj nr ID użytkownika" /><Br />
	<input type="checkbox" name="bialeznaki"/>Wyświetl bez znaków białych<br />
	<input type="submit" name="exportID" value="Export Pole"/>
</form>
</center>