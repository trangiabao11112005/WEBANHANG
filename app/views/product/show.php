<?php include 'app/views/shares/header.php'; ?>

<div class="card shadow-lg border-0">

<div class="card-header bg-dark text-white text-center py-3">
<h2 class="mb-0">🛍 Chi tiết sản phẩm</h2>
</div>

<div class="card-body p-4">

<?php if ($product): ?>

<div class="row align-items-center">

<!-- Ảnh sản phẩm -->
<div class="col-md-6 text-center mb-4 mb-md-0">

<?php if ($product->image): ?>

<img src="/<?php echo SecurityMiddleware::isSecurityEnabled() ? htmlspecialchars($product->image) : $product->image; ?>"
class="img-fluid rounded shadow-sm"
style="max-height:350px; object-fit:cover;"
alt="<?php echo SecurityMiddleware::isSecurityEnabled() ? htmlspecialchars($product->name) : $product->name; ?>">

<?php else: ?>

<img src="https://via.placeholder.com/400x300"
class="img-fluid rounded shadow-sm"
alt="Không có ảnh">

<?php endif; ?>

</div>


<!-- Thông tin sản phẩm -->
<div class="col-md-6">

<h3 class="fw-bold mb-3">
<?php if (SecurityMiddleware::isSecurityEnabled()): ?>
    <?php echo htmlspecialchars($product->name); ?>
<?php else: ?>
    <?php echo $product->name; ?>
<?php endif; ?>
</h3>

<p class="text-muted">
<?php if (SecurityMiddleware::isSecurityEnabled()): ?>
    <?php echo nl2br(htmlspecialchars($product->description)); ?>
<?php else: ?>
    <?php echo nl2br($product->description); ?>
<?php endif; ?>
</p>

<p class="text-danger fw-bold fs-4">
💰 <?php echo number_format($product->price,0,',','.'); ?> VND
</p>

<p>
<strong>Danh mục:</strong>

<span class="badge bg-secondary">
<?php echo !empty($product->category_name)
? (SecurityMiddleware::isSecurityEnabled() ? htmlspecialchars($product->category_name) : $product->category_name)
: 'Chưa có danh mục'; ?>
</span>

</p>


<div class="mt-4 d-flex gap-2 flex-wrap">

<!-- ADMIN -->
<?php if(SessionHelper::isAdmin()) { ?>

<a href="/Product/edit/<?php echo $product->id; ?>"
class="btn btn-warning">
Sửa
</a>

<a href="/Product/delete/<?php echo $product->id; ?>"
class="btn btn-danger"
onclick="return confirm('Bạn có chắc chắn muốn xóa?');">
Xóa
</a>

<?php } ?>


<!-- USER LOGIN -->
<?php if(SessionHelper::isLoggedIn() && !SessionHelper::isAdmin()) { ?>

<a href="/Product/addToCart/<?php echo $product->id; ?>"
class="btn btn-success px-4">
🛒 Thêm vào giỏ hàng
</a>

<?php } ?>


<!-- CHƯA LOGIN -->
<?php if(!SessionHelper::isLoggedIn()) { ?>

<a href="/account/login"
class="btn btn-secondary px-4">
🔒 Đăng nhập để mua
</a>

<?php } ?>


<a href="/Product/"
class="btn btn-outline-secondary px-4">
← Quay lại
</a>

</div>

</div>
</div>

<?php else: ?>

<div class="alert alert-danger text-center">
<h4>Không tìm thấy sản phẩm!</h4>
</div>

<?php endif; ?>

</div>
</div>

<?php include 'app/views/shares/footer.php'; ?>