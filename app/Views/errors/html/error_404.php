<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Error 404</title>
	<style>
		* {
			max-width: 100%;
		}

		body {
			background-color: #95c2de;
		}

		.mainbox {
			background-color: #95c2de;
			margin: auto;
			height: 600px;
			width: 600px;
			position: relative;
		}

		.err {
			color: #ffffff;
			font-family: 'Nunito Sans', sans-serif;
			font-size: 8rem;
		}

		.far {
			font-size: 8.5rem;
			color: #ffffff;
		}

		.err2 {
			color: #ffffff;
			font-family: 'Nunito Sans', sans-serif;
			font-size: 8rem;
		}

		.msg {
			text-align: center;
			font-family: 'Nunito Sans', sans-serif;
			font-size: 1.6rem;
		}

		a {
			text-decoration: none;
			color: white;
		}

		a:hover {
			text-decoration: underline;
		}
	</style>

	<head>
		<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@600;900&display=swap" rel="stylesheet">
		<script src="https://kit.fontawesome.com/4b9ba14b0f.js" crossorigin="anonymous"></script>
	</head>
</head>

<body>

	<body>
		<br>
		<br>
		<br>
		<br>
		<br>
		<div class="mainbox">
			<center>
				<span class="err">4</span>
				<i class="far fa-question-circle fa-spin"></i>
				<span class="err2">4</span>

				<div class="msg">The Page You are looking for is in Quarantine
					<p>Let's go to <a href="<?= getenv('app.baseURL') ?>">Dashboard</a> and try from there.</p>
				</div>
			</center>
		</div>
	</body>

</html>