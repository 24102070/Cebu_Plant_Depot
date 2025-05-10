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
                <h1>Update Plant Information</h1>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="plantname" name="plantname" required>
                        <label for="plantname" name="plantname">Enter Plant Name</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="plantprice" min="1" value="1" step=".01" name="plantprice" required>
                        <label for="plantprice" name="plantprice">Price</label>
                    </div>
                    <div class="form-floating">
                        <input type="number" class="form-control" id="plantquantity" min="0" value="0" name="plantquantity" required>
                        <label for="plantquantity" name="plantquantity">Quantity</label>
                    </div>
                    <div class="login">
                        <button type="submit" id="submitbtn">Update</button>
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

$plant = FILTER_INPUT(INPUT_POST, "plantname", FILTER_SANITIZE_SPECIAL_CHARS);
$price = FILTER_INPUT(INPUT_POST, "plantprice", FILTER_VALIDATE_FLOAT);
$quantity = FILTER_INPUT(INPUT_POST, "plantquantity", FILTER_VALIDATE_INT);
$isFound = false;
$sql = "SELECT product_name from admin_db";
$result = mysqli_query($con, $sql);
$availability = 1;

while($row = mysqli_fetcH_assoc($result)){

    if(strtoupper($row['product_name']) == strtoupper($plant)){

        if($quantity == 0){
            $availability = 0;
        }

        $updateSql = "UPDATE admin_db 
                    SET product_price = ?, product_quantity = ?, product_availability = ? 
                    WHERE UPPER(product_name) = ?";

        $update = $con->prepare($updateSql);
        $update->bind_param("diis", $price, $quantity, $availability, $plant);
        $update->execute();
        $update->close();
        $isFound = true;

        break;
    }

}

if($isFound){

    echo "<script>window.alert(`Plant Information Updated`);</script>";

}
elseif(!$isFound){

    echo "<script>window.alert(`No Plant Found!`);</script>";

}

}

?>