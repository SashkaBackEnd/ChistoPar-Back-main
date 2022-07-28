<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="BathServices",
 *     description="BathServices model",
 *     @OA\Xml(
 *         name="BathServices"
 *     )
 * )
 */

class BathServices extends Model
{
    protected $fillable = ['bath_id','name','description','time', 'price','type'];
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
     *      description="Название услуги БК",
     *      example="Тестовая услуга БК"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="Описание услуги БК",
     *      example="Описание услуги БК"
     * )
     *
     * @var string
     */
    private $description;
    

    /**
     * @OA\Property(
     *      title="Time",
     *      description="Время",
     *      format="int64",
     *      example=30
     * )
     *
     * @var string
     */
    private $time;


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
     *      title="Type",
     *      description="Разделы. 1 - Парение. 2 - Массаж. 3 - SPA. 4 - Программы. 5 - Прочее",
     *      format="int64",
     *      example=1
     * )
     *
     * @var string
     */
    private $type;

    public function specialist()
    {
        return $this->hasOne(Specialist::class, 'bath_serices_id', 'id');
    }

    use HasFactory;
}
