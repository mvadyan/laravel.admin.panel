<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\AdminUserEditRequest;
use App\Models\Admin\User;
use App\Models\UserRole;
use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\UserRepository;
use App\Services\Admin\UserAdminService;
use MetaTag;

/**
 * Class UserController
 * @package App\Http\Controllers\Blog\Admin
 */
class UserController extends AdminBaseController
{

    /**
     * @var UserRepository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private $userRepository;

    /**
     * @var
     */
    private $userAdminService;

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = app(UserRepository::class);
        $this->userAdminService = app(UserAdminService::class);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $perPage = 10;
        $countUsers = MainRepository::getCountUsers();
        $allUsers = $this->userRepository->getAllUsers($perPage);

        MetaTag::setTags(['title' => 'Список Пользователей']);

        return view('blog.admin.user.index', compact('countUsers', 'allUsers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        MetaTag::setTags(['title' => 'Добавление пользователя']);

        return view('blog.admin.user.add');
    }

    /**
     * @param AdminUserEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminUserEditRequest $request)
    {
        $user = $this->userAdminService->createUser();

        if (!$user) {
            return back()
                ->withErrors(['msg' => 'Ошибка создания пользователя'])
                ->withInput();
        } else {
            $this->userAdminService->attachToRole($user);
            $user->load('roles');
        }

        if (!count($user->roles)) {
            return back()
                ->withErrors(['msg' => 'Ошибка назначения роли'])
                ->withInput();
        } else {
            return redirect()
                ->route('blog.admin.users.index')
                ->with(['success' => 'Успешно сохранено']);
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
        $perPage = 5;
        $user = $this->userRepository->getId($id);
        $user->load('roles');
        $role = $user->roles->first();
        $orders = $this->userRepository->getUserOrders($perPage, $id);

        MetaTag::setTags(['title' => 'Редактирование пользователя']);

        return view('blog.admin.user.edit', compact('user', 'role', 'orders'));
    }

    /**
     * @param AdminUserEditRequest $request
     * @param User $user
     * @param UserRole $role
     */
    public function update(AdminUserEditRequest $request, User $user, UserRole $role)
    {
        $save = $this->userAdminService->updateUser($user);

        if (!$save) {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        } else {
            $this->userAdminService->updateRole($role, $user->id);
            return redirect()
                ->route('blog.admin.users.edit', $user->id)
                ->with(['success' => 'Успешно сохранено'])
                ->withInput();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $result = $this->userAdminService->destroyUser($id);

        if ($result) {
            return redirect()
                ->route('blog.admin.users.index')
                ->with(['success' => 'Пользователь удален']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }

    }
}
