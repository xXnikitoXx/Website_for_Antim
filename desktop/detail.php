<?php
session_start();
ob_start();
?>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="keywords" content="Antim I, Antim">
		<meta name="autors" content="Ibryam Ibryamov, Ventsislav Nenov">
		<title>Антималник</title>
		<link rel="icon" href="pic/LOGO.png">
		<link rel="stylesheet" href="css\style.css">
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		<script src="js/jquery.js"></script>
		<script src="js/materialize.js"></script>
		<script src="js/script.js"></script>
	</head>
	<body>
		<div id="menu">
			<ul>
			<ul id="dropdown1" class="dropdown-content">
					<li><a href="Profile.php">Профил</a></li>
					<li class="divider"></li>
					<li><a href="my_items.php">Мои обяви</a></li>
			</ul>
            <?php
            	if(!empty($_SESSION['image'])){
                	$i=$_SESSION['image'];
                	$l="pic/PROF/".$i;
                	echo "<div class='nav-wrapper'><il id='options'><a class='dropdown-trigger' href='Profile.php' data-target='dropdown1'><button style='border-radius: 5000px;cursor: pointer;background-color:initial;border: initial;'><img style='border-radius: 5000px;' src='$l'height='44' width='44'></button></a></il></div>";
            	}
            	else{
            	    if(empty($_SESSION['ID'])){
            	        echo "<li id='options' ><a class='waves-effect waves-light btn' href='register.php' style='width:220;margin-top:6;margin-left:6'><i class='material-icons'>Регистрирай се</i></a></li>";
            	        
                	echo "<li id='options'><a class='waves-effect waves-light btn' href='login.php' style='margin-top:6;width:100;'><i class='material-icons'>Вход</i></a></li>";
            	    }
            	    else if(!empty($_SESSION['ID'])){
            	    echo "<div class='nav-wrapper'><il id='options'><a class='dropdown-trigger' href='Profile.php' data-target='dropdown1'><button style='border-radius: 5000px;cursor: pointer;background-color:initial;border: initial;'><img style='border-radius: 5000px;' src='pic/profilePic.png'height='44' width='44'></button></a></il></div>";
            	    }
            	}
			?>
				<li id="options"><a href="team.php">За нас</a></li>
				<li id="options"><a href="questions.php">Въпроси</a></li>
					<?php
				 include 'connect.php';
				if(!empty($_SESSION['ID']))
				{
				    $id = $_SESSION['ID'];
				   
				    $conn = OpenCon();
				    $sql = "SELECT * FROM messages
				            WHERE seen = FALSE AND to_id = $id";
				    $result = $conn->query($sql);
				    $messages = 0;
				    while($row = $result->fetch_assoc())
				    {
				        $messages++;
				    }
				    if($messages > 0)
				    {
				     echo "<li id='options'><a href='messages.php' class='notification'><span>Съобщения</span><span class='badge'>$messages</span></a></li>";
				    }
				    else
				    {
				        echo "<li id='options'><a href='messages.php'>Съобщения</a></li>";
				    }
				}
				else
				{
				    echo "<li id='options'><a href='messages.php'>Съобщения</a></li>";
				}
				
				?>
				<li id="options"><a href="lost_things.php">Изгубени вещи</a></li>
				<li id="options"><a style="background-color: white;" href="index.php">Сергия</a></li>
				<li id="options" title="Добави"><a class="btn-floating btn-medium waves-effect waves-light blue pulse" href="add.php" style="margin-top: 10%;"><i class="material-icons">+</i></a></li>
				<li id="image"><img src="pic/image.png" height="45" width="45"></li>
			</ul>
		</div>
		<?php
			//връзка с базата данни
			//include 'connect.php';
			//отватяме връзка
			$conn = OpenCon();
			$item = $_GET["item"];
			//изпълнение на заявка
			$sql = "SELECT * FROM items
			LEFT JOIN images
			ON items.imageID = images.ID
			LEFT JOIN users
			ON items.userID = users.ID
			ORDER BY items.IID DESC;";
			$result = $conn->query($sql);
			$ho=0;
			//извеждане на нужната информация
			while($row = $result->fetch_assoc())
			{
			    $ho=1;
				$title = $row["title"];
				$desc = $row["description"];
				$price = $row["price"];
				$image = $row["image"];
				$date = $row["date"];
				$id = $row["IID"];
				$name = $row["name"];
				$secName = $row["secName"];
				$lastName = $row["lastName"];
				$userID = $row["userID"];
				if($item == $id)
				{
					echo "<div class='card-panel grey lighten-3' style='margin-left: 17%; transform: translate(-10%);'>
							<h3 style='text-align: center;'>$title</h3>
							<div style='text-align: center;' >
							<img style='object-fit:contain;max-height:500;max-width:100%' src='data:image/jpeg;base64,".base64_encode($image)."' class='img-thumnail' />
							</div>
						</div>
						<div class='card-panel grey lighten-3' style='margin-left: 17%; transform: translate(-10%);'>
							<h4 style='text-align: center;'>Описание</h4>
							<p>$desc</p>
						</div>
						<div class='card-panel grey lighten-3' style='margin-left: 17%; transform: translate(-10%);'>
							<h4 style='text-align: center;'>Цена</h4>
							<p>Цена: $price лева</p>
						</div>
						<div class='card-panel grey lighten-3' style='margin-left: 17%; transform: translate(-10%);'>
							<h4 style='text-align: center;'>Връзка с потребител</h4>
							<p>На: $name $secName $lastName</p>
							";
							if(isset($_SESSION["ID"]))
            	            {
							    echo"<form  method='post' action='send.php?to=$userID'>
							
								    <p>Съобщение:</p>
								    <input style='text-align: center;' placeholder='Съобщение' name='message' id='message' class='validate'>
								    <button class='btn waves-effect waves-light' type='submit' name='send' id='send'>Изпрати</button>
							    </form>";
							    
            	            }
						echo"</div>
						<div class='card-panel grey lighten-3' style='margin-left: 17%; transform: translate(-10%);'>
							<h4 style='text-align: center;'>Дата</h4>
							<p>Обявата е валидна до $date</p>
						</div>";
						break;
				}
			}
			if($ho == 0){
			    echo"<p style='text-align: center;'>Възникна проблем</p>";
			}
		?>
	</body>
</html>
<script>
$(document).ready(function(){  
      $('#send').click(function(){  
		   var message = $('#message').val();
		   
		   var filter = ["gay", "gei", "basi", "geq", "ebasi", "eba", "pedal", "pederas", "pederast", "kurva", "kurwa", "pishka", "kur", "kor", "гей", "педал", "педерас", "педераст", "курва", "пишка", "кур", "кор", "еба", "бал", "ебаси""GEY", "GEI", "BASI", "GEQ", "EBASI", "EBA", "PEDAL", "PEDERAS", "PEDERAST", "KURVA", "KURWA", "PISHKA", "KUR", "KOR", "ГЕЙ", "ПЕДАЛ", "ПЕДЕРАС", "ПЕДЕРАСТ", "КУРВА", "ПИШКА", "КУР", "КОР", "ЕБА", "ЕБАСИ"];
		   if(message == '')
		   {
				alert("Полето за съобщение е празно");
				return false;
		   }
		   else
		   {
    		   for(var i = 0; i < filter.length; i++)
    		   {
    			   if(message.includes(filter[i]))
    			   {
    				   alert("Полето за съобщение съдържа обидни думи или фрази");
    				   return false;
    				   break;
    			   }
    		   }
		   }
      });  
 });  

</script>