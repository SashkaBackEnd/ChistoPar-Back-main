<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="BathAttrBath",
 *     description="BathAttrBath model",
 *     @OA\Xml(
 *         name="BathAttrBath"
 *     )
 * )
 */


class BathAttrBath extends Model
{
    protected $fillable = ['bath_value_id', 'bath_id'];
    
    private $id;

    /**
     * @OA\Property(
     *      title="Parent Id",
     *      description="Id родительской категории",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $bath_id;

    /**
     * @OA\Property(
     *      title="Bath_value_id",
     *      description="Id значения",
     *      example="1"
     * )
     *
     * @var string
     */
    private $bath_value_id;


    use HasFactory;
}
