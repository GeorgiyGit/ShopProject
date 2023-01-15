<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //load data from form
    echo "POST REQUEST SERVER";
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    //save product
    include_once($_SERVER['DOCUMENT_ROOT'] . '/options/connection_database.php');
    $sql = 'INSERT INTO tbl_products (name, price, creation_time, description, owner_id) VALUES (:name, :price, NOW(), :description,5);';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    $insert_id=$conn->lastInsertId();

    $images = $_POST['images'];
    $count=1;
    //save images
    foreach ($images as $base64) {
        $dir_save = 'images/';
        $image_name = uniqid() . '.png';
        $uploadfile = $dir_save . $image_name;
        list(, $data) = explode(',', $base64);
        $data = base64_decode($data);
        file_put_contents($uploadfile, $data);
        $sql = 'INSERT INTO tbl_images (name, creation_time, priority, product_id) VALUES(:name, NOW(), :priority, :product_id);';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $image_name);
        $stmt->bindParam(':priority', $count);
        $stmt->bindParam(':product_id', $insert_id);
        $stmt->execute();
        $count++;
    }

    header("Location: /main.php");
    exit();

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Головна сторінка</title>
    <link rel="stylesheet" href="library/css/bootstrap.css">
    <link rel="stylesheet" href="library/js/bootstrap.js">
    <link rel="stylesheet" href="library/css/styles.css">
    <link rel="stylesheet" href="css/font-awesome.css">
</head>
<body>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
?>
<h1 class="text-center">Add product</h1>
<form class="col-md-6 offset-md-3" enctype="multipart/form-data" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" min="0" step="10">
    </div>
    <div class="mb-3">
        <div class="container">
            <div class="row" id="list_images">
                <div class="col-md-3" id="selectImages">
                    <label for="image" style="cursor: pointer" class="form-label text-success">
                        <i class="fa fa-plus" style="font-size: 120px;" aria-hidden="true"></i>
                    </label>
                    <input type="file"
                           class="form-control d-none"
                           id="image" multiple>
                </div>
            </div>
        </div>

    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" cols="30" rows="10" class="form-control" id="description"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Create</button>
</form>

<script src="library/js/bootstrap.js"></script>
<script src="library/js/jquery-3.6.2.min.js"></script>
<script>
    //function for creating unique id
    function uuidv4() {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    //Add images
    $(function () {
        const image = document.getElementById('image');

        image.onchange = function (e) {
            const files = e.target.files;

            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();

                reader.addEventListener('load', function () {
                    const base64 = reader.result;

                    const id = uuidv4();
                    //draw images
                    const data = `
                        <div class="row">
                            <div class="col-6">
                                <div class="fs-4 ms-2">
                                    <label for="${id}">
                                        <i class="fa fa-pencil" style="cursor: pointer;" aria-hidden="true"></i>
                                    </label>
                                    <input type="file" class="form-control d-none edit" id="${id}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-end fs-4 text-danger me-2 remove">
                                    <i class="fa fa-times" style="cursor: pointer" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                <div>
                    <img src="${base64}" id="${id}_image" width="100%">
                    <input type="hidden" id="${id}_file" value="${base64}" name="images[]">
                </div>
                `;
                    const item = document.createElement('div');

                    item.className = 'col-md-3 item-image';
                    item.innerHTML = data;

                    $("#selectImages").before(item);

                });
                const file = files[i];
                reader.readAsDataURL(file);
            }
            image.value = "";
        }


        //remove image from images
        $("#list_images").on("click", '.remove', function () {
            $(this).closest(".item-image").remove();
        });

        let edit_id = 0;
        const reader = new FileReader();
        reader.addEventListener("load", () => {
            const base64 = reader.result;
            document.getElementById(`${edit_id}_image`).src = base64;
            document.getElementById(`${edit_id}_file`).value = base64;
        });
        //edit image
        $("#list_images").on("change", '.edit', function (e) {
            edit_id = e.target.id;
            const file = e.target.files[0];
            reader.readAsDataURL(file);
            this.value = "";
        });

    });
</script>
</body>
</html>