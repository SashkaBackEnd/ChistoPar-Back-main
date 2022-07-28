<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * @OA\Get(
     *      path="/sale/{bath_id}",
     *      tags={"Sale"},
     *      summary="Акции банного комлекса",
     *      description="Возвращается объект акций банного комлекса",
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="bath_id",
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
    public function list(Request $request) {
        return response(Sale::where('bath_id', $request->bath_id)->orderBy('created_at', 'asc')->get());
    }


    /**
     * @OA\Get(
     *      path="/sale/{id}",
     *      tags={"Sale"},
     *      summary="Карточка акции",
     *      description="Возвращается объект акции",
     *      @OA\Parameter(
     *          name="id",
     *          description="id акции",
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
        $sale = Sale::where('id', $request->id)->first();
        if($sale == null) {
            return response([]);
        }
        return response($sale);
    }

    /**
     * @OA\Put(
     *      path="/sale/",
     *      tags={"Sale"},
     *      summary="Создать акцию",
     *      description="Создать акцию, возвращается объект акции",
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
     *          @OA\JsonContent(ref="#/components/schemas/Sale")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Sale")
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
    **/
    public function create(SaleRequest $request) {
        $sale = Sale::create($request->validated());
        return $sale;
    }


    /**
     * @OA\Patch(
     *      path="/sale/{id}",
     *      tags={"Sale"},
     *      summary="Обновить акцию",
     *      description="Обновить акцию, возвращается объект акции",
     *      @OA\Parameter(
     *          name="id",
     *          description="id акции",
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
     *          @OA\JsonContent(ref="#/components/schemas/Sale")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Sale")
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
    public function update(SaleRequest $request)
    {   
        Sale::where('id', $request->id)->update($request->except('api_token'));
        return Sale::where('id',$request->id)->first();
    }

    /**
     * @OA\Delete(
     *      path="/sale/{id}",
     *      tags={"Sale"},
     *      summary="Удалить акции",
     *      description="Удалить акции",
     *      @OA\Parameter(
     *          name="id",
     *          description="id акции",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="id акции",
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
    public function delete(Request $request)
    {
        return response([
            'status' => Sale::where('id', $request->id)->delete()
        ]);
    }
}
