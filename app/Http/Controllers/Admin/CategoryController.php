<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * CategoryController constructor.
     * @param CategoryRepository $categoryRepository
     */
    public function __construct
    (
        CategoryRepository $categoryRepository
    )
    {
        $this->categoryRepository =  $categoryRepository;
    }

    /**
     * @return \Illuminate\Container\Container|mixed|object
     */
    public function index() {
        try {
            $categories = Category::orderBy('name', 'DESC')->paginate(10);
            return view('admin.page.category.index', compact('categories'));
        } catch (\Exception $exception) {
            return view('admin.page.error');
        }
    }

    /**
     * Thêm mới category
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $this->categoryRepository->create([
                'name' => $request->get('name'),
            ]);

            return redirect()->route('admin.category-index')->with('success', 'Thành Công!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.category-index')->with('error', 'Không Thành Công: ' . $exception->getMessage());
        }
    }

    /**
     * Cập nhật category
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $this->categoryRepository->update($id, [
                'name' => $request->get('name'),
            ]);

            return redirect()->route('admin.category-index')->with('success', 'Thành Công!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.category-index')->with('error', 'Không Thành Công: ' . $exception->getMessage());
        }
    }

    /**
     * Xóa category
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        try {
            $this->categoryRepository->delete($id);

            return redirect()->route('admin.category-index')->with('success', 'Thành Công!');
        } catch (\Exception $exception) {
            return redirect()->route('admin.category-index')->with('error', 'Không Thành Công: ' . $exception->getMessage());
        }
    }
}
