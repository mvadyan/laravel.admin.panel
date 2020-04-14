<?php


namespace App\Repositories;

/**
 * Class CoreRepository
 * @package App\Repositories
 */
abstract class CoreRepository
{
    /**
     * @var mixed
     */
    protected $model;

    /**
     * CoreRepository constructor.
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass();

    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    protected function getConditions()
    {
        return clone $this->model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getId($id)
    {
        return $this->getConditions()->find($id);
    }

    /**
     * @param bool $get
     * @param null $id
     * @return int|null
     * @throws \Exception
     */
    public function getRequestId($get = true, $id = null)
    {
        if ($get) {
            $data = $_GET;
        } else {
            $data = $_POST;
        }

        $id = !empty($data[$id]) ? (int)$data[$id] : null;

        if (!$id) {
            throw new \Exception('Проверить id', 404);
        }

        return $id;
    }
}
