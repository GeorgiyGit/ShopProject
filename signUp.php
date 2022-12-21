<?php
if($_SERVER['REQUEST_METHOD']=='POST')
{
    $firstName=$_POST['firstName'];
    $lastName=$_POST['lastName'];
    $phone=$_POST['phone'];
    $email=$_POST['email'];
    $country_id=$_POST['countries'];
    $password=$_POST['password'];

    $image=$_POST['image'][0];;//$_FILES['image']['tmp_name'];
    echo("<script>console.log('PHP: " . $image . "');</script>");
    $dir_save='images/';
    $image_name=uniqid().'.png';
    $uploadfile=$dir_save.$image_name;
    if(move_uploaded_file($image,$uploadfile)){
        $dir_save = 'images/';
        $image_name = uniqid() . '.png';
        $priority=1;

        include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
        $sql = 'INSERT INTO tbl_images (name, creation_time, priority) VALUES(:name, NOW(), :priority);';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $image_name);
        $stmt->bindParam(':priority', $priority);
        $stmt->execute();

        $insert_id=$conn->lastInsertId();
        echo("<script>console.log('PHP: " . $firstName . "');</script>");
        echo("<script>console.log('PHP: " . $lastName . "');</script>");
        echo("<script>console.log('PHP: " . $phone . "');</script>");
        echo("<script>console.log('PHP: " . $password . "');</script>");
        echo("<script>console.log('PHP: " . $insert_id . "');</script>");

        include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
        $sql="INSERT INTO tbl_users (firstName, lastName, phone,email,password,image_id,country_id) VALUES (:firstName, :lastName, :phone, :email, :password,:image_id,:country_id);";
        $stmt= $conn->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':image_id', $insert_id);
        $stmt->bindParam(':country_id', $country_id);
        $stmt->execute();
        header("Location: /main.php");



        exit();
    }
    else{
        echo'Error';
    }
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="library/css/bootstrap.css">
    <link rel="stylesheet" href="library/js/bootstrap.js">
    <link rel="stylesheet" href="library/css/styles.css">
</head>
<body>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
?>
<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
$sql="select * from tbl_countries";
$stm=$conn->prepare($sql);
$stm->execute();

$countries = $stm->fetchAll();
?>
<div class="container">


<h1 class="text-center">Add product</h1>
<form action="" method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="firstName" class="form-label">First Name</label>
        <input type="text" class="form-control" id="firstName" name="firstName">
    </div>
    <div class="mb-3">
        <label for="lastName" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="lastName" name="lastName">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="mb-3">
        <label for="countries">Choose a country:</label>
        <select name="countries" id="countries" class="form-select">
            <?php foreach ($countries as $c){
                echo'<option value="'.$c['id'].'">'.$c['name'].'</option>';
            }?>
        </select>
        <br><br>
    </div>
    <div class="mb-3">
        <?php
            //include($_SERVER['DOCUMENT_ROOT'] . '/components/cropper.php');
        ?>
        <label for="image"></label>
        <input type="image" name="image" id="image">
    </div>
    <button type="submit" class="btn btn-primary">Sumbit</button>
</form>
</div>

</body>
</html>