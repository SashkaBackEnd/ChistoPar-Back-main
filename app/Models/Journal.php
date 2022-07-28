<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Journal",
 *     description="Journal model",
 *     @OA\Xml(
 *         name="Journal"
 *     )
 * )
 */

class Journal extends Model
{
    protected $fillable  = ['author_id','journal_category_id','bath_id', 'category_id','type','format', 'place', 'date','contacts', 'title','description','media', 'views', 'hashtags'];

    private $id;
    
    /**
     * @OA\Property(
     *     title="Author_id",
     *     description="ID автора",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $author_id;

    /**
     * @OA\Property(
     *     title="Journal_category_id",
     *     description="ID категории журнала",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $journal_category_id;

    /**
     * @OA\Property(
     *     title="Bath_id",
     *     description="ID банного комплекса",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $bath_id;

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
     *     title="Type",
     *     description="Тип записи (1 - Вести, 2 - Статьи, 3 - обзоры, 4 - Мероприятия)",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $type;


    /**
     * @OA\Property(
     *      title="Title",
     *      description="Заголовок журнала",
     *      example="Финалисты российской премии банной индустрии"
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *      title="Place",
     *      description="Место проведения (для мероприятий)",
     *      example="Москва"
     * )
     *
     * @var string
     */
    private $place;

    /**
     * @OA\Property(
     *      title="Date",
     *      description="Время проведенмя",
     *      example="12 сентября 2022"
     * )
     *
     * @var string
     */
    private $date;

    /**
     * @OA\Property(
     *      title="Format",
     *      description="Описание статьи",
     *      example="Лекция, мастер класс"
     * )
     *
     * @var string
     */
    private $format;

    /**
     * @OA\Property(
     *      title="Contacts",
     *      description="Контакты",
     *      example="+7 (999) 999 99 99"
     * )
     *
     * @var string
     */
    private $contacts;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="Описание статьи",
     *      example="Описание статьи"
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
     *      title="Кол-во просмотров",
     *      description="Кол-во просмотров",
     *      format="int64",
     *      example=1
     * )
     *
     * @var string
     */
    private $views;
    
    /**
     * @OA\Property(
     *      title="Хештеги",
     *      description="Хештеги",
     *      example="Баня"
     * )
     *
     * @var string
     */
    private $hashtags;
    
    public function fav()
    {
        return $this->hasOne(Fav::class, 'entity_id', 'id')->where('type', 3);
    }

    use HasFactory;
}
