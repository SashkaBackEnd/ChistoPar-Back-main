<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Form",
 *     description="Form model",
 *     @OA\Xml(
 *         name="Form"
 *     )
 * )
 */
class Form extends Model
{
    protected $fillable = ['bath_id', 'journal_id','name', 'phone', 'message', 'viewed'];
    
    private $id;

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
     *      title="Journal_id",
     *      description="Id мероприятия",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    private $journal_id;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Id объекта",
     *      example="Иванов Иван Иванович"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *      title="Phone",
     *      description="Телефон",
     *      example="+7 (999) 999 99 99"
     * )
     *
     * @var string
     */
    private $phone;
    
    /**
     * @OA\Property(
     *      title="Message",
     *      description="Сообщение",
     *      example="Здравствуйте, у меня есть вопрос!"
     * )
     *
     * @var string
     */
    private $message;

    /**
     * @OA\Property(
     *      title="Viewed",
     *      description="Просмотрено",
     *      example=false
     * )
     *
     * @var boolean
     */
    private $viewed;


    use HasFactory;
}
