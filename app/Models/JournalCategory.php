<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="JournalCategory",
 *     description="JournalCategory model",
 *     @OA\Xml(
 *         name="JournalCategory"
 *     )
 * )
 */
class JournalCategory extends Model
{
    protected $fillable = ['name', 'type'];
    private $id;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Название категории журнала",
     *      example="Журнал"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Type",
     *      description="Тип записи (1 - Вести, 2 - Статьи, 3 - обзоры, 4 - Мероприятия)",
     *      format="int64",
     *      example=1
     * )
     *
     * @var string
     */
    private $type;
    use HasFactory;
}
