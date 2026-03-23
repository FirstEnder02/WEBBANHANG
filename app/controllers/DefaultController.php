<?php
class DefaultController
{
    private $db;
    private $productModel;

    public function __construct()
    {
        require_once 'app/models/ProductModel.php';
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        header("Location: /webbanhang/product/home");
        exit;
    }
}