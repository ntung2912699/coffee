<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Product\ProductOptionRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductOptionRepository
     */
    protected $productOptionRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductController constructor.
     * @param ProductRepository $productRepository
     * @param ProductOptionRepository $productOptionRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct
    (
        ProductRepository $productRepository,
        ProductOptionRepository $productOptionRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->productRepository =  $productRepository;
        $this->productOptionRepository =  $productOptionRepository;
        $this->categoryRepository =  $categoryRepository;
    }

    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        try {
            $products = $this->productRepository->getAll();
            $categories = $this->categoryRepository->getAll();
            $attributes = $this->productOptionRepository->getAll();
            $attribute = $attributes->groupBy('attribute_name');

        return view('admin.page.product.index', compact('products', 'categories', 'attribute'));
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }

    /**
     * Store a newly created product in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $formattedPrice = $request->get('price');
        $priceFormat = str_replace([',', ' VNĐ'], '', $formattedPrice);
        $decimalPrice = floatval($priceFormat);
        $formattedDecimalPrice = number_format($decimalPrice, 2, '.', '');

        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:category,id',
            'description' => 'nullable|max:1000',
            'attribute_name' => 'nullable|array'
        ]);

        $attribute_name = null;
        if (!empty($request->attribute_name) && count($request->attribute_name) > 0) {
            $attribute_name = implode(",", $request->attribute_name);
        }

        try {
            // Create the product
            $this->productRepository->create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'price' => $formattedDecimalPrice,
                'description' => $request->description,
                'attribute_name' => $attribute_name
            ]);
            return redirect()->route('admin.product-index')->with('success', 'Thêm sản phẩm thành công!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.product-index')->with('error', 'Lỗi khi thêm sản phẩm: ' . $exception->getMessage());
        }
    }

    /**
     * Update the specified product in the database.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponsesd
     */
    public function update(Request $request, $id)
    {
        // Lấy giá và xử lý
        $formattedPrice = $request->get('price');
        $priceFormat = str_replace([',', ' VNĐ'], '', $formattedPrice);
        $decimalPrice = floatval($priceFormat);
        $formattedDecimalPrice = number_format($decimalPrice, 2, '.', '');

        // Kiểm tra và validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
            'description' => 'nullable|string|max:1000',
            'attribute_name' => 'nullable|array',
        ]);

        try {
            // Cập nhật thông tin sản phẩm
            $this->productRepository->update($id, [
                'name' => $request->get('name'),
                'category_id' => $request->get('category_id'),
                'price' => $formattedDecimalPrice,
                'description' => $request->get('description'),
                'attribute_name' => $request->get('attribute_name') ? implode(',', $request->get('attribute_name')) : null,
            ]);

            return redirect()->route('admin.product-index')->with('success', 'Sản phẩm đã được cập nhật!');
        } catch (\Exception $e) {
            return redirect()->route('admin.product-index')->with('error', 'Lỗi khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from the database.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $this->productRepository->delete($id);
            return redirect()->route('admin.product-index')->with('success', 'Sản phẩm đã được xóa!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.product-index')->with('error', 'Lỗi khi xóa sản phẩm: ' . $exception->getMessage());
        }
    }
}

