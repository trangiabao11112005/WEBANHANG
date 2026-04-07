<?php include 'app/views/shares/header.php'; ?>

<!-- BANNER -->

<div id="productBanner" class="carousel slide" data-ride="carousel">

    <div class="carousel-inner">

        <?php
        $bannerProducts = array_slice($products, 0, 7);
        $i = 0;

        foreach ($bannerProducts as $product):
            if ($product->image):
        ?>

                <div class="carousel-item <?php echo ($i == 0) ? 'active' : ''; ?>">

                    <img src="/<?php echo $product->image; ?>"
                        class="d-block w-100"
                        style="height:350px;object-fit:cover;">

                    <div class="carousel-caption bg-dark p-3 rounded">

                        <h4><?php echo htmlspecialchars($product->name); ?></h4>

                        <p class="text-warning">
                            <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                        </p>

                    </div>

                </div>

        <?php
                $i++;
            endif;
        endforeach;
        ?>

    </div>

    <a class="carousel-control-prev" href="#productBanner" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>

    <a class="carousel-control-next" href="#productBanner" role="button" data-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>

</div>

<?php if (SessionHelper::isAdmin()) { ?>
    <a href="/Product/add" class="btn btn-outline-light btn-sm">
        Thêm sản phẩm
    </a>
<?php } ?>

</div>



</div>
</div>
</div>


<div class="container">
    <div class="row">

        <!-- SIDEBAR DANH MỤC -->
        <div class="col-md-3">

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-warning fw-bold">
                    Danh mục sản phẩm
                </div>

                <div class="d-flex flex-wrap gap-2 mb-4">

                    <?php foreach ($categories as $category): ?>

                        <a href="/Product?category_id=<?php echo $category->id; ?>"
                            class="btn btn-outline-primary">

                            <?php echo htmlspecialchars($category->name); ?>

                        </a>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>


        <!-- GRID SẢN PHẨM -->
        <div class="col-md-9">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="fw-bold">🛍 Danh sách sản phẩm</h2>

                <?php if (SessionHelper::isAdmin()) { ?>
                    <a href="/Product/add" class="btn btn-success">
                        + Thêm sản phẩm mới
                    </a>
                <?php } ?>

            </div>


            <div class="row row-cols-1 row-cols-md-3 g-4">

                <?php foreach ($products as $product): ?>

                    <div class="col">

                        <div class="card h-100 shadow-sm">

                            <?php if ($product->image): ?>

                                <img src="/<?php echo $product->image; ?>"
                                    class="card-img-top"
                                    style="height:200px; width:100%; object-fit:cover;">

                            <?php else: ?>

                                <img src="https://via.placeholder.com/300x200"
                                    class="card-img-top">

                            <?php endif; ?>


                            <div class="card-body d-flex flex-column">

                                <h5 class="card-title">
                                    <?php echo htmlspecialchars($product->name); ?>
                                </h5>

                                <p class="card-text text-muted small">
                                    <?php echo htmlspecialchars($product->description); ?>
                                </p>

                                <p class="fw-bold text-danger mt-auto">
                                    <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                                </p>

                                <p class="small">
                                    Danh mục:
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($product->category_name); ?>
                                    </span>
                                </p>


                                <div class="d-flex flex-wrap gap-1 mt-3">

                                    <!-- XEM -->
                                    <a href="/Product/show/<?php echo $product->id; ?>"
                                        class="btn btn-info btn-sm">
                                        Xem
                                    </a>


                                    <!-- ADMIN -->
                                    <?php if (SessionHelper::isAdmin()) { ?>

                                        <a href="/Product/edit/<?php echo $product->id; ?>"
                                            class="btn btn-warning btn-sm">
                                            Sửa
                                        </a>

                                        <a href="/Product/delete/<?php echo $product->id; ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
                                            Xóa
                                        </a>

                                    <?php } ?>


                                    <!-- USER ĐÃ LOGIN -->
                                    <?php if (SessionHelper::isLoggedIn() && !SessionHelper::isAdmin()) { ?>

                                        <a href="/Product/addToCart/<?php echo $product->id; ?>"
                                            class="btn btn-primary btn-sm">
                                            🛒 Thêm vào giỏ
                                        </a>

                                    <?php } ?>


                                    <!-- CHƯA LOGIN -->
                                    <?php if (!SessionHelper::isLoggedIn()) { ?>

                                        <a href="/account/login"
                                            class="btn btn-secondary btn-sm">
                                            🔒 Đăng nhập để mua
                                        </a>

                                    <?php } ?>

                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>