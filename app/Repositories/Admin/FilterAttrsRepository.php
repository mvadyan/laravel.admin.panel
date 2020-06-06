<?php


namespace App\Repositories\Admin;


use App\Repositories\CoreRepository;
use App\Models\Admin\AttributeValue as Model;

/**
 * Class FilterAttrsRepository
 * @package App\Repositories\Admin
 */
class FilterAttrsRepository extends CoreRepository
{
    /**
     * FilterAttrsRepository constructor.
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
     * @param $id
     * @return mixed
     */
    public function getCountFilterAttrsById($id)
    {
        return $this->getConditions()->where('attr_group_id', $id)->count();
    }

    /**
     * @return mixed
     */
    public function getAllAttrsFilter()
    {
        return $this->getConditions()->with('filter')->paginate(10);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function checkUnique($name)
    {
        return $this->getConditions()->where('value', $name)->count();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getInfoProduct($id)
    {
        return $this->getConditions()->find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteAttrFilter($id)
    {
        return $this->getConditions()->where('id', $id)->forceDelete();
    }
}
