<?php include 'app/views/shares/header.php'; ?>

<h1>Giỏ hàng</h1>

<?php if (!empty($cart)): ?>

<ul class="list-group">

<?php 
$total = 0;
foreach ($cart as $id => $item): 
$total += $item['price'] * $item['quantity'];
?>

<li class="list-group-item">

<h2><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h2>

<?php if ($item['image']): ?>
<img src="/<?php echo $item['image']; ?>" alt="Product Image" style="max-width: 100px;">
<?php endif; ?>

<p>Giá: <?php echo htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8'); ?> VND</p>

<p>
Số lượng:

<a href="/Product/decreaseQuantity/<?php echo $id; ?>" 
class="btn btn-warning btn-sm">➖</a>

<strong><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></strong>

<a href="/Product/increaseQuantity/<?php echo $id; ?>" 
class="btn btn-success btn-sm">➕</a>

</p>

<p>
Thành tiền: 
<strong>
<?php echo number_format($item['price'] * $item['quantity']); ?> VND
</strong>
</p>

<!-- NÚT XÓA -->
<a href="/Product/removeFromCart/<?php echo $id; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Bạn có chắc muốn xoá sản phẩm này?')">
Xóa
</a>

</li>

<?php endforeach; ?>

</ul>

<h3 class="mt-3">
Tổng tiền: <strong><?php echo number_format($total); ?> VND</strong>
</h3>

<?php else: ?>

<p>Giỏ hàng của bạn đang trống.</p>

<?php endif; ?>

<a href="/Product" class="btn btn-secondary mt-2">Tiếp tục mua sắm</a>
<a href="/Product/checkout" class="btn btn-primary mt-2">Thanh Toán</a>

<?php include 'app/views/shares/footer.php'; ?>