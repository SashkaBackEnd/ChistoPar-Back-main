<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Faq",
 *     description="Faq model",
 *     @OA\Xml(
 *         name="Faq"
 *     )
 * )
 */


class Faq extends Model
{
    protected $fillable = ['bath_id','question', 'answer'];
    
    private $id;

    private $type;

    /**
     * @OA\Property(
     *      title="Bath_id",
     *      description="Id БК",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $bath_id;

    /**
     * @OA\Property(
     *      title="Question",
     *      description="Вопрос",
     *      example="Какая средняя цена форматов посещения?"
     * )
     *
     * @var string
     */
    private $question;


    /**
     * @OA\Property(
     *      title="Answer",
     *      description="Ответ",
     *      example="1500"
     * )
     *
     * @var string
     */
    private $answer;

    use HasFactory;
}
