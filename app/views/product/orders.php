<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">

<h2>Danh sách đơn hàng</h2>

<table class="table table-bordered">

<thead>
<tr>
<th>ID</th>
<th>Tên khách hàng</th>
<th>SĐT</th>
<th>Địa chỉ</th>
<th>Ngày đặt</th>
</tr>
</thead>

<tbody>

<?php foreach ($orders as $order): ?>

<tr>

<td><?php echo $order->id; ?></td>

<td><?php echo htmlspecialchars($order->name); ?></td>

<td><?php echo htmlspecialchars($order->phone); ?></td>

<td><?php echo htmlspecialchars($order->address); ?></td>

<td><?php echo $order->created_at; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php include 'app/views/shares/footer.php'; ?>