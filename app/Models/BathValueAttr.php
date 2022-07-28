<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="BathValueAttr",
 *     description="BathValueAttr model",
 *     @OA\Xml(
 *         name="BathValueAttr"
 *     )
 * )
 */


class BathValueAttr extends Model
{
    protected $fillable = ['name','bath_attr_id'];
    
    private $id;
    /**
     * @OA\Property(
     *      title="Bath_attr_id",
     *      description="Id атрибута",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $bath_attr_id;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Название атрибута",
     *      format="int64",
     *      example="Атрибут"
     * )
     *
     * @var integer
     */
    private $name;

    use HasFactory;
}
