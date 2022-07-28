<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="TechSpecification",
 *     description="TechSpecification model",
 *     @OA\Xml(
 *         name="TechSpecification"
 *     )
 * )
 */
class TechSpecification extends Model
{
    protected $fillable  = ['name'];
     /**
     * @OA\Property(
     *      title="Name",
     *      description="Название тех. характеристики",
     *      example="Тестовая тех. характеристика"
     * )
     *
     * @var string
     */
    private $name;
    use HasFactory;
}
