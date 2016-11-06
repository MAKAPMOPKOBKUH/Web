<!DOCTYPE html>
<html> 
<head>
<link rel="stylesheet" type="text/css" href="css/style.css" > 
<title>Топовый сайт</title> 
</head> 

<body class="background"> 


	<div class="header">
	<div class="logo">

	<a href="index.php"><img src="img/logo.png" width="175" height="109" alt="Home page"> </a>
	
	</div>
	
	<?
            include('Menu.php');
            echo Menu::getMenu($_GET['page']);
    ?>

	</div>
	
	<div class="content">
<?
	include('Content.php');
	echo Content::getPage($_GET['page']);
?>
	
	</div>
	
</body> 


</html>