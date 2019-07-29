<!DOCTYPE html>
<html>
	<head>
		<title>League of Legends API</title>

		<link rel='stylesheet' href='assets/css/default.css' />

		<script type='text/javascript' src='assets/js/jquery-3.4.1.js'></script>
		<script type='text/javascript' src='assets/js/main.js'></script>
	</head>

	<body>
		<div class='container'>
			<div class='content'>
				<div class='main-form'>
					<input type='text' name='summoner' placeholder='Summoner Name' value='absol' />

					<select name='region'>
						<option value='NA'>NA</option>
						<option value='EUW'>EUW</option>
						<option value='EUNE'>EUNE</option>
						<option value='JP'>JP</option>
					</select>

					<br /><br />
					
					<button onclick='FetchSummoner();'>Fetch Summoner</button>
				</div>
			</div>

			<div class='bg-init'></div>
			<div class='css-loader'>
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
	</body>
</html>