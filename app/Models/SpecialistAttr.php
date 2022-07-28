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

class SpecialistAttr extends Model
{
    protected $fillable = ['name', 'filter', 'image'];
    private $id;

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
     *      title="Image",
     *      description="Иконка",
     *      example="Тестовый банный комлекс"
     * )
     *
     * @var string
     */
    private $image;

    /**
     * @OA\Property(
     *      title="Filter",
     *      description="Участвует ли в фильтрации",
     *      example=1
     * )
     *
     * @var boolean
     */
    private $filter;
    use HasFactory;
}
