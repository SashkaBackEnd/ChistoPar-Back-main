<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="SpecialistAttr",
 *     description="SpecialistAttr model",
 *     @OA\Xml(
 *         name="SpecialistAttr"
 *     )
 * )
 */
class Sale extends Model
{
    protected $fillable = ['bath_id','name', 'date_start', 'date_end', 'image'];
    private $id;

    /**
     * @OA\Property(
     *      title="Bath_id",
     *      description="ID БК",
     *      example=1
     * )
     *
     * @var integer
     */
    private $bath_id;
    /**
     * @OA\Property(
     *      title="Name",
     *      description="Акция",
     *      example="Акция"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Date_start",
     *      description="Дата начала акции",
     *      example="10.03.2022"
     * )
     *
     * @var string
     */
    private $date_start;

    /**
     * @OA\Property(
     *      title="Date_end",
     *      description="Акция",
     *      example="10.04.2022"
     * )
     *
     * @var string
     */
    private $date_end;

    /**
     * @OA\Property(
     *      title="Image",
     *      description="Обложка",
     *      example="images/pic.png"
     * )
     *
     * @var string
     */
    private $image;

 
    use HasFactory;
}
