<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     title="Specification",
 *     description="Specification model",
 *     @OA\Xml(
 *         name="Specification"
 *     )
 * )
 */


class Specification extends Model
{
    protected $fillable  = ['name'];
     /**
     * @OA\Property(
     *      title="Name",
     *      description="Название характеристики",
     *      example="Тестовая характеристика"
     * )
     *
     * @var string
     */
    private $name;
    use HasFactory;
}
