<?php


namespace App\Repositories\Admin;


use App\Repositories\CoreRepository;
use App\Models\Admin\AttributeGroup as Model;

/**
 * Class FilterGroupRepository
 * @package App\Repositories\Admin
 */
class FilterGroupRepository extends CoreRepository
{
    /**
     * FilterGroupRepository constructor.
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
     * @return mixed
     */
    public function getAllGroupsFilter()
    {
        return $this->getConditions()->get()->all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getInfoGroup($id)
    {
        return $this->getConditions()->find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteGroupFilter($id)
    {
        return $this->getConditions()->where('id', $id)->forceDelete();
    }
}
