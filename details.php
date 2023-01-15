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
include($_SERVER["DOCUMENT_ROOT"] . '/options/connection_database.php');
//load id from route
$id=$_GET['id'];
$name='';
$price = '';
$description='';

//select product with this id
$sql="SELECT * FROM tbl_products where id=:id";
$stm=$conn->prepare($sql);
$stm->execute([':id'=>$id]);

if($row = $stm->fetch()){
    $name = $row['name'];
    $price = $row['price'];
    $description = $row['description'];
}

//select current product`s images
$sql="select name from tbl_images 
      where product_id=:id 
      order by priority";
$stm=$conn->prepare($sql);
$stm->execute([':id'=>$id]);

$images = $stm->fetchAll();
?>
<section>
    <div class="container mt-5 mb-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="images p-3">
                                <div class="text-center p-4"> <img id="main-image" src="images/<?php echo $images[0]['name']; ?>" width="250" /> </div>
                                <div class="thumbnail text-center">
                                    <?php foreach ($images as $img){
                                        echo'<img onclick="change_image(this)" src="images/'.$img['name'].'" width="70">';
                                    }?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="product p-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center"> <i class="fa fa-long-arrow-left"></i> <span class="ml-1">Back</span> </div> <i class="fa fa-shopping-cart text-muted"></i>
                                </div>
                                <div class="mt-4 mb-3"> <span class="text-uppercase text-muted brand">Orianz</span>
                                    <h5 class="text-uppercase"><?php echo $name; ?></h5>
                                    <div class="price d-flex flex-row align-items-center"> <span class="act-price"></span>
                                        <div class="ml-2"> <small class="dis-price"><?php echo $price; ?> грн</small></div>
                                    </div>
                                </div>
                                <p class="about"><?php echo $description; ?></p>
                                <div class="sizes mt-5">
                                    <h6 class="text-uppercase">Size</h6> <label class="radio"> <input type="radio" name="size" value="S" checked> <span>S</span> </label> <label class="radio"> <input type="radio" name="size" value="M"> <span>M</span> </label> <label class="radio"> <input type="radio" name="size" value="L"> <span>L</span> </label> <label class="radio"> <input type="radio" name="size" value="XL"> <span>XL</span> </label> <label class="radio"> <input type="radio" name="size" value="XXL"> <span>XXL</span> </label>
                                </div>
                                <div class="cart mt-4 align-items-center"> <button class="btn btn-danger text-uppercase mr-2 px-4">Add to cart</button> <i class="fa fa-heart text-muted"></i> <i class="fa fa-share-alt text-muted"></i> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    function loadData(id) {
        $.ajax({
            url: "details.php",
            method: "POST",
            data: {get_data: 1, id: id},
            success: function (response) {
                console.log(response);
            }
        });
    }

    //change ccurent image, when we click on another one.
    function change_image(image){

        var container = document.getElementById("main-image");

        container.src = image.src;
    }
    document.addEventListener("DOMContentLoaded", function(event) { });
</script>

<footer class="blockquote-footer">
    <div>
        <a href="">Our facebook</a>
    </div>
</footer>
</body>
</html>