<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="SpecialistValueAttr",
 *     description="SpecialistValueAttr model",
 *     @OA\Xml(
 *         name="SpecialistValueAttr"
 *     )
 * )
 */
class SpecialistValueAttr extends Model
{
    protected $fillable = ['name','specialist_attr_id'];
    /**
     * @OA\Property(
     *      title="Specialist_attr_id",
     *      description="Id атрибута",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $specialist_attr_id;
    private $id;
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
