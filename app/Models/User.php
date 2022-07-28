<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    private $id;



    /**
     * @OA\Property(
     *     title="Avatar",
     *     description="Аватар",
     *     example="images/avatar.jpg"
     * )
     *
     * @var integer
     */
    private $avatar;

    /**
     * @OA\Property(
     *     title="Email",
     *     description="Почта",
     *     example="mail@mail.ru"
     * )
     *
     * @var integer
     */
    private $email;

    /**
     * @OA\Property(
     *     title="Phone",
     *     description="Телефон",
     *     example="+7 (999) 999 99 99"
     * )
     *
     * @var integer
     */
    private $phone;


    /**
     * @OA\Property(
     *     title="Fullname",
     *     description="Полное имя",
     *     example="Иванов Иван Иванович"
     * )
     *
     * @var integer
     */
    private $fullname;

    /**
     * @OA\Property(
     *     title="Password",
     *     description="Пароль",
     *     example="pAsSwOrD67802"
     * )
     *
     * @var integer
     */
    private $password;


    /**
     * @OA\Property(
     *     title="Role_id",
     *     description="Роль пользователя. 0 - обычный пользователь. 1 - администратор",
     *     example=0
     * )
     *
     * @var integer
     */
    private $role_id;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'api_token',
        'avatar',
        'bonus',
        'phone',
        'role_id',
        'code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function review()
    {
        return $this->hasOne(Review::class, 'user_id', 'id');
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'user_id', 'id');
    }
}
