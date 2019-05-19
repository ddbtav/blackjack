<?php
//JF: Error messages and success messages for registering new user. Also has functiosn which print out header and footer for each page.
class Htmller
{

    static function trigger_get_messages () {
        if (isset($_GET['register_error']) && $_GET['register_error'] == "username_taken") {
            $taken_username = $_GET['username'];
            echo "This username ($taken_username) is already taken. Please login or register a new one.<br>";
        }
        
        if (isset($_GET['login_error']) && $_GET['login_error'] == "wrong_credentials") {
            $username_entered = $_GET['username'];
            echo "Login failed. Wrong username ($username_entered) or password.<br>";
        }
        
        if (isset($_GET['register']) && $_GET['register'] == "success") {
            $username_registered = $_GET['username'];
            echo "Successfully registered \"$username_registered\". Please login.<br>";
        }
        
        if (isset($_GET['fail_to_register']) && $_GET['fail_to_register'] == "psw_miss") {
            $username_tried = $_GET['username'];
            echo "Registration failed. Password mismatch for username ($username_tried).<br>";
        }
        
    }
    
    
    static function check_login() {
        if (isset($_POST["submit-login"])) {
            if (isset($_POST["username"]) && isset($_POST["password"]) && ! empty(($_POST["username"])) && ! empty($_POST["password"])) {
                echo "username is " . $_POST["username"];
                echo "<br>";
                echo "password is " . $_POST["password"];
                $username = $_POST["username"];
                $password = $_POST["password"];
                
                if (DB_connection::checkLoginCredentials($username, $password)) {
                    echo "successfully logged in ";
                    $_SESSION["user"] = strtoupper($username);
                    $userObj = new User($_SESSION['user']);
                   $_SESSION["userObj"] = $userObj;
                   $_SESSION['balance'] = $_SESSION["userObj"]->getBalance(); //  TODO: add balance fetch.
                    //reset_stats();
                    header("Location: settings.php?login=success");
                    exit();
                } else {
                    header("Location: index.php?login_error=wrong_credentials&username=$username");
                    exit();
                }
            }
        }
        
    }
    
    
    static function check_register()
    {
        if (isset($_POST["submit-register"])) {
            if (isset($_POST["username"]) && isset($_POST["password"]) && ! empty(($_POST["username"])) && ! empty($_POST["password"]) && isset($_POST["passwordRe"]) && ! empty(($_POST["passwordRe"]))) {
                echo "Submit-register username is " . $_POST["username"];
                echo "<br>";
                echo "Submit-register password is " . $_POST["password"];
                $username = $_POST["username"];
                $password = $_POST["password"];
                
                if ($password != $_POST["passwordRe"]){
                    header("Location: index.php?fail_to_register=psw_miss&username=$username");
                    exit();
                }
                
                if (DB_connection::username_exists($username)) {
                    header("Location: index.php?register_error=username_taken&username=$username");
                    exit();
                } else {
//                     DB_connection::insertIntoTable($username, $password);
//                     print_r($_SESSION);
                    if (DB_connection::insertIntoTable($username, $password)) {
                        header("Location: index.php?register=success&username=$username");
                        exit();
                    }
                }
            }
        }
    }
    
    
    static function check_register_working()
    {
        if (isset($_POST["submit-register"])) {
            if (isset($_POST["username"]) && isset($_POST["password"]) && ! empty(($_POST["username"])) && ! empty($_POST["password"]) && isset($_POST["passwordRe"]) && ! empty(($_POST["passwordRe"]))) {
                echo "Submit-register username is " . $_POST["username"];
                echo "<br>";
                echo "Submit-register password is " . $_POST["password"];
                $username = $_POST["username"];
                $password = $_POST["password"];
                
                if ($password != $_POST["passwordRe"]){
                    header("Location: index.php?fail_to_register=psw_miss&username=$username");
                    exit();
                }
                
                if (DB_connection::username_exists($username)) {
                    header("Location: index.php?register_error=username_taken&username=$username");
                    exit();
                } else {
                    if (DB_connection::insertIntoTable($username, $password)) {
                        header("Location: index.php?register=success&username=$username");
                        exit();
                    }
                }
            }
        }
    }
    
    
    
    static function print_login_form()
    {
        echo <<<_LOGIN_FORM
     <form method="POST" action=""> 
     		<input type="text" name="username" placeholder="username" required>  
     		<input type="password" name="password" placeholder="password" required>  
     		<input type="submit" value="Login" name="submit-login"> 
     </form>
        
_LOGIN_FORM;
    }

    static function print_create_user_form()
    {
        echo <<<_CREATE_USER_FORM

<form method="POST" action="">
	<table>
		<tr>
			<td>Username</td> 
			<td><input type="text" name="username" placeholder="username" maxlength="10" pattern="(?=.*[A-Za-z0-9]).{5,10}" required> (Letters and numbers only, 5-10 characters.)</td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" placeholder="password" pattern="(?=.*\d)(?=.*[A-Za-z])(?=.*[$&+,:;=?@#|'.^*()%!-]).{8,}" required> (At least one letter, one number and one special character (&,%,#) at least 8 characters.)</td>
    		</tr> 
     		<tr>
     			<td>Re-enter password</td> 
     			<td><input type="password" name="passwordRe" placeholder="re-enter password" required></td> 
     		</tr> 
     		<tr> 
     			<td><input type="submit" name="submit-register" value="Register"></td> 
     		</tr> 
     	</table> 
     </form> 
     
_CREATE_USER_FORM;
    }
        
    static function print_html_header() {
        echo <<<e

        <!DOCTYPE html>
        <html>
        <head>
          
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <style>
                .footer {
                      position: fixed;
                      left: 0;
                      bottom: 0;
                      width: 100%;
                }
            </style>
        </head>
        
        
        <body class="bg-success">
        	
         	<nav class="navbar navbar-dark bg-dark navbar-expand-md" role="navigation">
            <div class="container text-center">
            			<a class="navbar-brand" href="/">BlackJack</a>
            			
            </div>
            	</nav>
                <main class="container mt-5 bg-success">
e;
        
    }
        
    static function print_html_footer() {
        echo "<br><br><br>";
        echo <<<e
        </main>
        <footer class="footer page-footer font-small bg-dark text-center">
            <div class="text-light">&copy;2019, Dmitry & Josh</p>
        </footer>
        </body>
     </html>
e;
    }


    static function print_play_header() {
        $temp_user  = $_SESSION['user'];
        $_SESSION["balance"] = $_SESSION["userObj"]->getBalance();
        $balance = number_format($_SESSION["balance"], 2);
       
        echo <<<e
        
<nav class="navbar navbar-dark bg-dark navbar-expand-md" role="navigation">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="settings.php">Settings</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="logout.php">Logout</a>
      </li>
    </ul>
    <span class="navbar-text">
      Logged in as: $temp_user. Your balance is: $$balance.
    </span>
  </div>
</nav>


e;
        
    }

    static function print_play_again_button() {
        echo <<<e
<div class="container mt-3 text-center">
<form method="POST" action="">
<div class="form-group">
<button type="submit" name="play_again" class="btn btn-primary">Play again</button>
</form>
</div>

e;

}

    
    











}
?>    


