<!DOCTYPE html>
<html>
<head>
	<title>Layout 2</title>
	<link rel="stylesheet" href="grid.css">
	<script>
         setTimeout(function(){
            window.location.href = 'layout1.php';
         }, 5000);
      </script>
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

			.header {
			background-color: rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(0, 0, 0, 0.8);
			grid-row-start: 1;
			grid-row-end: 3;
			max-height: 12.5vh;
			max-width: 100vw;
			font-size: 30px;
			text-align: center;
			grid-column-start: 1;
			grid-column-end: 17;
			display: inline-block;
			overflow: hidden;
			
			}

			.body {
			background-color: rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(0, 0, 0, 0.8);
			grid-row-start: 3;
			grid-row-end: 16;
			font-size: 30px;
			text-align: center;
			grid-column-start: 1;
			grid-column-end: 11;
			overflow: hidden;
			}

			.widget1 {
			background-color: rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(0, 0, 0, 0.8);
			grid-row-start: 3;
			grid-row-end: 10;
			font-size: 30px;
			text-align: center;
			grid-column-start: 11;
			grid-column-end: 17;
			overflow: hidden;
			}
			.widget2 {
			background-color: rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(0, 0, 0, 0.8);
			grid-row-start: 10;
			grid-row-end: 16;
			font-size: 30px;
			text-align: center;
			grid-column-start: 11;
			grid-column-end: 17;
			overflow: hidden;

			}

			.footer {
			background-color: rgba(255, 255, 255, 0.8);
			border: 1px solid rgba(0, 0, 0, 0.8);
			grid-row-start: 16;
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
		<div class="header">Header</div>
		<div class="body">Content</div>
		<div class="widget1">Widget 1</div>  
		<div class="widget2">Widget 2</div>
		<div class="footer">Footer</div>
	  </div>
</body>
</html>
