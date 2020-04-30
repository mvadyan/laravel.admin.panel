<?php


namespace App\Services\Admin;

use App\Models\Admin\User;
use App\Models\UserRole;
use Illuminate\Http\Request;


/**
 * Class UserAdminService
 * @package App\Services\Admin
 */
class UserAdminService
{
    /**
     * @var Request
     */
    private $request;

    /**
     * UserAdminService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    /**
     * @return mixed
     */
    public function createUser()
    {
        return User::create([
            'name' => $this->request->input('name'),
            'email' => $this->request->input('email'),
            'password' => bcrypt($this->request->input('password')),
        ]);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function attachToRole($user)
    {
        $user->roles()->attach($this->request->input('role'));

        return ($user);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function updateUser($user)
    {
        $user->name = $this->request->input('name');
        $user->email = $this->request->input('email');
        $user->email = $this->request->input('email');
        $this->request->input('password') == null ?: $user->password = bcrypt($this->request->input('password'));

        return $user->save();
    }

    /**
     * @param $role
     * @param $userId
     * @return mixed
     */
    public function updateRole($role, $userId)
    {
        $role->where('user_id', $userId)->update([
            'role_id' => (int)$this->request->input('role'),
        ]);
    }

    public function destroyUser($id)
    {
        return User::destroy($id);
    }
}
