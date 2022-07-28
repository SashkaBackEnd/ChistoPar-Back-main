<?php

namespace App\Http\Controllers;

use App\Http\Requests\FaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    /**
     * @OA\Get(
     *      path="/faq/{id}",
     *      tags={"Faq"},
     *      summary="Карточка FAQ банного комлпекса",
     *      description="Возвращается объект  карточки FAQ банного комплекса",
     *      @OA\Parameter(
     *          name="id",
     *          description="id",
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
    // Карточка банного комлпекса
    public function single(Request $request) {
        return Faq::where('bath_id', $request->id)->get();
    }

    /**
     * @OA\Put(
     *      path="/faq/",
     *      tags={"Faq"},
     *      summary="Создать новый FAQ банный комлекс",
     *      description="Создать новый FAQ банный комлекс, возвращается объект FAQ банного комплекса",
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
     *          @OA\JsonContent(ref="#/components/schemas/Faq")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Faq")
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
    public function create(FaqRequest $request) {
        $bath = Faq::create($request->validated());
        return $bath;
    }


    /**
     * @OA\Patch(
     *      path="/faq/{id}",
     *      tags={"Faq"},
     *      summary="Обновить FAQ банный комплекс",
     *      description="Обновить FAQ банный комплекс, возвращается объект банного комплекса",
     *      @OA\Parameter(
     *          name="id",
     *          description="id банного комплекса",
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
     *          @OA\JsonContent(ref="#/components/schemas/Bath")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Bath")
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
    public function update(Request $request)
    {   
        Faq::where('id', $request->id)->update($request->except('api_token'));
        return Faq::where('id',$request->id)->first();
    }


    /**
     * @OA\Delete(
     *      path="/faq/{id}",
     *      tags={"Faq"},
     *      summary="Удалить банный комлпекс",
     *      description="Удалить банный комлпекс",
     *      @OA\Parameter(
     *          name="id",
     *          description="id банного комлпекса",
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
            'status' => Faq::where('id', $request->id)->delete()
        ]);
    }
}
