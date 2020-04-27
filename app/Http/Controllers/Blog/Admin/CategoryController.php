<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Admin\Category;
use App\Repositories\Admin\CategoryRepository;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use MetaTag;

/**
 * Class CategoryController
 * @package App\Http\Controllers\Blog\Admin
 */
class CategoryController extends AdminBaseController
{
    /**
     * @var CategoryRepository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $categoryRepository;

    /**
     * @var CategoryService|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $categoryService;

    /**
     * @var Request
     */
    private $request;

    /**
     * CategoryController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct();
        $this->categoryRepository = app(CategoryRepository::class);
        $this->categoryService = app(CategoryService::class);
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $arrMenu = Category::all();

        $menu = $this->categoryRepository->buildMenu($arrMenu);

        MetaTag::setTags(['title' => 'Список категорий']);

        return view('blog.admin.category.index', ['menu' => $menu]);
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function myDel()
    {
        $this->validate($this->request, [
            'id' => 'required|integer',
        ]);

        $id = $this->request->input('id');
        $children = $this->categoryRepository->checkChildren($id);

        if ($children) {
            return back()->withErrors(['msg' => 'Удаление невозможно, в категории есть вложенние категории']);
        }

        $parents = $this->categoryRepository->checkParentsProducts($id);

        if ($parents) {
            return back()->withErrors(['msg' => 'Удаление невозможно, в категории есть товары']);
        }

        $result = $this->categoryService->deleteCategory($id);

        if ($result) {
            return redirect()
                ->route('blog.admin.categories.index')
                ->with(['success' => 'Запись удалена']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка Удаления']);
        }

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $item = new Category();
        $categoryList = $this->categoryRepository->getComboBoxCategories();

        MetaTag::setTags(['title' => 'Создание новой категории']);

        return view('blog.admin.category.create', [
            'categories' => Category::with('children')
                ->where('parent_id', '0')
                ->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * @param CategoryUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryUpdateRequest $request)
    {
        $name = $this->categoryRepository->checkUniqueName($request->title, $request->parent_id);

        if ($name) {
            return back()
                ->withErrors(['msg' => 'Не может быть в одной категории двух одинаковых.
                 Выбирите другую категорию'])
                ->withInput();
        }

        $item = $this->categoryService->saveNewCategory($request->input());

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.create', [$item->id])
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка Сохранения'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $item = $this->categoryRepository->getId($id);

        MetaTag::setTags(['title' => 'Редактирование категории']);

        return view('blog.admin.category.edit', [
            'categories' => Category::with('children')
                ->where('parent_id', '0')
                ->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * @param CategoryUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = $this->categoryRepository->getId($id);
        $result = $this->categoryService->updateCategory($category);

        if ($result) {
            return redirect()
                ->route('blog.admin.categories.edit', $category->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
