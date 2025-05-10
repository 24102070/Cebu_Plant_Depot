<?php



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cebu Home Depot - Admin</title>
    <link rel="stylesheet" href="bootstrap.css">
</head>
<style>
body{

background: linear-gradient(to right, #3a5a40, #588157);
text-align: center;

}
.main{

display: flex;
height: 100vh;
align-items: center;
justify-content: center;
gap: 80px;

}
.container{

background-color: #dad7cd;
padding: 20px;
border-radius: 10px;
margin: 10px;
gap: 20px;
box-shadow: rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px;

}
.login{

margin-top: 50px;

}
#loginbtn{

background-color: #3a5a40;
padding: 15px;
margin-top: 20px;
text-align: center;
border-radius: 10px;
color: white;
text-decoration: none;
cursor: pointer;
transition: 0.2s ease-in-out;
border-style: none;

}
#loginbtn:hover{

background-color: #588157;

}
#error{

color: red;

}
</style>
<body>

    <section class="main">
        <div class="container">
            <div class="form">
                <h1>Admin Login</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
                    <div class="form-floating">
                        <input type="text" name="username" class="form-control" id="floatingInput" placeholder="Eg. Juan Dela L. Cruz" >
                        <label for="floatingInput">Username</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="floatingEmail" placeholder="name@example.com">
                        <label for="floatingEmail">Password</label>
                    </div>
                    <div class="login">
                        <button id="loginbtn" type="submit">Login</button>
                    </div>
                </form>
                <p id="error"></p>
            </div>
        </div>
    </section>

</body>
</html>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = FILTER_INPUT(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = FILTER_INPUT(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $admin_user = "admin";
    $admin_password = "admin";

    if($username == $admin_user && $password == $admin_password){

        session_start();
        $_SESSION["username"] = $username;
        header("Location: main.php");
        exit;

    }
    else{

        echo "<script>document.getElementById('error').textContent = `Incorrect Username or Password`;</script>";

    }

}


?>