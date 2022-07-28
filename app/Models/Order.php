<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Order",
 *     description="Order model",
 *     @OA\Xml(
 *         name="Order"
 *     )
 * )
 */
class Order extends Model
{
    protected $fillable  = ['user_id', 'bath_id',  'specialist_id', 'bath_formats_ids', 'comment', 'duration' ,'date_from','date_to','status', 'price', 'pay_type'];

 
    private $id;

    /**
     * @OA\Property(
     *     title="Bath_id",
     *     description="ID БК",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $bath_id;

    /**
     * @OA\Property(
     *     title="Specialist_id",
     *     description="ID специалиста",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $specialist_id;

    /**
     * @OA\Property(
     *     title="Bath_serices_id",
     *     description="Форматы посещения БК",
     *     example="'[1,2,3,4]'"
     * )
     *
     * @var integer
     */
    private $bath_formats_ids;

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
     *      title="Comment",
     *      description="Комментарий",
     *      example="Комментарий"
     * )
     *
     * @var string
     */
    private $comment;


    /**
     * @OA\Property(
     *     title="Duration",
     *     description="Продолжительность",
     *     format="int64",
     *     example=90
     * )
     *
     * @var integer
     */
    private $duration;

    /**
     * @OA\Property(
     *      title="Date_from",
     *      description="Дата начала",
     *      example="17.02.2002 16:30"
     * )
     *
     * @var string
     */
    private $date_from;

    /**
     * @OA\Property(
     *      title="Date_to",
     *      description="Дата конца",
     *      example="17.02.2002 18:30"
     * )
     *
     * @var string
     */
    private $date_to;


    /**
     * @OA\Property(
     *     title="Status",
     *     description="Статус. 1 - В ожидании. 2 - в обработке. 3 - Оплата наличными. 4 - частичная оплата. 5 -предоплата получена. 6 -неучадная оплата. 7 - подтверждено. 8 - выполнено.  9 - не выполнено. 10 - отмена.",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $status;


    /**
     * @OA\Property(
     *     title="Price",
     *     description="Цена",
     *     format="int64",
     *     example=1500
     * )
     *
     * @var integer
     */
    private $price;

    /**
     * @OA\Property(
     *     title="Pay_type",
     *     description="Тип оплаты",
     *     format="int64",
     *     example=1500
     * )
     *
     * @var integer
     */
    private $pay_type;
    
    public function bath()
    {
        return $this->hasOne(Bath::class, 'id', 'bath_id');
    }

    public function specialist()
    {
        return $this->hasOne(Specialist::class, 'id', 'specialist_id');
    }


    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function service()
    {
        return $this->hasOne(BathServices::class, 'id', 'bath_formats_ids');
    }
    use HasFactory;
}
