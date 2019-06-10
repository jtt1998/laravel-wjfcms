<?php

namespace App\Models;

use App\Http\Traits\TraitsModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use TraitsModel;

    /**
     * @var array
     */
    public static $sex = [
        -1 => '保密',
        0 => '男',
        1 => '女'
    ];

    /**
     * @var array
     */
    public static $status = [
        0 => '禁用',
        1 => '正常'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account', 'username', 'password', 'tel', 'email', 'sex', 'status', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/10
     * Time: 22:13
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {
        Validator::make(
            $attributes, [
                'username' => 'required|string|max:255',
                'account' => 'required|max:100|unique:admins',
                'password' => 'required|string|min:6',
            ]
        )->validate();
        $attributes['password'] = Hash::make($attributes['password']);
        return static::query()->create($attributes);
    }

    /**
     * Description:
     * User: Vijay
     * Date: 2019/6/10
     * Time: 22:12
     * @param array $attributes
     * @param array $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        if (isset($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }
        if (isset($attributes['account'])) {
            Validator::make(
                $attributes, [
                    'account' => Rule::unique('admins')->where(function ($query) {
                        return $query->where('id', '!=', $this->id);
                    })
                ]
            )->validate();
        }
        return parent::update($attributes, $options); // TODO: Change the autogenerated stub
    }
}
