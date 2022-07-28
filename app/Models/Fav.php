<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Fav",
 *     description="Fav model",
 *     @OA\Xml(
 *         name="Fav"
 *     )
 * )
 */


class Fav extends Model
{
    protected $fillable = ['type','user_id', 'entity_id'];
    
    private $id;

    private $type;

    /**
     * @OA\Property(
     *      title="User_id",
     *      description="Id пользователя",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $user_id;

    /**
     * @OA\Property(
     *      title="Entity_id",
     *      description="Id объекта",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $entity_id;

    use HasFactory;
}
