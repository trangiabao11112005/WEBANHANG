<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/OrderModel.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/helpers/LogHelper.php');

class ProductController {

    private $productModel;
    private $orderModel;
    private $db;

    public function __construct() {

        SessionHelper::start();

        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->orderModel = new OrderModel($this->db);
    }

    public function index(){
        $this->list();
    }

    public function list(){

        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();

        if(isset($_GET['category_id']) && $_GET['category_id'] != ''){
            $products = $this->productModel->getProductsByCategory($_GET['category_id']);
            LogHelper::log('VIEW_PRODUCTS', 'Xem sản phẩm theo danh mục: ' . $_GET['category_id']);
        }else{
            $products = $this->productModel->getProducts();
            LogHelper::log('VIEW_PRODUCTS', 'Xem tất cả sản phẩm');
        }

        require_once 'app/views/product/list.php';
    }

    public function show($id){

        $product = $this->productModel->getProductById($id);

        if($product){
            LogHelper::log('VIEW_PRODUCT', 'Xem sản phẩm ID: ' . $id);
            include 'app/views/product/show.php';
        }else{
            LogHelper::log('VIEW_PRODUCT_FAILED', 'Cố găng xem sản phẩm không tồn tại, ID: ' . $id);
            echo "Không thấy sản phẩm.";
        }
    }

    /* =========================
        ADMIN
    ==========================*/

    public function add(){

        if(!SessionHelper::isAdmin()){
            die("Bạn không có quyền truy cập");
        }

        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/add.php';
    }

    public function save(){

        if(!SessionHelper::isAdmin()){
            die("Bạn không có quyền thêm sản phẩm");
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $image = $this->uploadImage($_FILES['image']);
            }else{
                $image = "";
            }

            $result = $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            if(is_array($result)){
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            }else{
                LogHelper::log('ADD_PRODUCT', 'Thêm sản phẩm: ' . $name);
                header('Location: /Product');
            }
        }
    }

    public function edit($id){

        if(!SessionHelper::isAdmin()){
            die("Bạn không có quyền sửa");
        }

        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();

        if($product){
            include 'app/views/product/edit.php';
        }else{
            echo "Không thấy sản phẩm.";
        }
    }

    public function update(){

        if(!SessionHelper::isAdmin()){
            die("Bạn không có quyền cập nhật");
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
                $image = $this->uploadImage($_FILES['image']);
            }else{
                $image = $_POST['existing_image'];
            }

            $edit = $this->productModel->updateProduct(
                $id,
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            if($edit){
                LogHelper::log('UPDATE_PRODUCT', 'Cập nhật sản phẩm ID: ' . $id . ' - ' . $name);
                header('Location: /Product');
            }else{
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id){

        if(!SessionHelper::isAdmin()){
            die("Bạn không có quyền xoá");
        }

        if($this->productModel->deleteProduct($id)){
            LogHelper::log('DELETE_PRODUCT', 'Xóa sản phẩm ID: ' . $id);
            header('Location: /Product');
        }else{
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    /* =========================
        GIỎ HÀNG
    ==========================*/

public function addToCart($id){

    if(!SessionHelper::isLoggedIn()){
        header("Location: /account/login");
        exit();
    }

    if(SessionHelper::isAdmin()){
        echo "Admin không được phép mua hàng";
        exit();
    }

    $product = $this->productModel->getProductById($id);

    if(!$product){
        echo "Không tìm thấy sản phẩm.";
        return;
    }

    $username = $_SESSION['username'];

    if(!isset($_SESSION['cart'][$username])){
        $_SESSION['cart'][$username] = [];
    }

    if(isset($_SESSION['cart'][$username][$id])){
        $_SESSION['cart'][$username][$id]['quantity']++;
        LogHelper::log('ADD_TO_CART', 'Increased quantity for product ID: ' . $id);
    }else{
        $_SESSION['cart'][$username][$id] = [
            'name'=>$product->name,
            'price'=>$product->price,
            'quantity'=>1,
            'image'=>$product->image
        ];
        LogHelper::log('ADD_TO_CART', 'Thêm sản phẩm vào giỏ hàng ID: ' . $id);
    }

    header('Location: /Product');
}

    public function cart(){

    if(!SessionHelper::isLoggedIn()){
        header("Location: /account/login");
        exit();
    }

    $username = $_SESSION['username'];

    $cart = $_SESSION['cart'][$username] ?? [];

    include 'app/views/product/cart.php';
}

    public function orders(){
        if(!SessionHelper::isAdmin()){
            die("Bạn không có quyền truy cập");
        }

        $orders = $this->orderModel->getOrders();
        include 'app/views/product/orders.php';
    }

    public function removeFromCart($id){

    $username = $_SESSION['username'];

    if(isset($_SESSION['cart'][$username][$id])){
        unset($_SESSION['cart'][$username][$id]);
    }

    header('Location: /Product/cart');
}
public function increaseQuantity($id){

    $username = $_SESSION['username'];

    if(isset($_SESSION['cart'][$username][$id])){
        $_SESSION['cart'][$username][$id]['quantity']++;
    }

    header('Location: /Product/cart');
    exit;
}
public function decreaseQuantity($id){

    $username = $_SESSION['username'];

    if(isset($_SESSION['cart'][$username][$id])){

        if($_SESSION['cart'][$username][$id]['quantity'] > 1){
            $_SESSION['cart'][$username][$id]['quantity']--;
        }else{
            unset($_SESSION['cart'][$username][$id]);
        }
    }

    header('Location: /Product/cart');
    exit;
}

    /* =========================
        CHECKOUT
    ==========================*/

    public function checkout(){

        if(!SessionHelper::isLoggedIn()){
            header("Location: /account/login");
            exit();
        }

        include 'app/views/product/checkout.php';
    }

    public function processCheckout(){

        $username = $_SESSION['username'];

if(!isset($_SESSION['cart'][$username]) || empty($_SESSION['cart'][$username])){
    echo "Giỏ hàng trống.";
    return;
}

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
                echo "Giỏ hàng trống.";
                return;
            }

            $this->db->beginTransaction();

            try{

                $query = "INSERT INTO orders (name, phone, address)
                          VALUES (:name, :phone, :address)";

                $stmt = $this->db->prepare($query);

                $stmt->bindParam(':name',$name);
                $stmt->bindParam(':phone',$phone);
                $stmt->bindParam(':address',$address);

                $stmt->execute();

                $order_id = $this->db->lastInsertId();

                foreach($_SESSION['cart'][$username] as $product_id=>$item){

                    $query = "INSERT INTO order_details
                              (order_id, product_id, quantity, price)
                              VALUES (:order_id, :product_id, :quantity, :price)";

                    $stmt = $this->db->prepare($query);

                    $stmt->bindParam(':order_id',$order_id);
                    $stmt->bindParam(':product_id',$product_id);
                    $stmt->bindParam(':quantity',$item['quantity']);
                    $stmt->bindParam(':price',$item['price']);

                    $stmt->execute();
                }

               unset($_SESSION['cart'][$username]);

                $this->db->commit();

                LogHelper::log('CHECKOUT', 'Đơn hàng được đặt thành công, Mã đơn hàng: ' . $order_id);

                header('Location: /Product/orderConfirmation');

            }catch(Exception $e){

                $this->db->rollBack();

                echo "Lỗi: ".$e->getMessage();
            }
        }
    }

    public function orderConfirmation(){
        include 'app/views/product/orderConfirmation.php';
    }

    /* =========================
        UPLOAD IMAGE
    ==========================*/

    private function uploadImage($file){

        $target_dir = "public/images/";

        if(!is_dir($target_dir)){
            mkdir($target_dir,0777,true);
        }

        $fileName = time().'_'.$file['name'];

        $target_file = $target_dir.$fileName;

        if(move_uploaded_file($file["tmp_name"],$target_file)){
            return $target_file;
        }

        return "";
    }

    public function search()
    {
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $query = $_GET['q'];
            $products = $this->productModel->searchProducts($query);
            $categories = (new CategoryModel($this->db))->getCategories();
            LogHelper::log('SEARCH_PRODUCTS', 'Tìm kiếm: ' . $query);
            require_once 'app/views/product/list.php';
        } else {
            LogHelper::log('SEARCH_PRODUCTS', 'Tìm kiếm trống');
            header('Location: /Product/');
        }
    }

}
?>