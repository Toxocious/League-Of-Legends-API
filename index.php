<!DOCTYPE html>
<html>
	<head>
		<title>League of Legends API</title>

		<link rel='stylesheet' href='assets/css/default.css' />
		<link rel='stylesheet' href='assets/css/form.css' />

		<script type='text/javascript' src='assets/js/jquery-3.4.1.js'></script>
		<script type='text/javascript' src='assets/js/main.js'></script>
	</head>

	<body>
		<div class='container'>
			<div class='content'>
				<div class="form__group field">
					<input type="input" class="form__field" placeholder="Summoner Name" name="summoner" id='summoner' required />
					<label for="name" class="form__label">Summoner Name</label>

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