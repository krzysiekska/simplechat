<html>
	<head>
		<title>Simple Chat jQuery + PHP</title>
		<link rel="stylesheet" href="style.css">
		<meta charset="utf-8">
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="vigenere/vigenere.min.js"></script>
	</head>
	<body>
		<script>
			let from = null;
			let start = 0; 
			let url = "http://localhost/SimpleChat/chat.php";

			var objVigenere = new Vigenere();
						
			$(document).ready(function(){
				from = prompt("Podaj imię: ");
				load();
							
				$('form').submit(function(e){
				const date = new Date();
								
				var getMessage = $('#message').val();
				
				var myCipherFrom = objVigenere.encrypt(from, "secretkey");
				var myCipherMessage = objVigenere.encrypt(getMessage, "secretkey");

				$.post(url, {
					message: myCipherMessage,
					from: myCipherFrom,
					getHours: date.getHours(),
					getMinutes: date.getMinutes()
				});
									
					$('#message').val('');
					return false;
				});
			});
						
			
			function load(){
				$.get(url + '?start=' + start, function(result){
					if(result.items){
						result.items.forEach(item => {
							start = item.id;
							$('#messages').append(renderMessage(item));

						});
						$('#messages').animate({ scrollTop: $('#messages')[0].scrollHeight}); 
					}
				load();
				});
			}	

			function renderMessage(item){
				var myPlaintextFrom = objVigenere.decrypt(item.name, "secretkey");
				var myPlaintextMessage = objVigenere.decrypt(item.message, "secretkey");

				return `<div style='text-align: left;' class='container'><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Calico_tabby_cat_-_Savannah.jpg/1200px-Calico_tabby_cat_-_Savannah.jpg" alt="Avatar"><p><b>${myPlaintextFrom.toLowerCase()}</b></p>${myPlaintextMessage.toLowerCase()}<span class="time-right">${item.hours}:${item.minutes}</span></div>`;
			}
		</script>
			<center>
				<div id="messages">
				</div>
				
				<form action="#" method="POST">
					<input type="text" id="message" autocomplete="off" autofocus placeholder="Type message..."/>
					<input type="submit" value="Wyślij"/>
					<button name="signUp" id="signUp">Export JSON</button>
				</form>
			</center>
	<script>
	const signUpButton = document.getElementById('signUp');

	signUpButton.addEventListener('click', () => {
		location.href="/export";
	});
	</script>
	</body>
</html>