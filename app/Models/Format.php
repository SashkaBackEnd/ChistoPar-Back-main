<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Format",
 *     description="Format model",
 *     @OA\Xml(
 *         name="Format"
 *     )
 * )
 */

class Format extends Model
{

    protected $fillable  = ['bath_id','name','description','image', 'price_online','price_phone', 'price_time', 'badge', 'periods'];


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
     *      title="Name",
     *      description="Название формата",
     *      example="Тестовый формат"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="Описание формата",
     *      example="Описание формата"
     * )
     *
     * @var string
     */
    private $description;
    
    /**
     * @OA\Property(
     *      title="Image",
     *      description="Изображение",
     *      example="images/pic.jpg"
     * )
     *
     * @var string
     */
    private $image;


    /**
     * @OA\Property(
     *      title="Price_online",
     *      description="Оплата в онлайн",
     *      format="int64",
     *      example=1500
     * )
     *
     * @var string
     */
    private $price_online;
    
    /**
     * @OA\Property(
     *      title="Price_phone",
     *      description="Оплата по телефону",
     *      format="int64",
     *      example=1500
     * )
     *
     * @var string
     */
    private $price_phone;

    /**
     * @OA\Property(
     *      title="Price_time",
     *      description="Оплата почасово",
     *      format="int64",
     *      example=1500
     * )
     *
     * @var string
     */
    private $price_time;

    /**
     * @OA\Property(
     *      title="Badge",
     *      description="Бэйдж",
     *      format="int64",
     *      example=1
     * )
     *
     * @var string
     */
    private $badge;


    /**
     * @OA\Property(
     *      title="Periods",
     *      description="Периоды",
     *      format="int64",
     *      example="{'11:00':500,'23:00':1000}"
     * )
     *
     * @var string
     */
    private $periods;

    use HasFactory;
}
