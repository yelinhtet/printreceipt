<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1000DC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style type="text/css">
  	.container{
  		margin-top: 100px;
  	}
  	.excel_file{
  		padding-bottom: 10px;
  	}
  </style>
</head>
<body>
	<div class="container">
		<div class="row">
			<form method='post' enctype='multipart/form-data' action='datafetch.php'>
				<input type='file' class="excel_file" id='excel_file' name='excel' required>
				<button type='submit'>Fetch</button>
			</form>
		</div>
	</div>
</body>
</html>