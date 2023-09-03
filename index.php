<?php


session_start();
$connect = mysqli_connect('localhost', 'root', '', 'shopping_cart');

if (isset($_POST['add_to_cart'])) {

    if (isset($_SESSION['cart'])) {

        $session_array_id = array_column($_SESSION['cart'], "id");

        if (!in_array($_GET['id'], $session_array_id)) {
            $session_array = array(
                'id' => $_GET['id'],
                "name" => $_POST['name'],
                "price" => $_POST['price'],
                "quantity" => $_POST['quantity']
            );

            $_SESSION['cart'][] = $session_array;
        }
    } else {
        $session_array = array(
            'id' => $_GET['id'],
            "name" => $_POST['name'],
            "price" => $_POST['price'],
            "quantity" => $_POST['quantity']
        );

        $_SESSION['cart'][] = $session_array;
    }
}



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>

    <div class="container_fluid">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="text-center">Shopping Cart Data</h2>
                    <div class="col-md-12">
                        <div class="row">

                            <?php

                            $query = "SELECT * FROM cart_item";
                            $result = mysqli_query($connect, $query);

                            while ($row = mysqli_fetch_array($result)) { ?>
                                <div class="col-md-4">
                                    <form method="post" action="index.php?id=<?= $row['id'] ?>">
                                        <img style='height:150px;' src="Imgs/<?= $row['image'] ?>">
                                        <h5 class="text-center"><?= $row['name']; ?></h5>
                                        <h6 class="text-center">$<?= number_format($row['price'], 2); ?></h6>
                                        <input type="hidden" name="name" value="<?= $row['name'] ?>">
                                        <input type="hidden" name="price" value="<?= $row['price'] ?>">
                                        <input type="number" name="quantity" value="1" class="form-control">
                                        <input type="submit" class="btn btn-primary btn-block my2" name="add_to_cart" value="Add To Cart">
                                    </form>
                                </div>

                            <?php }

                            ?>

                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="text-center">Item Selected</h2>

                    <?php

                    $total = 0;

                    $output = "";

                    $output .= "
                    <table class='table table-bordered table-striped'>
                    <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Item Price</th>
                    <th>Item Quantity</th>
                    <th>Item Price</th>
                    <th>Action</th>
                    ";

                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $key => $value) {
                            $output .= "
                            <tr>
                            <td>" . $value['id'] . "</td>
                            <td>" . $value['name'] . "</td>
                            <td>" . $value['price'] . "</td>
                            <td>" . $value['quantity'] . "</td>
                            <td>$" . number_format($value['price'] * $value['quantity'], 2) . "</td>
                            <td>
                            <a href='index.php?action=remove&id=" . $value['id'] . "'>
                            <button class='btn btn-danger btn-block'>Remove</button>
                            </a>
                            </td>
                            ";

                            $total = $total + $value['quantity'] * $value['price'];
                        }

                        $output .= "
                        <tr>
                        <td colspan='3'></td>
                        <td></b>Total Price</b></td>
                        <td>" . number_format($total, 2) . "</td>
                        <td>
                        <a href='index.php?action=clearall'>
                        <button class='btn btn-warning'>Clear</button>
                        </a>
                        </td>
                        </tr>
                        ";
                    }

                    echo $output;

                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php

    if (isset($_GET['action'])) {
        if ($_GET['action'] == "clearall") {
            unset($_SESSION['cart']);
        }

        if ($_GET['action'] == "remove") {

            foreach ($_SESSION['cart'] as $key => $value) {
                if ($value['id'] == $_GET['id']) {
                    unset($_SESSION['cart'][$key]);
                }
            }
        }
    }
    ?>
</body>

</html>