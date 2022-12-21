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
?>
<section style="background-color: #eee;">
    <div class="container py-5">
        <div class="row">
            <?php
            $sql="SELECT p.id, p.name, p.price, img.name as image 
                  FROM tbl_products as p, tbl_images as img 
                  where p.id=img.product_id and img.priority=1";
            foreach ($conn->query($sql) as $row) {
                $id=$row['id'];
                $name=$row['name'];
                $image=$row['image'];
                $price=$row['price'];
                $images = $row['image'];
                //print_r([$id, $name, $image, $price]);
                echo '
            <div class="col-md-6 col-lg-4 mb-4 mb-md-0">
                <div class="card">
                    <img src="images/'.$images.'"
                         class="card-img-top" alt="Gaming Laptop"/>
                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">'.$name.'</h5>
                            <h5 class="text-dark mb-0">'.$price.'&nbsp;₴</h5>
                        </div>

                        <div class="mb-2 text-end">
                            <button type="button" class="btn btn-success">Buy</button>
                            <a href="details.php?id='.$id.'" class="btn btn-secondary">Details</a>
                        </div>
                        
                    </div>
                </div>
            </div>
                ';
            }
            ?>


            <div class = "modal fade" id = "myModal" tabindex = "-1" role = "dialog" aria-hidden = "true">

                <div class = "modal-dialog">
                    <div class = "modal-content">

                        <div class = "modal-header">
                            <h4 class = "modal-title">
                                Customer Detail
                            </h4>

                            <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
                                ×
                            </button>
                        </div>

                        <div id = "modal-body">
                            Press ESC button to exit.
                        </div>

                        <div class = "modal-footer">
                            <button type = "button" class = "btn btn-default" data-dismiss = "modal">
                                OK
                            </button>
                        </div>

                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->

            </div><!-- /.modal -->
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
</script>

<footer class="blockquote-footer">
    <div>
        <a href="">Our facebook</a>
    </div>
</footer>
</body>
</html>