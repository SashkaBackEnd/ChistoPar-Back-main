<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="SpecialistBath",
 *     description="SpecialistBath model",
 *     @OA\Xml(
 *         name="SpecialistBath"
 *     )
 * )
 */

class SpecialistBath extends Model
{
    protected $fillable = ['specialist_id', 'bath_id'];
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
     *      title="Bath_id",
     *      description="Id БК",
     *      example="1"
     * )
     *
     * @var string
     */
    private $bath_id;
    
    use HasFactory;
}
