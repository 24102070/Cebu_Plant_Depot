<?php

include("database.php");

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
#submitbtn{

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
width: 15rem;
font-weight: bold;

}
#submitbtn:hover{

background-color: #588157;

}
.form h1{

font-size: 3rem;
margin-bottom: 30px;

}
</style>
<body>
    <section class="main">
        <div class="container">
            <div class="form">
                <h1>Remove Plant</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data" onsubmit="return confirmDelete();">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="plantname" name="plantname" required>
                        <label for="plantname" name="plantname">Plant Name</label>
                    </div>
                    <div class="login">
                        <button type="submit" id="submitbtn">Remove Plant</button>
                    </div>
                    <p id="missing"></p>
                </form>
            </div>
        </div>
    </section>
</body>
<script>
function confirmDelete(){

    return confirm("Are you sure you want to remove this plant?");

}
</script>
</html>
<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $plant_name = FILTER_INPUT(INPUT_POST, "plantname", FILTER_SANITIZE_SPECIAL_CHARS);
    $isFound = false;
    $sql = "SELECT product_name from admin_db";
    $result = mysqli_query($con, $sql);

    while($row = mysqli_fetcH_assoc($result)){

        if(strtoupper($row['product_name']) == strtoupper($plant_name)){
    
            $isFound = true;
            
            $delete = "DELETE from admin_db WHERE UPPER(product_name) = ?";
            $deleteprod = $con->prepare($delete);
            $plant_name = strtoupper($plant_name);
            $deleteprod->bind_param("s", $plant_name);
            $deleteprod->execute();
            $deleteprod->close();
    
            break;
        }
    
    }
    
    if($isFound){
    
        echo "<script>window.alert(`Plant has been deleted`);</script>";
    
    }
    elseif(!$isFound){
    
        echo "<script>window.alert(`No Plant Found!`);</script>";
    
    }

}

?>