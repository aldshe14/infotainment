<!DOCTYPE html>
<html>
<head>
	<title>Layout 4</title>
	<link rel="stylesheet" href="grid.css">
	<style>
		body{
			padding: 0px;
			margin: 0px;
			}
			.grid-container {
			display: grid;
			grid-template-columns: 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw 6.25vw ;
			grid-template-rows: 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh 6.25vh;
			background-color: #2196F3;
			padding: 0px;
			margin: 0px;
			}

			.body {
			background-color: rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(0, 0, 0, 0.8);
			grid-row-start: 1;
			grid-row-end: 17;
			font-size: 30px;
			text-align: center;
			grid-column-start: 1;
			grid-column-end: 17;
			overflow: hidden;
			}

			

	</style>
</head>
<body>
<div class="grid-container">
		<div class="body">Header</div>
	  </div>
</body>
</html>
