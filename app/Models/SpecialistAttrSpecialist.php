<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     title="SpecialistAttrSpecialist",
 *     description="SpecialistAttrSpecialist model",
 *     @OA\Xml(
 *         name="SpecialistAttrSpecialist"
 *     )
 * )
 */

class SpecialistAttrSpecialist extends Model
{
    protected $fillable = ['specialist_id', 'specialist_value_id'];
    private $id;

    /**
     * @OA\Property(
     *      title="Specialist_id",
     *      description="Id специалиста",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $specialist_id;

    

    /**
     * @OA\Property(
     *      title="Specialist_value_id",
     *      description="Id Значение атрибута",
     *      example="1"
     * )
     *
     * @var string
     */
    private $specialist_value_id;
    use HasFactory;
}
