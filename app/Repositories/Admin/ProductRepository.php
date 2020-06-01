<?php


namespace App\Repositories\Admin;


use App\Models\Admin\AttributeProduct;
use App\Models\Admin\Gallery;
use App\Models\Admin\Product;
use App\Models\Admin\RelatedProduct;
use App\Repositories\CoreRepository;
use App\Models\Admin\Product as Model;

/**
 * Class ProductRepository
 * @package App\Repositories\Admin
 */
class ProductRepository extends CoreRepository
{
    /**
     * ProductRepository constructor.
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
     * @param int $perPage
     * @return mixed
     */
    public function getLastProducts(int $perPage)
    {
        return $this->getConditions()::orderByDesc('id')
            ->limit($perPage)
            ->paginate($perPage);
    }

    /**
     * @param int $perPage
     * @return mixed
     */
    public function getAllProducts(int $perPage)
    {
        return $this->getConditions()::with('category')
            ->orderBy('title')
            ->limit($perPage)
            ->paginate($perPage);
    }

    /**
     * @param $q
     * @return mixed
     */
    public function getProducts($q)
    {
        return \DB::table('products')
            ->select('id', 'title')
            ->where('title', 'LIKE', '%' . $q . '%')
            ->limit(8)
            ->get();
    }

    /**
     * @param $name
     * @param $wmax
     * @param $hmax
     */
    public function uploadImg($name, $wmax, $hmax)
    {
        $uploadDir = 'uploads/single/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $name));
        $uploadFile = $uploadDir . $name;
        \Session::put('single', $name);
        self::resize($uploadFile, $uploadFile, $wmax, $hmax, $ext);
    }

    /**
     * @param $name
     * @param $wmax
     * @param $hmax
     */
    public function uploadGallery($name, $wmax, $hmax)
    {
        $uploadDir = 'uploads/gallery/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name']));
        $new_name = md5(time()) . ".$ext";
        $uploadFile = $uploadDir . $new_name;
        \Session::push('gallery', $new_name);

        if (@move_uploaded_file($_FILES[$name]['tmp_name'], $uploadFile)) {
            self::resize($uploadFile, $uploadFile, $wmax, $hmax, $ext);
            $res = array('file' => $new_name);
            echo json_encode($res);
        }
    }

    /**
     * @param $target
     * @param $dest
     * @param $wmax
     * @param $hmax
     * @param $ext
     */
    public static function resize($target, $dest, $wmax, $hmax, $ext)
    {
        list($w_orig, $h_orig) = getimagesize($target);
        $ratio = $w_orig / $h_orig;

        if ($wmax / $hmax > $ratio) {
            $wmax = $hmax * $ratio;
        } else {
            $hmax = $wmax / $ratio;
        }

        $img = "";

        switch ($ext) {
            case ("gif"):
                $img = imagecreatefromgif($target);
                break;
            case("png"):
                $img = imagecreatefrompng($target);
                break;
            default:
                $img = imagecreatefromjpeg($target);
        }

        $newImg = imagecreatetruecolor($wmax, $hmax);

        if ($ext == "png") {
            imagesavealpha($newImg, true);
            $transPng = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
            imagefill($newImg, 0, 0, $transPng);
        }

        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig);

        switch ($ext) {
            case("gif"):
                imagegif($newImg, $dest);
                break;
            case("png"):
                imagepng($newImg, $dest);
                break;
            default:
                imagejpeg($newImg, $dest);
        }

        imagedestroy($newImg);
    }

    public function getImg(Product $product)
    {
        clearstatcache();

        if (!empty(\Session::get('single'))) {
            $name = \Session::get('single');
            $product->img = $name;
            \Session::forget('single');
            return;
        }

        if (empty(\Session::get('single')) &&
            !is_file(WWW . '/uploads/single/' . $product->img)) {
            $product->img = null;
        }
        return;
    }

    /**
     * @param $id
     * @param $data
     */
    public function editFilter($id, $data)
    {
        $filter = \DB::table('attribute_products')
            ->where('product_id', $id)
            ->pluck('attr_id')
            ->toArray();

        /**
         * if remove filters
         */
        if (empty($data['attrs']) && !empty($filter)) {
            \DB::table('attribute_products')
                ->where('product_id', $id)
                ->delete();
            return;
        }

        /**
         * if add filters
         */
        if (empty($filter) && !empty($data['attrs'])) {
            $sql_part = '';
            foreach ($data['attrs'] as $v) {
                $sql_part .= "($v, $id),";
            }

            $sql_part = rtrim($sql_part, ',');
            \DB::insert("insert into attribute_products (attr_id, product_id) values $sql_part");
            return;
        }

        /**
         * if change filters
         */
        if (!empty($data['attrs'])) {
            $result = array_diff($filter, $data['attrs']);

            if ($result) {
                \DB::table('attribute_products')
                    ->where('product_id', $id)
                    ->delete();

                $sql_part = '';
                foreach ($data['attrs'] as $v) {
                    $sql_part .= "($v, $id),";
                }

                $sql_part = rtrim($sql_part, ',');
                \DB::insert("insert into attribute_products (attr_id, product_id) values $sql_part");
                return;
            }
        }

    }

    /**
     * @param $id
     * @param $data
     */
    public function editRelatedProduct($id, $data)
    {
        $related_product = \DB::table('related_products')
            ->select('related_id')
            ->where('product_id', $id)
            ->pluck('related_id')
            ->toArray();

        /**
         * if delete related droducts
         */
        if (empty($data['related']) && !empty($related_product)) {
            \DB::table('related_products')
                ->where('product_id', $id)
                ->delete();

            return;
        }

        /**
         * if add related products
         */

        if (empty($related_product) && !empty($data['related'])) {
            $sql_part = '';

            foreach ($data['related'] as $v) {
                $v = (int)$v;
                $sql_part .= "($id, $v),";
            }

            $sql_part = rtrim($sql_part, ',');
            \DB::insert("insert into related_products (product_id, related_id) VALUES $sql_part");
            return;
        }

        /**
         * if change related products
         */

        if (!empty($data['related'])) {
            $result = array_diff($related_product, $data['related']);

            if (!empty($result) || count($related_product) != count($data['related'])) {
                \DB::table('related_products')
                    ->where('product_id', $id)
                    ->delete();
                $sql_part = '';
                foreach ($data['related'] as $v) {
                    $sql_part .= "($id, $v),";
                }

                $sql_part = rtrim($sql_part, ',');
                \DB::insert("insert into related_products (product_id, related_id) values $sql_part");
                return;
            }
        }

    }

    /**
     * @param $id
     */
    public function saveGallery($id)
    {
        if (!empty(\Session::get('gallery'))) {
            $sql_part = '';
            foreach (\Session::get('gallery') as $v) {
                $sql_part .= "($id, '$v'),";
            }

            $sql_part = rtrim($sql_part, ',');
            \DB::insert("insert into galleries (product_id, img) values $sql_part");
            \Session::forget('gallery');
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getInfoProduct($id)
    {
        return $this->getConditions()
            ->with([
                'filterAttr',
                'gallery',
                'relatedProducts'
            ])
            ->find($id);
    }

    /**
     * @param $product
     * @return mixed
     */
    public function getFiltersProduct($product)
    {
        return $product->filterAttr->pluck('attr_id')->all();
    }

    /**
     * @param $product
     * @return mixed
     */
    public function getRelatedProducts($product)
    {
        $relatedId = $product->relatedProducts->pluck('related_id')->all();
        return $this->getConditions()::whereIn('id', $relatedId)->get();
    }

    /**
     * @param $product
     * @return mixed
     */
    public function getGallery($product)
    {
        return $product->gallery->pluck('img')->all();
    }

    /**
     * @param Model $product
     * @return bool
     */
    public function returnStatusOne(Product $product): bool
    {
        $product->status = '1';
        return $product->save();
    }

    /**
     * @param Model $product
     * @return bool
     */
    public function deleteStatusOne(Product $product): bool
    {
        $product->status = '0';
        return $product->save();
    }

    /**
     * @param Model $product
     * @return bool
     */
    public function deleteProduct(Product $product)
    {
        $relations = $this->deleteProductRelations($product);

        $singleImg = $product->img;
        $prod = $product->forceDelete();

        if ($relations && $prod && $singleImg) {
            @unlink("uploads/single/$singleImg");
        }

        return ($relations && $prod);
    }

    /**
     * @param Model $product
     * @return bool
     */
    private function deleteProductRelations(Product $product)
    {
        $product->load('filterAttr', 'relatedProducts', 'gallery');

        $filterAttr = true;
        $relatedProducts = true;
        $gallery = true;

        \DB::transaction(function () use ($product, $filterAttr, $relatedProducts, $gallery) {
            $filterAttr = $product->filterAttr->count() ?
                AttributeProduct::where('product_id', $product->id)->delete() : true;

            $relatedProducts = $product->relatedProducts->count() ?
                RelatedProduct::where('product_id', $product->id)->delete() : true;

            $galleryImg = $product->gallery->pluck('img')->all();
            $gallery = $product->gallery->count() ?
                Gallery::where('product_id', $product->id)->delete() : true;

            if ($filterAttr && $relatedProducts && $gallery && !empty($galleryImg)) {
                foreach ($galleryImg as $img) {
                    @unlink("uploads/gallery/$img");
                }
            }
        });

        return $filterAttr && $relatedProducts && $gallery;
    }

}
