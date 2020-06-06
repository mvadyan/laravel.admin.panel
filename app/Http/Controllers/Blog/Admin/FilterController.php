<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\AdminGroupFilterAddRequest;
use App\Http\Requests\BlogAttrsFilterAddRequest;
use App\Models\Admin\AttributeGroup;
use App\Models\Admin\AttributeValue;
use App\Repositories\Admin\FilterAttrsRepository;
use App\Repositories\Admin\FilterGroupRepository;
use MetaTag;
use Illuminate\Http\Request;

/**
 * Class FilterController
 * @package App\Http\Controllers\Blog\Admin
 */
class FilterController extends AdminBaseController
{
    private $filterGroupRepository;
    private $filterAttrsRepository;

    /**
     * FilterController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->filterAttrsRepository = app(FilterAttrsRepository::class);
        $this->filterGroupRepository = app(FilterGroupRepository::class);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function attributeGroup()
    {
        $attrs_group = $this->filterGroupRepository->getAllGroupsFilter();

        MetaTag::setTags(['title' => 'Группы фильтров']);

        return view('blog.admin.filter.attribute-group', compact('attrs_group'));
    }

    /**
     * @param AdminGroupFilterAddRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function groupAdd(AdminGroupFilterAddRequest $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $group = (new AttributeGroup())->create($data);
            $group->save();

            if ($group) {
                return redirect('/admin/filter/group-add-group')
                    ->with(['success' => "Добавлена новая группа фильтров"]);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка создания новой группы фильтров'])
                    ->withInput();
            }

        } else {
            if ($request->isMethod('get')) {
                MetaTag::setTags(['title' => 'Новая группа фильтров']);
                return view('blog.admin.filter.group-add-group');
            }
        }
    }

    /**
     * @param AdminGroupFilterAddRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function groupEdit(AdminGroupFilterAddRequest $request, $id)
    {
        if (empty($id)) {
            return back()
                ->withErrors(['msg' => "Запись [{$id}] не найдена!"]);
        }

        if ($request->isMethod('post')) {
            $group = AttributeGroup::find($id);
            $group->title = $request->title;
            $group->save();

            if ($group) {
                return redirect('/admin/filter/group-filter')
                    ->with(['success' => 'Успешно изменино']);
            } else {
                back()
                    ->withErrors(['msg' => 'Ошибка изменения'])
                    ->withInput();
            }
        } else {
            if ($request->isMethod('get')) {
                $group = $this->filterGroupRepository->getInfoGroup($id);

                MetaTag::setTags(['title' => 'Редактирование группы']);

                return view('blog.admin.filter.group-edit', compact('group'));
            }
        }

    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function groupDelete($id)
    {
        if (empty($id)) {
            return back()
                ->withErrors(['msg' => "Запись [{$id}] не найдена!"]);
        }

        $count = $this->filterAttrsRepository->getCountFilterAttrsById($id);

        if ($count) {
            return back()
                ->withErrors(['msg' => 'Удаление не возможно! В группе есть атрибуты']);
        }

        $delete = $this->filterGroupRepository->deleteGroupFilter($id);

        if ($delete) {
            return back()->with(['success' => 'Удалено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function attributeFilter()
    {
        $attrs = $this->filterAttrsRepository->getAllAttrsFilter();

        MetaTag::setTags(['title' => 'фильтры']);
        return view('blog.admin.filter.attribute', compact('attrs'));
    }

    /**
     * @param BlogAttrsFilterAddRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function attributeAdd(BlogAttrsFilterAddRequest $request)
    {
        if ($request->isMethod('post')) {
            $uniqueName = $this->filterAttrsRepository->checkUnique($request->value);

            if ($uniqueName) {
                return redirect('/admin/filter/attrs-add')
                    ->withErrors(['msg' => 'Такое название фильтра уже есть!'])
                    ->withInput();
            }

            $data = $request->input();
            $attr = (new AttributeValue())->create($data);
            $attr->save();

            if ($attr) {
                return redirect('/admin/filter/attrs-add')
                    ->with(['success' => 'Добавлен новый фильтр']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка создания фильтра'])
                    ->withInput();
            }

        } else {
            if ($request->isMethod('get')) {
                $group = $this->filterGroupRepository->getAllGroupsFilter();
                MetaTag::setTags(['title' => 'Новый атрибут для фильтра']);
                return view('blog.admin.filter.attrs-add', compact('group'));
            }
        }
    }

    /**
     * @param BlogAttrsFilterAddRequest $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function attrEdit(BlogAttrsFilterAddRequest $request, $id)
    {
        if (empty($id)) {
            return back()
                ->withErrors(['msg' => "Запись [{$id}] не найдена!"]);
        }

        if ($request->isMethod('post')) {
            $attr = AttributeValue::find($id);
            $attr->value = $request->value;
            $attr->attr_group_id = $request->attr_group_id;
            $attr->save();

            if ($attr) {
                return redirect('/admin/filter/attributes-filter')
                    ->with(['success' => 'Успешно изменено']);
            } else {
                return back()
                    ->withErrors(['msg' => 'Ошибка изменения'])
                    ->withInput();

            }
        } else {
            if ($request->isMethod('get')) {
                $attr = $this->filterAttrsRepository->getInfoProduct($id);
                $group = $this->filterGroupRepository->getAllGroupsFilter();

                MetaTag::setTags(['title' => 'Редактирование фильтра']);
                return view('blog.admin.filter.attrs-edit', compact('group', 'attr'));
            }
        }
    }

    public function attrDelete($id)
    {
        if (empty($id)) {
            return back()
                ->withErrors(['msg' => "Запись [{$id}] не найдена!"]);
        }

        $delete = $this->filterAttrsRepository->deleteAttrFilter($id);

        if ($delete) {
            return redirect('/admin/filter/attributes-filter')
                ->with(['success' => 'Удалено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка удаления']);
        }
    }
}
