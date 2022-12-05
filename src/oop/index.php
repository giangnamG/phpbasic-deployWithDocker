<?php
    require './config.php';
    if(isset($_SESSION['logged']))
        echo "<script>window.location='./app.php'</script>";
    
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
	$username = addslashes($username);
        $password = addslashes($password);
        if(empty($username) || empty($password))
            echo "<script>alert('info empty!!')</script>";
        else{
            $sql = "select * from `login-info` where username='$username'and password=MD5('$password')";
            if(mysqli_num_rows(mysqli_query($conn,$sql))){
                $_SESSION['username']=$username;
                $sql = "update `login-info` set `active`=1 where `login-info`.`username`='$username'";
                mysqli_query($conn,$sql);
                $_SESSION['logged']=1;
                echo "<script>alert('login success')</script>";
                echo "<script>window.location='./app.php'</script>";
            }else{
                echo "<script>alert('username or password is wrong')</script>";
                echo "<script>window.location='./index.php'</script>";
            }
        }
    }

    if(isset($_POST['register'])){
        $email    = $_POST['email'];
        $phone    = $_POST['phone'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $cfPasswd = $_POST['cf-password'];

        if(empty($email) || empty($phone) || empty($username) || empty($password) || empty($cfPasswd)){
            echo "<script>alert('info empty!!')</script>";
        }else{
            $partTen = "/^[A-Za-z0-9_.]{6,32}@([a-zA-Z0-9]{2,12})(.[a-zA-Z]{2,12})+$/";
            if(!preg_match($partTen ,$email))
                echo "<script>alert('Email not valid')</script>";
            else
                if($password!=$cfPasswd)
                    echo "<script>alert('password not same')</script>";
                else{
                    $sql = "select * from `login-info` where username='$username' or email='$email'";
                    if(mysqli_num_rows(mysqli_query($conn,$sql)))   
                        echo "<script>alert('user already exited !!')</script>";
                    else{
                        $sql = "insert into `login-info`(`email`,`username`,`password`,`phone`) values ('$email','$username',MD5('$password'),'$phone')"; 
                        mysqli_query($conn,$sql);
                        unset($_POST['register']);
                        echo "<script>window.location='./index.php'</script>";
                    }
                }
        }
        unset($_POST['register']);
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>week4</title>
    <link rel="shortcut icon" href="./assets/favicon.jpg">
    <link rel="stylesheet" href="./assets/index.css">
</head>
<body>
<h2>hi there</h2>
<div class="container" id="container">
	<div class="form-container sign-up-container">
		<form action="" method="post">
			<h1>Create Account</h1>
			
			<span>or use your email for registration</span>
            <input type="email" name="email" placeholder="Email" />
			<input type="text" name="username" placeholder="Username" />
			
			<input type="password" name="password" placeholder="Password" />
			<input type="password" name="cf-password" placeholder="Confirm Password" />
			<input type="text" name="phone" placeholder="Phone" />
			<button name="register">Sign Up</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form action="" method="POST">
			<h1>Sign in</h1>
			<div class="social-container">
				<a href="#" class="social" style="color: #0000CD;"><i class="fab fa-facebook-f">fb</i></a>
			</div>
			<span>or use your account</span>
			<input type="text" name="username" placeholder="Username or email" />
			<input type="password" name="password" placeholder="Password" />
			<a href="#">Forgot your password?</a>
			<button name="login">Sign In</button>
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<div class="overlay-panel overlay-right">
				<h1>Hello, Friend!</h1>
				<p>Enter your personal details and start journey with us</p>
				<button class="ghost" id="signUp">Sign Up</button>
			</div>
		</div>
	</div>
</div>
<script src="./main.js"></script>

<footer>
	<p>
		ISPClub <i class="fa fa-heart"></i> in
		<a href="https://www.facebook.com/ATTT.PTIT">here</a>.
	</p>
</footer>

</body>
</html>
