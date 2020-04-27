<?php


namespace App\Services\Admin;


use App\Models\Admin\Category;
use Illuminate\Http\Request;

/**
 * Class CategoryService
 * @package App\Services\Admin
 */
class CategoryService
{
    /**
     * @var Request
     */
    private $request;

    /**
     * CategoryService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteCategory($id)
    {
        return Category::find($id)
            ->forceDelete();
    }

    /**
     * @param $data
     * @return Category
     */
    public function saveNewCategory($data)
    {
        $item = new Category();
        $item->fill($data)->save();

        return $item;
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function updateCategory(Category $category)
    {
        return $category->update($this->request->all());
    }

}
