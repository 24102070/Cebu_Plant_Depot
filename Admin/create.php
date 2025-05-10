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
                <h1>Plant Information</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-floating">
                        <input type="file" class="form-control" id="submitimage" name="image" required>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" id="plantname" name="plantname" required>
                        <label for="plantname" name="plantname">Name</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="plantprice" min="1" value="1" step=".01" name="plantprice" required>
                        <label for="plantprice" name="plantprice">Price</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="plantquantity" min="1" value="1" name="plantquantity" required>
                        <label for="plantquantity" name="plantquantity">Quantity</label>
                    </div>
                    <div class="login">
                        <button type="submit" id="submitbtn">Add</button>
                    </div>
                    <p id="missing"></p>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $plantname = FILTER_INPUT(INPUT_POST, "plantname", FILTER_SANITIZE_SPECIAL_CHARS);
    $plantprice = FILTER_INPUT(INPUT_POST, "plantprice", FILTER_VALIDATE_FLOAT);
    $plantquantity = FILTER_INPUT(INPUT_POST, "plantquantity", FILTER_VALIDATE_INT);
    $plant_available = true;
    $image;
    $imgContent = NULL;    

    if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0){

        $image = $_FILES['image']['tmp_name'];
        $imgContent = file_get_contents($image);

    }

    $sql = "INSERT INTO admin_db (product_name, product_price, product_availability, product_quantity, product_image)
            VALUES ( ?, ?, ?, ?, ?)";

    $statement = $con->prepare($sql);

    if(!$statement){

        die("Error: " . $con->error);

    }

    $null = NULL;

    $statement->bind_param('sdiib', $plantname, $plantprice, $plant_available, $plantquantity, $null);
    $statement->send_long_data(4, $imgContent);

    if($statement->execute()){

        echo "<script>window.alert(`Upload Successful`);</script>";

    }
    else{

        echo "<script>window.alert(`Upload Unsuccessful, Please Try Again!`);</script>";

    }

}

?>