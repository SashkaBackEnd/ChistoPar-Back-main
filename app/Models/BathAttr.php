<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="BathAttr",
 *     description="BathAttr model",
 *     @OA\Xml(
 *         name="BathAttr"
 *     )
 * )
 */


class BathAttr extends Model
{
    protected $fillable = ['name', 'filter','image'];
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
     *      title="Filter",
     *      description="Участвует ли в фильтрации",
     *      example=1
     * )
     *
     * @var boolean
     */
    private $filter;

    /**
     * @OA\Property(
     *      title="Image",
     *      description="Иконка",
     *      example="/uploads/pic.png"
     * )
     *
     * @var boolean
     */
    private $image;
    use HasFactory;
}
