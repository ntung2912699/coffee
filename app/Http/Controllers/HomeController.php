<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var CartRepository
     */
    protected $cartRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct
    (
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        CartRepository $cartRepository,
        OrderRepository $orderRepository
    )
    {
        $this->categoryRepository =  $categoryRepository;
        $this->productRepository =  $productRepository;
        $this->cartRepository =  $cartRepository;
        $this->orderRepository =  $orderRepository;
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function index() {
        try {
            $categories = $this->categoryRepository->getAll();
            return view('welcome', compact('categories'));
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }
}
