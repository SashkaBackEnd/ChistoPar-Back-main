<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Specialists",
 *     description="Specialist model",
 *     @OA\Xml(
 *         name="Specialists"
 *     )
 * )
 */


class Specialist extends Model
{
    const EXPERIENCE = 8;
    const SPECIALIZATION = 4;

    protected $fillable  = [
        'user_id', 
        'bath_serices_id', 
        'bath_category_id',
        'courses', 
        'achievements',
        'name',
        'description',
        'media',
        'facebook',
        'vk',
        'instagram',
        'twitter',
        'phone',
        'email'
    ];

    private $id;

    /**
     * @OA\Property(
     *     title="Bath_serices_id",
     *     description="Услуги БК",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $bath_serices_id;

    /**
     * @OA\Property(
     *     title="Category_id",
     *     description="Категория",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $bath_category_id;

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
     *      description="Имя спецаиалиста",
     *      example="Тестовый специалист"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="Описание категории",
     *      example="Описание специалиста"
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
     *      title="Courses",
     *      description="Курсы",
     *      example="Курс1"
     * )
     *
     * @var string
     */
    private $courses;

    /**
     * @OA\Property(
     *      title="Аchievements",
     *      description="Достижение",
     *      example="Достижение1"
     * )
     *
     * @var string
     */
    private $achievements;

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
     *      title="Phone",
     *      description="Телефон",
     *      example="+7 (999) 999 99 99"
     * )
     *
     * @var string
     */
    private $phone;


    // public function bath()
    // {
    //     return $this->belongsTo(Bath::class);
    // }
    public function services()
    {
        return $this->hasManyThrough(
            BathServices::class, SpecialistServices::class,
            'specialist_id', 'id', 'id', 'service_id'
        );
    }
    

    public function bath()
    {
        return $this->hasManyThrough(
            Bath::class, SpecialistBath::class,
            'specialist_id', 'id', 'id', 'bath_id'
        );
    }
    

    public function experience()
    {
        return $this->hasOneThrough(
            SpecialistValueAttr::class, SpecialistAttrSpecialist::class,
            'specialist_id', 'id', 'id', 'specialist_value_id'
        )->where('specialist_attr_id', self::EXPERIENCE);
    }
    
    public function specialization()
    {
        return $this->hasOneThrough(
            SpecialistValueAttr::class, SpecialistAttrSpecialist::class,
            'specialist_id', 'id', 'id', 'specialist_value_id'
        )->where('specialist_attr_id', self::SPECIALIZATION);
    }

    public function reviews()
    {
        return $this->hasOne(Review::class, 'entity_id', 'id')->where('type', 2);
    }

    public function category()
    {
        return $this->hasOne(BathCategories::class, 'id', 'bath_category_id');
    }
    use HasFactory;
}
