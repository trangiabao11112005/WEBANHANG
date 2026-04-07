<?php include 'app/views/shares/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card shadow-lg border-0">
            <div class="card-header bg-warning text-dark text-center py-3">
                <h3 class="mb-0">✏️ Sửa sản phẩm</h3>
            </div>

            <div class="card-body p-4">

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/Product/update" enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?php echo $product->id; ?>">

                    <!-- Tên sản phẩm -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên sản phẩm</label>
                        <input type="text" name="name" class="form-control"
                            value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea name="description" class="form-control" rows="4" required><?php
                                                                                            echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8');
                                                                                            ?></textarea>
                    </div>

                    <!-- Giá -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá (VND)</label>
                        <input type="number" name="price" class="form-control" step="0.01"
                            value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>"
                            required>
                    </div>

                    <!-- Danh mục -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Danh mục</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->id; ?>"
                                    <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Hình ảnh</label>
                        <input type="file" name="image" class="form-control">

                        <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">

                        <?php if ($product->image): ?>
                            <div class="mt-3 text-center">
                                <img src="/<?php echo $product->image; ?>"
                                    class="img-thumbnail shadow-sm"
                                    style="max-width: 150px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Nút -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-warning px-4">
                            💾 Lưu thay đổi
                        </button>

                        <a href="/Product/" class="btn btn-outline-secondary px-4">
                            ← Quay lại
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>