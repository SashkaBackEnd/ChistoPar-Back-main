<?php

namespace App\Http\Controllers;

use App\Http\Requests\TechSpecificationBathRequest;
use App\Models\Bath;
use App\Models\TechSpecification;
use App\Models\TechSpecificationBath;
use Illuminate\Http\Request;

class TechSpecificationBathController extends Controller
{
        /**
     * @OA\Get(
     *      path="/techspecificationbath/{bath_id}",
     *      tags={"TechSpecificationBath"},
     *      summary="Значение тех. характеристик банных комлексов",
     *      description="Возвращается объект значение характеристик банных комлексов",
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
    public function specification(Request $request) {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id '.$request->bath_id.' not found'
            ]);
        }
        return TechSpecificationBath::where('bath_id', $request->bath_id)->get();
    }

    /**
     * @OA\Put(
     *      path="/techspecificationbath/{bath_id}",
     *      tags={"TechSpecificationBath"},
     *      summary="Создать новое значение тех. характеристик банных комлексов",
     *      description="Создать новое значение тех. характеристик для БК, возвращается объект значения тех. характеристик банных комлексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TechSpecificationBath")
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
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TechSpecificationBath")
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
    public function create(TechSpecificationBathRequest $request)
    {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id'.$request->bath_id.'not found'
            ]);
        }
        if(TechSpecification::where('id', $request->tech_specification_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'TechSpecification with id '.$request->bath_id.' not found'
            ]);
        }
        $format = TechSpecificationBath::create($request->validated());
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/techspecificationbath/{id}",
     *      tags={"TechSpecificationBath"},
     *      summary="Обновить значение тех. характеристик банных комлексов",
     *      description="Обновить значение тех. характеристик, возращается объект значения тех. характеристик банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id формата",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/TechSpecificationBath")
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
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/TechSpecificationBath")
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
        $format = TechSpecificationBath::where('id', $request->id)->first();
        if($format != null) {
            $format->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return TechSpecificationBath::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/techspecificationbath/{id}",
     *      tags={"TechSpecificationBath"},
     *      summary="Удалить значение тех. характеристик банных комлексов",
     *      description="Удалить значение тех. характеристик банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id значение тех. характеристик",
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
            'status' => TechSpecificationBath::where('id', $request->id)->delete()
        ]);
    }
}
