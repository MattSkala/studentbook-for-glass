
<?php

	include_once "URLRequest.php";
  include_once "util.php";

	$msb_api = "http://dev.mystudentbook.com/api2";

	class Message extends Exception{private $type = "info"; public function __construct($msg, $type="info"){parent::__construct($msg, 0);$this->type= $type;}public function getType(){return $this->type;}}
	$messages = Array();
	if(isset($_GET["login"])){
		$success = false;
		try{

			session_start();
		
			/*MyStudentsBook Login*/
			if(isset($_POST["user"]) && isset($_POST["pass"])){
				$login = json_decode(URLRequest::request($msb_api."/login",null, Array("email"=>$_POST["user"], "password"=>$_POST["pass"])));
				if($login->success){
					$_SESSION["msb_token"] = $login->token;
					header("Location: oauth2callback.php");
					$success = true;
				}else{
					throw new Message("Login error","error");
				}
			}


		}catch(Message $e){
			$messages[] = $e;
		}catch(Exception $e){
			echo "Error: ".$e->getMessage();
		}
		if($success==true){

			exit;
		}

	}

  if(isset($_GET["logout"])){
    @session_start();
    if(isset($_SESSION["userid"])){
      remove_credentials($_SESSION["userid"]);
    }
    unset($_SESSION);
  }

	if(isset($_GET["success"])){
		$messages[] = new Message("You are logged to myStudentsBook.com for Glass", "success");
	}
?>

<!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MyStudentsBook for Glass</title>

    <!-- Bootstrap core CSS -->
    <link href="static/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="static/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  	

    <div class="container">

    <?php foreach($messages  as $msg){ ?>
  		<div class="alert alert-<?php echo $msg->getType(); ?>"><?php echo $msg->getMessage(); ?></div>
  	<?php } ?>

      <form class="form-signin" action="?login" method="POST">
        <h2 class="form-signin-heading">Login</h2>
        <input type="text" name="user" class="form-control" placeholder="E-mail" autofocus>
        <input type="password" name="pass" class="form-control" placeholder="Heslo">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
