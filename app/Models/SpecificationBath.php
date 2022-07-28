<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="SpecificationBath",
 *     description="SpecificationBath model",
 *     @OA\Xml(
 *         name="SpecificationBath"
 *     )
 * )
 */
class SpecificationBath extends Model
{
    protected $fillable  = ['descrition', 'bath_id', 'specification_id'];
    public $timestamps = false;
     /**
     * @OA\Property(
     *      title="Name",
     *      description="Значение характеристики",
     *      example="Тестовая характеристика"
     * )
     *
     * @var string
     */
    private $descrition;

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
     *     title="Specification_id",
     *     description="ID характеристики",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $specification_id;
    use HasFactory;
}
