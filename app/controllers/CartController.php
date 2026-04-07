<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/helpers/SessionHelper.php');

class CartController {

    private $productModel;
    private $db;

    public function __construct(){

        SessionHelper::start();

        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);

        // chưa login thì không được dùng giỏ hàng
        if(!SessionHelper::isLoggedIn()){
            header("Location: /account/login");
            exit();
        }

        // admin không dùng giỏ
        if(SessionHelper::isAdmin()){
            die("Admin không sử dụng giỏ hàng.");
        }
    }

    /* ======================
        THÊM VÀO GIỎ
    ====================== */

    public function add($id){

        $product = $this->productModel->getProductById($id);

        if(!$product){
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }

        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id]['quantity']++;
        }else{
            $_SESSION['cart'][$id] = [
                'name'=>$product->name,
                'price'=>$product->price,
                'quantity'=>1,
                'image'=>$product->image
            ];
        }

        header("Location: /Cart");
    }

    /* ======================
        HIỂN THỊ GIỎ
    ====================== */

    public function index(){

        $cart = $_SESSION['cart'] ?? [];

        include 'app/views/product/cart.php';
    }

    /* ======================
        XOÁ SẢN PHẨM
    ====================== */

    public function remove($id){

        if(isset($_SESSION['cart'][$id])){
            unset($_SESSION['cart'][$id]);
        }

        header("Location: /Cart");
    }

    /* ======================
        XOÁ TOÀN BỘ GIỎ
    ====================== */

    public function clear(){

        unset($_SESSION['cart']);

        header("Location: /Cart");
    }

}