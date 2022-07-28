<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistAttrRequest;
use App\Models\Specialist;
use App\Models\SpecialistAttr;
use App\Models\SpecialistAttrSpecialist;
use App\Models\SpecialistValueAttr;
use Illuminate\Http\Request;

class SpecialistAttrController extends Controller
{
        /**
     * @OA\Get(
     *      path="/specialist/attr",
     *      tags={"SpecialistAttr"},
     *      summary="Каталог артибутов специалистов",
     *      description="Возвращается объект артибутов специалистов",
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
    public function specialistattr(Request $request) {
        return SpecialistAttr::all();
    }


    /**
     * @OA\Get(
     *      path="/specialist/attr/{id}",
     *      tags={"SpecialistAttr"},
     *      summary="Артибуты специалистов",
     *      description="Возвращается объект артибута специалистов",
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
        $bathattr = SpecialistAttr::where('id',$request->id)->first();
        if($bathattr) {
            return SpecialistAttr::where('id',$request->id)->first();
        } else {
            return response([
                'status' => 'error',
                'message' => 'SpecialistAttr with id '.$request->id.'  not found'
            ]);
        }
    }
    /**
     * @OA\Get(
     *      path="/specialistattrcatalog/",
     *      tags={"SpecialistAttr"},
     *      summary="Артибуты специалистов",
     *      description="Возвращается объект артибутов специалистов",
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
        $bath_attr = SpecialistAttr::all();
        $bath_value = SpecialistValueAttr::all();
        $data = [];
        foreach($bath_attr as $ba) {
            foreach($bath_value as $bv) {
                $data[$ba->id]['name'] = $ba->name;
                $data[$ba->id]['image'] = $ba->image;
                if($bv->specialist_attr_id == $ba->id) {
                    $data[$ba->id]['items'][$bv->id] = $bv->name;
                }
            }
        
        }
        return response($data);
    }
    /**
     * @OA\Get(
     *      path="/specialistattrcatalogsingle/{id}",
     *      tags={"SpecialistAttr"},
     *      summary="Артибуты специалистов",
     *      description="Возвращается объект артибутов специалистов",
     *      @OA\Parameter(
     *          name="id",
     *          description="id специалиста",
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
        $bath_attr = SpecialistAttr::all();
        $bath_value = SpecialistValueAttr::all();
        $bath_attr_bath = SpecialistAttrSpecialist::where('specialist_id', $request->id)->get();
        $data = [];
        foreach($bath_attr as $ba) {
            foreach($bath_value as $bv) {
                foreach($bath_attr_bath as $bab) {
                    if($bv->specialist_attr_id == $ba->id && $bab->specialist_value_id == $bv->id) {
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
     *      path="/specialist/attr/",
     *      tags={"SpecialistAttr"},
     *      summary="Создать новый атрибут специалистов",
     *      description="Создать новый атрибут специалистов, возвращается объект артибута специалистов",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttr")
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttr")
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
    public function create(SpecialistAttrRequest $request)
    {
        $format = SpecialistAttr::create($request->validated());
        return $format;
    }



    /**
     * @OA\Patch(
     *      path="/specialist/attr/{id}",
     *      tags={"SpecialistAttr"},
     *      summary="Обновить атрибут специалистов",
     *      description="Обновить атрибут специалистов, возвращается объект артибута специалистов",
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
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttr")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/SpecialistAttr")
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
        $bathattr = SpecialistAttr::where('id', $request->id)->first();
        if($bathattr != null) {
            $bathattr->update($request->except('api_token'));
        } else {
            return response([
                'status' => 'error'
            ]);
        }
        return SpecialistAttr::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/specialist/attr/{id}",
     *      tags={"SpecialistAttr"},
     *      summary="Удалить атрибут специалистов",
     *      description="Удалить атрубут специалистов",
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
        if($request->id == Specialist::EXPERIENCE ||  $request->id == Specialist::SPECIALIZATION) {
            return response([
                'status' => 0
            ],400);
        }
        return response([
            'status' => SpecialistAttr::where('id', $request->id)->delete()
        ]);
    }
}
