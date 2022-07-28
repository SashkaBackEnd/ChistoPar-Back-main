<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API сервиса ЧистоПар",
 *      description="Документация сервиса.",
 *      @OA\Contact(
 *          email="nfs2025@mail.ru"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo API Server"
 * )

 *
 * @OA\Tag(
 *     name="Category",
 *     description="Категории банного комлекса"
 * )
 * 
 * * @OA\Tag(
 *     name="Specialist",
 *     description="Специалисты"
 * )
 * * @OA\Tag(
 *     name="SpecialistAttr",
 *     description="Атрибуты специалистов"
 * )
 * * @OA\Tag(
 *     name="SpecialistAttrSpecialist",
 *     description="Связь значения и названия атрибутов специалистов"
 * )
 * * @OA\Tag(
 *     name="Baths",
 *     description="Банные комплексы"
 * )
 *  * @OA\Tag(
 *     name="Service",
 *     description="Услуги банных комплексов"
 * )
 * * @OA\Tag(
 *     name="Format",
 *     description="Форматы посещения банных комплексов"
 * )
 * @OA\Tag(
 *     name="BathAttr",
 *     description="Атрибуты банных комплексов"
 * )
 * @OA\Tag(
 *     name="BathAttrBath",
 *     description="Связь значения и названия атрибутов банных комплексов"
 * )
 *  * @OA\Tag(
 *     name="Media",
 *     description="Загрузка медиафайлов"
 * )
 *  * @OA\Tag(
 *     name="Specification",
 *     description="Характеристики банных комлексов"
 * )
 *  * @OA\Tag(
 *     name="TechSpecification",
 *     description="Характеристики банных комлексов"
 * )
 * @OA\Tag(
 *     name="TechSpecificationBath",
 *     description="Значение тех. характеристики банных комлексов"
 * )
 * @OA\Tag(
 *     name="SpecificationBath",
 *     description="Значение тех. характеристики банных комлексов"
 * )
 * @OA\Tag(
 *     name="SpecialistServices",
 *     description="Связь специалистов с услугами"
 * )
 * @OA\Tag(
 *     name="Bathattrvalue",
 *     description="Значения атрибутов БК"
 * )
 * @OA\Tag(
 *     name="Specialistattrvalue",
 *     description="Значения атрибутов БК"
 * )
 * @OA\Tag(
 *     name="Journal",
 *     description="Журнал"
 * )
 * 
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
