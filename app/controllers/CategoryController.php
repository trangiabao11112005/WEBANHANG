<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SecurityMiddleware.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Hiển thị danh sách danh mục
    public function list()
    {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // Hiển thị form thêm danh mục
    public function add()
    {
        include 'app/views/category/add.php';
    }

    // Lưu danh mục mới
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = trim($_POST['name']);

            // kiểm tra dữ liệu
            if (empty($name)) {
                $error = "Tên danh mục không được để trống!";
                include 'app/views/category/add.php';
                return;
            }

            $useSecurity = SecurityMiddleware::isSecurityEnabled();
            $this->categoryModel->createCategory($name, $useSecurity);

            header("Location: /Category/list");
            exit;
        }
    }

    // Hiển thị form sửa
    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            echo "Không tìm thấy danh mục.";
            return;
        }

        include 'app/views/category/edit.php';
    }

    // Cập nhật danh mục
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $name = trim($_POST['name']);

            if (empty($name)) {
                $error = "Tên danh mục không được để trống!";
                $category = $this->categoryModel->getCategoryById($id);
                include 'app/views/category/edit.php';
                return;
            }

            $useSecurity = SecurityMiddleware::isSecurityEnabled();
            $this->categoryModel->updateCategory($id, $name, $useSecurity);

            header("Location: /Category/list");
            exit;
        }
    }

    // Xóa danh mục
    public function delete($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            echo "Danh mục không tồn tại.";
            return;
        }

        $this->categoryModel->deleteCategory($id);

        header("Location: /Category/list");
        exit;
    }
}
?>