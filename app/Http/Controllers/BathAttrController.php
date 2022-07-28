<?php

namespace App\Http\Controllers;

use App\Http\Requests\BathAttrRequest;
use App\Models\Bath;
use App\Models\BathAttr;
use App\Models\BathAttrBath;
use App\Models\BathValueAttr;
use Illuminate\Http\Request;

class BathAttrController extends Controller
{
    /**
     * @OA\Get(
     *      path="/bathattr",
     *      tags={"BathAttr"},
     *      summary="Каталог артибутов банных комплексов",
     *      description="Возвращается объект артибутов банных комплексов",
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
    public function bathattr(Request $request) {
        return BathAttr::all();
    }


    /**
     * @OA\Get(
     *      path="/bathattr/{id}",
     *      tags={"BathAttr"},
     *      summary="Артибуты банных комплексов",
     *      description="Возвращается объект артибута банных комплексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id атрибута",
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
        $bathattr = BathAttr::where('id',$request->id)->first();
        if($bathattr) {
            return BathAttr::where('id',$request->id)->first();
        } else {
            return response([
                'status' => 'error',
                'message' => 'BathAttr with id '.$request->id.'  not found'
            ]);
        }
    }


    /**
     * @OA\Get(
     *      path="/bathattrcatalog/",
     *      tags={"BathAttr"},
     *      summary="Артибуты банных комплексов",
     *      description="Возвращается объект артибутов банных комплексов",
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

    public function catalog(Request $request) {
        $bath_attr = BathAttr::all();
        $bath_value = BathValueAttr::all();
        $data = [];
        foreach($bath_attr as $ba) {
            foreach($bath_value as $bv) {
                $data[$ba->id]['name'] = $ba->name;
                $data[$ba->id]['image'] = $ba->image;
                if($bv->bath_attr_id == $ba->id) {
                    $data[$ba->id]['items'][$bv->id] = $bv->name;
                }
            }
        
        }
        return response($data);
    }
     /**
     * @OA\Get(
     *      path="/bathattrcatalogsingle/{id}",
     *      tags={"BathAttr"},
     *      summary="Артибуты банных комплексов",
     *      description="Возвращается объект артибутов банных комплексов",
     *      @OA\Parameter(
     *          name="id",
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

    public function catalogSingle(Request $request) {
        $bath_attr = BathAttr::all();
        $bath_value = BathValueAttr::all();
        $bath_attr_bath = BathAttrBath::where('bath_id', $request->id)->get();
        $data = [];
        foreach($bath_attr as $ba) {
            foreach($bath_value as $bv) {
                foreach($bath_attr_bath as $bab) {
                    if($bv->bath_attr_id == $ba->id && $bab->bath_value_id == $bv->id) {
                        $data[$ba->id]['name'] = $ba->name;
                        $data[$ba->id]['image'] = $ba->image;
                        $data[$ba->id]['items'][$bv->id] = $bv->name;
                    }
                }
            }
        }
        return response($data);
    }
    /**
     * @OA\Put(
     *      path="/bathattr/",
     *      tags={"BathAttr"},
     *      summary="Создать новый атрибут банных комплексов",
     *      description="Создать новый атрибут банных комплексов, возвращается объект артибута банных комплексов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/BathAttr")
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
     *          @OA\JsonContent(ref="#/components/schemas/BathAttr")
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
    public function create(BathAttrRequest $request)
    {
        $format = BathAttr::create($request->validated());
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/bathattr/{id}",
     *      tags={"BathAttr"},
     *      summary="Обновить атрибут банных комплексов",
     *      description="Обновить атрибут банных комплексов, возвращается объект артибута банных комплексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id атрибута",
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
     *          @OA\JsonContent(ref="#/components/schemas/BathAttr")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BathAttr")
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
        $bathattr = BathAttr::where('id', $request->id)->first();
        if($bathattr != null) {
            $bathattr->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return BathAttr::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/bathattr/{id}",
     *      tags={"BathAttr"},
     *      summary="Удалить атрибут банных комплексов",
     *      description="Удалить атрубут банных комплексов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id атрубута",
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
        $bath_attr = BathAttr::where('id', $request->id)->first();
        if($bath_attr == null || $bath_attr->id == 13 || $bath_attr->id == 6 || $bath_attr->id == 24) {
            return response([
                'status' => 0
            ]);
        }
        BathValueAttr::where('bath_attr_id',$request->id)->delete();
        return response([
            'status' => BathAttr::where('id', $request->id)->delete()
        ]);
    }
}
