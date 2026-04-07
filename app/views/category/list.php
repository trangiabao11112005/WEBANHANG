<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">

<h3>Danh sách danh mục</h3>

<a href="/Category/add" class="btn btn-success mb-3">
<i class="fa fa-plus"></i> Thêm danh mục
</a>

<table class="table table-bordered table-striped">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Tên danh mục</th>
<th width="200">Thao tác</th>
</tr>
</thead>

<tbody>

<?php foreach ($categories as $category): ?>

<tr>
<td><?= $category->id ?></td>
<td><?= $category->name ?></td>

<td>

<a href="/Category/edit/<?= $category->id ?>" class="btn btn-warning btn-sm">
<i class="fa fa-edit"></i> Sửa
</a>

<a href="/Category/delete/<?= $category->id ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Bạn có chắc muốn xóa?')">

<i class="fa fa-trash"></i> Xóa
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<?php include 'app/views/shares/footer.php'; ?>