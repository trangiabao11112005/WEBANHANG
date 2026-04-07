<?php
require_once 'app/helpers/SessionHelper.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý sản phẩm</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* =========================
NAVBAR
=========================*/

        .navbar {
            background: linear-gradient(90deg, #ff6a00, #ff9a00);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 22px;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 600;
            transition: 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #222 !important;
            transform: translateY(-2px);
        }

        .navbar-nav {
            margin-left: auto;
        }

        /* =========================
BANNER
=========================*/

        .banner-slider img {
            height: 350px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* =========================
PRODUCT CARD
=========================*/

        .product-card {
            transition: 0.3s;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* =========================
CATEGORY MENU
=========================*/

        .category-menu {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .category-menu a {
            background: #fff3e6;
            padding: 8px 14px;
            border-radius: 20px;
            text-decoration: none;
            color: #ff6a00;
            font-weight: 600;
            transition: 0.3s;
        }

        .category-menu a:hover {
            background: #ff6a00;
            color: white;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg">

        <a class="navbar-brand" href="/Product/">
            <i class="fa-solid fa-store"></i> CellphoneB
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <form class="form-inline my-2 my-lg-0 mr-3" action="/Product/search" method="GET">
            <input class="form-control mr-sm-2" type="search" name="q" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit"><i class="fa-solid fa-search"></i></button>
        </form>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link" href="/Product/">
                        <i class="fa-solid fa-list"></i> Sản phẩm
                    </a>
                </li>

                <?php if (SessionHelper::isAdmin()) { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/Product/add">
                            <i class="fa-solid fa-plus"></i> Thêm sản phẩm
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/Category/List/">
                            <i class="fa-solid fa-tags"></i> Danh mục
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/Product/orders/">
                            <i class="fa-solid fa-box"></i> Đơn hàng
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/Admin/">
                            <i class="fa-solid fa-user-shield"></i> Admin
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/Admin/security">
                            <i class="fa-solid fa-shield-alt"></i> Bảo mật
                        </a>
                    </li>

                <?php } ?>

                <?php if (SessionHelper::isLoggedIn() && !SessionHelper::isAdmin()) { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/Product/cart">
                            <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                        </a>
                    </li>

                <?php } ?>

                <li class="nav-item">

                    <?php
                    if (SessionHelper::isLoggedIn()) {
                        echo "<a class='nav-link' href='/Product/'><i class='fa-solid fa-user'></i> " . $_SESSION['username'] . "</a>";
                    } else {
                        echo "<a class='nav-link' href='/account/login'><i class='fa-solid fa-user'></i> Đăng nhập</a>";
                    }
                    ?>

                </li>

                <?php if (SessionHelper::isLoggedIn()) { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/account/logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                        </a>
                    </li>

                <?php } ?>

            </ul>

        </div>

    </nav>

    <!-- BANNER CONTAINER -->
    <div class="container mt-3">

        <div id="productBanner" class="carousel slide banner-slider mb-4" data-ride="carousel">

            <div class="carousel-inner">

                <!-- Banner ảnh sẽ được load từ list.php -->

            </div>

            <a class="carousel-control-prev" href="#productBanner" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>

            <a class="carousel-control-next" href="#productBanner" role="button" data-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>

        </div>

    </div>

    <div class="container mt-4">