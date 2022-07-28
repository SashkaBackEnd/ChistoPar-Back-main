<?php

namespace App\Http\Controllers;

use App\Http\Requests\TechSpecificationRequest;
use Illuminate\Http\Request;
use App\Models\TechSpecification;
use App\Models\TechSpecificationBath;

class TechSpecificationController extends Controller
{
    /**
     * @OA\Get(
     *      path="/techspecification/",
     *      tags={"TechSpecification"},
     *      summary="Характеристики банных комлексов",
     *      description="Возвращается объект характеристик банных комлексов",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function specification(Request $request) {
        return TechSpecification::all();
    }

    /**
     * @OA\Get(
     *      path="/techspecification/{bath_id}",
     *      tags={"TechSpecification"},
     *      summary="Тех .характеристики банного копмлекса",
     *      description="Возвращается объект тех. характеристик банного комлекса",
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="id БК",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function single(Request $request) {
        $spec_bath = TechSpecificationBath::where('bath_id', $request->bath_id)->get();
        $data = [];
        foreach($spec_bath as $value) {
            $data[] = $value->tech_specification_id;
        }
        $spec = TechSpecification::whereIn('id', $data)->get();
        $result = [];
        foreach($spec_bath as $spec_b) {
            foreach($spec as $s) {
                if($spec_b->tech_specification_id == $s->id) {
                    $result[$s->name][] = $spec_b->descrition;
                }
            }
        }
        return response($result);
    }

    /**
     * @OA\Put(
     *      path="/techspecification/",
     *      tags={"TechSpecification"},
     *      summary="Создать новую характеристику банных комлексов",
     *      description="Создать новый характеристику для БК, возвращается объект характеристики банных комлексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
     *       ),
     *      @OA\Parameter(
     *          name="api_token",
     *          description="Токен пользователя",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function create(TechSpecificationRequest $request)
    {
        $format = TechSpecification::create($request->validated());
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/techspecification/{id}",
     *      tags={"TechSpecification"},
     *      summary="Обновить тех. характеристику банных комлексов",
     *      description="Обновить тех. характеристику, возращается объект тех. характеристики банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id тех. характеристики",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="api_token",
     *          description="Токен пользователя",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Specification")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */
    public function edit(Request $request)
    {
        $format = TechSpecification::where('id', $request->id)->first();
        if($format != null) {
            $format->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return TechSpecification::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/techspecification/{id}",
     *      tags={"TechSpecification"},
     *      summary="Удалить тех. характеристику банных комлексов",
     *      description="Удалить тех. характеристику банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id характеристики",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="api_token",
     *          description="Токен пользователя",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function delete(Request $request)
    {
        return response([
            'status' => TechSpecification::where('id', $request->id)->delete()
        ]);
    }
}
