<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="BathCategories",
 *     description="Category model",
 *     @OA\Xml(
 *         name="BathCategories"
 *     )
 * )
 */
class BathCategories extends Model
{
    protected $fillable  = ['name','description','media', 'parent_id'];


    private $id;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Название категории",
     *      example="Тестовая категория"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="Описание категории",
     *      example="Описание категории"
     * )
     *
     * @var string
     */
    private $description;
    
    /**
     * @OA\Property(
     *      title="Media",
     *      description="Медиафайлы",
     *      example="images/pic.jpg"
     * )
     *
     * @var string
     */
    private $media;

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
    private $parent_id;
    
    use HasFactory;
    
    public function categories()
    {
        return $this->hasMany(BathCategories::class, 'parent_id', 'id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(BathCategories::class,'parent_id', 'id')->with('categories');
    }

    public function baths()
    {
        return $this->hasOne(Bath::class, 'category_id', 'id');
    }
}
