<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     title="SpecialistServices",
 *     description="SpecialistServices model",
 *     @OA\Xml(
 *         name="SpecialstServices"
 *     )
 * )
 */
class SpecialistServices extends Model
{
    protected $fillable = ['specialist_id', 'service_id'];
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
     *      title="Service_id",
     *      description="Id услуги",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $service_id;
    use HasFactory;
}
