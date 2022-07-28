<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\BathServices;
use App\Models\Bath;
use Illuminate\Http\Request;

class BathServicesController extends Controller
{
    /**
     * @OA\Get(
     *      path="/service/{bath_id}",
     *      tags={"Service"},
     *      summary="Услуги банных комлексов",
     *      description="Возращается объект услуг банных комлексов",
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
    public function service(Request $request) {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id '.$request->bath_id.' not found'
            ]);
        }
        return BathServices::where('bath_id', $request->bath_id)->with('specialist')->orderBy('created_at', 'asc')->get();
    }

    /**
     * @OA\Put(
     *      path="/service/{bath_id}",
     *      tags={"Service"},
     *      summary="Создать новую услугу для банных комлексов",
     *      description="Создать новую услугу для банных комлексов,возращается объект услуг банных комлексов",
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="id БК",
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
     *          @OA\JsonContent(ref="#/components/schemas/BathServices")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BathServices")
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
    public function create(ServiceRequest $request)
    {
        if(Bath::where('id', $request->bath_id)->first() == null) {
            return response([
                'status' => 'error',
                'message' => 'Bath with id '.$request->bath_id.' not found'
            ]);
        }
        $format = BathServices::create($request->validated());
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/service/{id}",
     *      tags={"Service"},
     *      summary="Обновить услугу",
     *      description="Обновить услугу, возращается объект услуг банных комлексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id услуги",
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
     *          @OA\JsonContent(ref="#/components/schemas/BathServices")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BathServices")
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
        $format = BathServices::where('id', $request->id)->first();
        if($format != null) {
            $format->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return BathServices::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/service/{id}",
     *      tags={"Service"},
     *      summary="Удалить услугу",
     *      description="Удалить услугу",
     *      @OA\Parameter(
     *          name="id",
     *          description="id услуги",
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
            'status' => BathServices::where('id', $request->id)->delete()
        ]);
    }
}
