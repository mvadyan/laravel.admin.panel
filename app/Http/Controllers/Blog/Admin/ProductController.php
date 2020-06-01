<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\AdminProductsCreateRequest;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use App\Repositories\Admin\ProductRepository;
use App\SBlog\Core\BlogApp;
use Illuminate\Http\Request;
use MetaTag;


/**
 * Class ProductController
 * @package App\Http\Controllers\Blog\Admin
 */
class ProductController extends AdminBaseController
{

    /**
     * @var ProductRepository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $productRepository;

    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->productRepository = app(ProductRepository::class);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $perPage = 10;

        $getAllProducts = $this->productRepository->getAllProducts($perPage);

        MetaTag::setTags(['title' => "Список продуктов"]);

        return view('blog.admin.product.index', compact('getAllProducts'));

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $item = new Category();

        MetaTag::setTags(['title' => "Создание нового продукта"]);

        return view('blog.admin.product.create', [
            'categories' => Category::with('children')
                ->where('parent_id', '0')
                ->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * @param AdminProductsCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminProductsCreateRequest $request)
    {
        $data = $request->input();
        $product = (new Product())->create($data);

        $id = $product->id;
        $product->status = $request->status ? '1' : '0';
        $product->hit = $request->hit ? '1' : '0';
        $product->category_id = $request->parent_id ?? '0';
        $this->productRepository->getImg($product);

        $save = $product->save();

        if ($save) {
            $this->productRepository->editFilter($id, $data);
            $this->productRepository->editRelatedProduct($id, $data);
            $this->productRepository->saveGallery($id);

            return redirect()
                ->route('blog.admin.products.create', [$product->id])
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения'])
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
        $product = $this->productRepository->getInfoProduct($id);
        $id = $product->id;
        BlogApp::get_instance()->setProperty('parent_id', $product->category_id);
        $filter = $this->productRepository->getFiltersProduct($product);
        $relatedProducts = $this->productRepository->getRelatedProducts($product);
        $images = $this->productRepository->getGallery($product);

        MetaTag::setTags(['title' => "Редактирование продукта"]);

        return view('blog.admin.product.edit', compact('product', 'filter', 'relatedProducts', 'images', 'id'),
            [
                'categories' => Category::with('children')
                    ->where('parent_id', '0')
                    ->get(),
                'delimiter' => '-',
                'product' => $product,
            ]);
    }

    /**
     * @param AdminProductsCreateRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminProductsCreateRequest $request, $id)
    {
        $product = $this->productRepository->getId($id);

        $data = $request->all();
        $result = $product->update($data);

        $product->status = $request->status ? '1' : '0';
        $product->hit = $request->hit ? '1' : '0';
        $product->category_id = $request->parent_id ?? '0';
        $this->productRepository->getImg($product);

        $save = $product->save();

        if ($result && $save) {
            $this->productRepository->editFilter($id, $data);
            $this->productRepository->editRelatedProduct($id, $data);
            $this->productRepository->saveGallery($id);

            return redirect()
                ->route('blog.admin.products.edit', [$product->id])
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

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function ajaxImage(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('blog.admin.product.include.image_single_edit');
        } else {
            $validator = \Validator::make($request->all(),
                [
                    'file' => 'image|max:5000',
                ],
                [
                    'file.image' => 'Файл должен быть картинкой (jpeg, png, gif, or svg)',
                    'file.max' => 'ошибка! Максимальный размер картинки - 5 мб!',
                ]
            );

            if ($validator->fails()) {
                return array(
                    'fail' => true,
                    'errors' => $validator->errors(),
                );
            }
            $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'uploads/single/';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('file')->move($dir, $filename);

            $wmax = BlogApp::get_instance()->getProperty('img_width');
            $hmax = BlogApp::get_instance()->getProperty('img_height');

            $this->productRepository->uploadImg($filename, $wmax, $hmax);

            return $filename;
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function gallery(Request $request)
    {
        $validator = \Validator::make($request->all(),
            [
                'file' => 'image|max:5000',
            ],
            [
                'file.image' => 'Файл должен быть картинкой (jpeg, png, gif, or svg)',
                'file.max' => 'ошибка! Максимальный размер картинки - 5 мб!',
            ]
        );

        if ($validator->fails()) {
            return array(
                'fail' => true,
                'errors' => $validator->errors(),
            );
        }

        if (isset($_GET['upload'])) {
            $wmax = BlogApp::get_instance()->getProperty('gallery_width');
            $hmax = BlogApp::get_instance()->getProperty('gallery_height');
            $name = $_POST['name'];
            $this->productRepository->uploadGallery($name, $wmax, $hmax);
        }

    }

    /**
     * @param $filename
     */
    public function deleteImage($filename)
    {
        \File::delete('uploads/single/' . $filename);
    }

    /**
     * Delete gallery image
     */
    public function deleteGallery()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $src = isset($_POST['src']) ? $_POST['src'] : null;

        if (!$id || !$src) {
            return;
        }

        if (\DB::delete("DELETE FROM galleries WHERE product_id = ? AND img = ?", [$id, $src])) {
            @unlink("uploads/gallery/$src");
            exit('1');
        }

        return;
    }


    /**
     * @param Request $request
     */
    public function related(Request $request)
    {
        $q = isset($request->q) ? htmlspecialchars(trim($request->q)) : '';
        $data['items'] = [];

        $products = $this->productRepository->getProducts($q);

        if ($products) {
            $i = 0;
            foreach ($products as $id => $title) {
                $data['items'][$i]['id'] = $title->id;
                $data['items'][$i]['text'] = $title->title;

                $i++;
            }
        }

        echo json_encode($data);
        die;
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnStatus($id)
    {
        $product = $this->productRepository->getId($id);

        $res = $this->productRepository->returnStatusOne($product);

        if ($res) {
            return redirect()
                ->route('blog.admin.products.index')
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteStatus($id)
    {
        $product = $this->productRepository->getId($id);

        $res = $this->productRepository->deleteStatusOne($product);

        if ($res) {
            return redirect()
                ->route('blog.admin.products.index')
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteProduct($id)
    {
        $product = $this->productRepository->getId($id);
        $res = $this->productRepository->deleteProduct($product);

        if ($res) {
            return redirect()
                ->route('blog.admin.products.index')
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения']);
        }
    }
}
