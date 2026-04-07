<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">

<h3>Thêm danh mục</h3>

<?php if (!empty($error)) : ?>
<div class="alert alert-danger">
<?= $error ?>
</div>
<?php endif; ?>

<form method="POST" action="/Category/save">

<div class="mb-3">
<label class="form-label">Tên danh mục</label>
<input type="text" name="name" class="form-control">
</div>

<button class="btn btn-primary">
<i class="fa fa-save"></i> Lưu
</button>

<a href="/Category/list" class="btn btn-secondary">
Quay lại
</a>

</form>

</div>

<?php include 'app/views/shares/footer.php'; ?>