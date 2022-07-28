<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Format;
use App\Models\Review;
/**
 * @OA\Schema(
 *     title="Bath",
 *     description="Bath model",
 *     @OA\Xml(
 *         name="Bath"
 *     )
 * )
 */

class Bath extends Model
{
    protected $fillable  = [
        'parent_id',
        'user_id',
        'category_id',
        'name',
        'description',
        'media', 
        'operation_mode', 
        'phone', 
        'address', 
        'cash', 
        'sale',
        'price', 
        'pay_type',
        'badge',
        'site',
        'vk',
        'facebook',
        'instagram',
        'twitter',
        'fio',
        'fullname',
        'owner_email',
        'owner_phone',
        'manager_phone',
        'manager_email',
        'manager_name',
        'avatar',
        'email',
        'coordinates',
        'link',
        'main_rank_name',
        'visit_rule',
        'map'
    ];


    private $id;
    
    /**
     * @OA\Property(
     *     title="Parent_id",
     *     description="Родительский БК",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $parent_id;
    /**
     * @OA\Property(
     *     title="Category_id",
     *     description="ID категории",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $category_id;

    /**
     * @OA\Property(
     *     title="User_id",
     *     description="ID пользователя",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $user_id;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Название банного комплекса",
     *      example="Тестовый банный комлекс"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="Описание банного комплекса",
     *      example="Описание банного комплекса"
     * )
     *
     * @var string
     */
    private $description;
    
    /**
     * @OA\Property(
     *      title="Media",
     *      description="Медиафайлы",
     *      example="images/pic.jpg"
     * )
     *
     * @var string
     */
    private $media;


    /**
     * @OA\Property(
     *      title="Оperation_mode",
     *      description="Режим работы",
     *      example="Круглосуточно"
     * )
     *
     * @var string
     */
    private $operation_mode;

    /**
     * @OA\Property(
     *      title="Phone",
     *      description="Телефон",
     *      example="+7934859234"
     * )
     *
     * @var string
     */
    private $phone;


    /**
     * @OA\Property(
     *      title="Address",
     *      description="Адрес",
     *      example="г. Москва"
     * )
     *
     * @var string
     */
    private $address;

    /**
     * @OA\Property(
     *      title="Cash",
     *      description="Способ оплаты",
     *      format="int64",
     *      example=1
     * )
     *
     * @var string
     */
    private $cash;

    /**
     * @OA\Property(
     *      title="Sale",
     *      description="Скидка",
     *      format="int64",
     *      example=10
     * )
     *
     * @var string
     */
    private $sale;


    /**
     * @OA\Property(
     *      title="Price",
     *      description="Цена",
     *      format="int64",
     *      example=1500
     * )
     *
     * @var string
     */
    private $price;

    /**
     * @OA\Property(
     *      title="Pay_type",
     *      description="Способ оплаты",
     *      example=1
     * )
     *
     * @var string
     */
    private $pay_type;

    /**
     * @OA\Property(
     *      title="Badge. Бэйдж (1 - популярное, 2 - рекомендуем, 3 - выбор пользователя)",
     *      description="Бэйдж",
     *      format="int64",
     *      example=1500
     * )
     *
     * @var string
     */
    private $badge;


    /**
     * @OA\Property(
     *      title="Site",
     *      description="Сайт",
     *      example="www.some.com"
     * )
     *
     * @var string
     */
    private $site;

    /**
     * @OA\Property(
     *      title="vk",
     *      description="Вконтакте",
     *      example="www.vk.com"
     * )
     *
     * @var string
     */
    private $vk;


    /**
     * @OA\Property(
     *      title="Facebook",
     *      description="Фейсбук",
     *      example="www.facebook.com"
     * )
     *
     * @var string
     */
    private $facebook;


    /**
     * @OA\Property(
     *      title="Instagram",
     *      description="Инстаграм",
     *      example="www.instagram.com"
     * )
     *
     * @var string
     */
    private $instagram;


    /**
     * @OA\Property(
     *      title="Twitter",
     *      description="Адрес",
     *      example="www.twitter.com"
     * )
     *
     * @var string
     */
    private $twitter;



    /**
     * @OA\Property(
     *      title="Fullname",
     *      description="ФИО",
     *      example="Иванов иван иванович"
     * )
     *
     * @var string
     */
    private $fullname;


    /**
     * @OA\Property(
     *      title="Owner_email",
     *      description="Почта владельца",
     *      example="mail@mail.ru"
     * )
     *
     * @var string
     */
    private $owner_email;


    /**
     * @OA\Property(
     *      title="Owner_phone",
     *      description="Телефон владельца",
     *      example="+7 (999) 999 99 99"
     * )
     *
     * @var string
     */
    private $owner_phone;


    /**
     * @OA\Property(
     *      title="Manager_email",
     *      description="Почта управляющего",
     *      example="mail@mail.ru"
     * )
     *
     * @var string
     */
    private $manager_email;


    /**
     * @OA\Property(
     *      title="Manager_phone",
     *      description="Телефон управляющего",
     *      example="+7 (999) 999 99 99"
     * )
     *
     * @var string
     */
    private $manager_phone;

    /**
     * @OA\Property(
     *      title="Manager_name",
     *      description="ФИО управляющего",
     *      example="Иванов Иван Иванович"
     * )
     *
     * @var string
     */
    private $manager_name;


    /**
     * @OA\Property(
     *      title="Email",
     *      description="Почта",
     *      example="mail@mail.ru"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *      title="Avatar",
     *      description="Аватар БК",
     *      example="images/pic.png"
     * )
     *
     * @var string
     */
    private $avatar;
    /**
     * @OA\Property(
     *      title="Coordinates",
     *      description="Координаты БК",
     *      example=""
     * )
     *
     * @var string
     */
    private $coordinates;

    /**
     * @OA\Property(
     *      title="Link",
     *      description="Ссылка",
     *      example="/neskuchnue-bani"
     * )
     *
     * @var string
     */
    private $link;

    /**
     * @OA\Property(
     *      title="Main_rank_name",
     *      description="Название основного разряда БК",
     *      example="Нескучный разряд!"
     * )
     *
     * @var string
     */
    private $main_rank_name;

    /**
     * @OA\Property(
     *      title="Visit_rule",
     *      description="Правила посещения",
     *      example="Правила посещения"
     * )
     *
     * @var string
     */
    private $visit_rule;

    /**
     * @OA\Property(
     *      title="Map",
     *      description="Виджет яндекс карты",
     *      example="Виджет"
     * )
     *
     * @var string
     */
    private $map;


    public function childrens()
    {
        return $this->hasMany(Bath::class, 'parent_id', 'id');
    }

    public function format()
    {
        return $this->hasOne(Format::class)->orderBy('price_online', 'asc');
    }

    public function category()
    {
        return $this->hasOne(BathCategories::class, 'id', 'category_id');
    }
    
    public function service()
    {
        return $this->hasMany(BathServices::class)->with('specialist')->orderBy('created_at', 'asc');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'entity_id', 'id')->where('type', 1);
    }

    use HasFactory;

}
