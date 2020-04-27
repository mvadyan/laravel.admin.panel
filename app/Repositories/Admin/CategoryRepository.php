<?php


namespace App\Repositories\Admin;


use App\Models\Admin\Product;
use App\Repositories\CoreRepository;
use App\Models\Admin\Category as Model;
use Illuminate\Support\Collection;
use Lavary\Menu\Builder;
use Menu as LavMenu;

/**
 * Class CategoryRepository
 * @package App\Repositories\Admin
 */
class CategoryRepository extends CoreRepository
{

    /**
     * CategoryRepository constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed|string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * @param Collection $arrMenu
     * @return Builder
     */
    public function buildMenu(Collection $arrMenu): Builder
    {
        $mBuilder = lavMenu::make('MyNav', function ($m) use ($arrMenu) {
            foreach ($arrMenu as $item) {
                if ($item->parent_id == 0) {
                    $m->add($item->title, $item->id)
                        ->id($item->id);
                } else {
                    if ($m->find($item->parent_id)) {
                        $m->find($item->parent_id)
                            ->add($item->title, $item->id)
                            ->id($item->id);
                    }
                }
            }
        });

        return $mBuilder;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function checkChildren($id)
    {
        return $this->getConditions()
            ->where('parent_id', $id)
            ->count();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function checkParentsProducts($id)
    {
        return Product::where('category_id', $id)
            ->count();
    }

    public function getComboBoxCategories()
    {
        return $this->getConditions()
            ->get();
    }

    /**
     * @param $name
     * @param $parent_id
     * @return mixed
     */
    public function checkUniqueName($name, $parent_id)
    {
        return $this->getConditions()
            ->where([
                'title' => $name,
                'parent_id' => $parent_id
            ])
            ->exists();
    }
}
