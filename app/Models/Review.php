<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Review",
 *     description="Review model",
 *     @OA\Xml(
 *         name="Review"
 *     )
 * )
 */

class Review extends Model
{

    protected $fillable = ['type','parent_id','user_id', 'entity_id', 'advantage', 'comment' ,'flaw','rating', 'moderate', 'images'];
    
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
     *      title="Parent_id",
     *      description="Id комментария. Указывать, если это ответ на комментарий. Для комментариев первого уровня - null",
     *      format="int64",
     *      example=null
     * )
     *
     * @var integer
     */
    private $parent_id;

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


    /**
     * @OA\Property(
     *      title="Advantage",
     *      description="Плюсы",
     *      example="Плюс"
     * )
     *
     * @var string
     */
    private $advantage;

    /**
     * @OA\Property(
     *      title="Аlow",
     *      description="Недостаток",
     *      example="Недостаток"
     * )
     *
     * @var string
     */
    private $flaw;


    /**
     * @OA\Property(
     *      title="Rating",
     *      description="Рейтинг",
     *      format="int64",
     *      example=5
     * )
     *
     * @var string
     */
    private $rating;

    /**
     * @OA\Property(
     *      title="Moderate",
     *      description="Прошел модерацию",
     *      format="int64",
     *      example=1
     * )
     *
     * @var string
     */
    private $moderate;

    /**
     * @OA\Property(
     *      title="Images",
     *      description="Фото",
     *      format="int64",
     *      example="/uploads/test.png"
     * )
     *
     * @var string
     */
    private $images;

    /**
     * @OA\Property(
     *      title="Comment",
     *      description="Комментарий",
     *      format="int64",
     *      example="В целом, отличный банный комлпекс"
     * )
     *
     * @var string
     */
    private $comment;


    public function reviews()
    {
        return $this->hasMany(Review::class, 'parent_id', 'id')->with('users');
    }

    public function childrenReviews()
    {
        return $this->hasMany(Review::class,'parent_id', 'id')->with('reviews');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function entities()
    {
      
        if($this->type == 1) {
            return $this->hasOne(Bath::class, 'id', 'entity_id');   
        }
        if($this->type == 2) {
            return $this->hasOne(Specialist::class, 'id', 'entity_id');
        }
        if($this->type == 3) {
            return $this->hasOne(Journal::class, 'id', 'entity_id');
        }
    }
    
    use HasFactory;
}
