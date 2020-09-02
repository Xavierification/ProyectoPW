<!DOCTYPE html>
<html>
<head>
	<title>Aplicación ChatRoom</title>
	<link rel="stylesheet" href="../library/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="custom.css">
	<script src="../library/js/jquery-3.5.1.min.js"></script>
</head>
<body>
	<div class="container">
		<h2 class="text-center" style="margin-top: 5px; padding-top: 0;">Aplicación ChatRoom</h2>
		<hr>
		<?php 
			if(isset($_POST['join'])) {
				session_start();
				require("db/users.php");
				$objUser = new users;
				$objUser->setEmail($_POST['email']);
				$objUser->setName($_POST['uname']);
				$objUser->setLoginStatus(1);
			 	$objUser->setLastLogin(date('Y-m-d h:i:s'));
			 	$userData = $objUser->getUserByEmail();
			 	if(is_array($userData) && count($userData)>0) {
			 		$objUser->setId($userData['id']);
			 		if($objUser->updateLoginStatus()) {
			 			echo "Usuario login..";
			 			$_SESSION['user'][$userData['id']] = $userData;
			 			header("location: chatroom.php");
			 		} else {
			 			echo "Fallo del login.";
			 		}
			 	} else {
				 	if($objUser->save()) {
				 		$lastId = $objUser->dbConn->lastInsertId();
				 		$objUser->setId($lastId);
						$_SESSION['user'][$lastId] = [ 
							'id' => $objUser->getId(), 
							'name' => $objUser->getName(), 
							'email'=> $objUser->getEmail(), 
							'login_status'=>$objUser->getLoginStatus(), 
							'last_login'=> $objUser->getLastLogin() 
						];

				 		echo "Usuario Registrado";
				 		header("location: chatroom.php");
				 	} else {
				 		echo "Fallo..";
				 	}
				 }
			}
		 ?>
		<div class="row join-room">
			<div class="col-md-6 col-md-offset-3">
				<form id="join-room-frm" role="form" method="post" action="" class="form-horizontal">
					<div class="form-group">
	                  	<div class="input-group">
	                        <div class="input-group-addon addon-diff-color">
	                            <span class="glyphicon glyphicon-user"></span>
	                        </div>
	                        <input type="text" class="form-control" id="uname" name="uname" placeholder="Ingresar Nombre">
	                  	</div>
	                </div>
					<div class="form-group">
	                	<div class="input-group">
	                        <div class="input-group-addon addon-diff-color">
	                            <span class="glyphicon glyphicon-envelope"></span>
	                        </div>
	                    	<input type="email" class="form-control" id="email" name="email" placeholder="Ingresar Email" value="">
	                	</div>
	                </div>
	                <div class="form-group">
	                    <input type="submit" value="UNIRSE AL CHATROOM" class="btn btn-success btn-block" id="join" name="join">
	                </div>
			    </form>
			</div>
		</div>
	</div>
</body>
</html>