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

margin-top: 10px;

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
width: 25rem;

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
                <h1>Admin Page</h1>
                    <div class="login">
                        <a href="create.php"><button id="loginbtn" type="submit">Add New Product</button></a>
                    </div>
                    <div class="login">
                        <a href="update.php"><button id="loginbtn" type="submit">Update a Product</button></a>
                    </div>
                    <div class="login">
                        <a href="remove.php"><button id="loginbtn" type="submit">Remove a Product</button></a>
                    </div>
                <p id="error"></p>
            </div>
        </div>
    </section>

</body>
</html>
