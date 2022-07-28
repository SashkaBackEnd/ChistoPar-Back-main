<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialistRequest;
use App\Models\BathServices;
use App\Models\Specialist;
use App\Models\Bath;
use App\Models\BathCategories;
use App\Models\Review;
use App\Models\SpecialistAttrSpecialist;
use App\Models\SpecialistBath;
use App\Models\SpecialistServices;
use App\Models\SpecialistValueAttr;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    /**
     * @OA\Get(
     *      path="/specialists",
     *      tags={"Specialist"},
     *      summary="Каталог специалистов",
     *      description="Возвращается объект каталога специалистов",
     *      @OA\Parameter(
     *          name="category_id",
     *          description="Категория",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="Банный комлпекс",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="page",
     *          description="Пагинация",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price_sort",
     *          description="Сортировка по цене. Передавать asc/desc",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="rating_sort",
     *          description="Сортировка по рейтингу. Передавать asc/desc",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          description="Сортировка имени (ищет совпадения)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="name_sort",
     *          description="Сортировка имени по алфавиту. Передавать asc/desc",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *       @OA\Parameter(
     *          name="attr[]",
     *          description="Атрибуты",
     *          required=false,
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
    public function specialists(Request $request)
    {
        $specialists = Specialist::with('category')->withCount('reviews')->with('bath')->with('services')->with('experience')->with('specialization');
        if($request->has('category_id')) {
            $specialists->where('bath_category_id',$request->category_id);
        }
        if($request->has('bath_id')) {
            $data = [];
            $specialist_bath = SpecialistBath::where('bath_id', $request->bath_id)->get();
            foreach($specialist_bath as $value) {
                $data[] = $value->specialist_id;
            }
            $specialists->whereIn('id',$data);
        }
        if($request->has('name')) {
            $specialists->where('name', 'ilike', "%{$request->name}%");
        }
        if($request->has('name_sort')) {
            if($request->name_sort == 'asc') {
                $specialists->orderBy('name', 'ASC');
            } 
            if($request->name_sort == 'desc') {
                $specialists->orderBy('name', 'desc');
            }
        }
        if($request->has('price_sort')) {
            if($request->price_sort == 'asc') {
                $specialists->orderBy('price', 'ASC');
            } 
            if($request->price_sort == 'desc') {
                $specialists->orderBy('price', 'desc');
            }
        }
        if($request->has('rating_sort')) {
            if($request->rating_sort == 'asc') {
                $specialists->orderBy('rating', 'ASC');
            } 
            if($request->rating_sort == 'desc') {
                $specialists->orderBy('rating', 'desc');
            }
        }
        if($request->has('attr')) {
            // return response($request->attr);
            $attr = $request->attr;
            $SpecialistAttrSpecialist = SpecialistAttrSpecialist::whereIn('specialist_value_id', $attr)->get();
            $specialist_ids = [];
            foreach($SpecialistAttrSpecialist as $value) {
                $specialist_ids[] = $value['specialist_id']; 
            }
            if(empty($specialist_ids)) {
                return response([]);
            }
            // return $bath_ids;
            $specialists->whereIn('id',$specialist_ids);
        }
        $specialists_arr = $specialists->paginate(15)->toArray();
        // Добавляем города
        foreach($specialists_arr['data'] as $key => $specialist) {
            $specialists_arr['data'][$key]['city'] = $this->getCity($specialist['bath_category_id']); 
        }
        return $specialists_arr;
    }
    /**
     * @OA\Get(
     *      path="/specialists/{id}",
     *      tags={"Specialist"},
     *      summary="Карточка специалиста",
     *      description="Возвращается объект карточки специалиста",
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
    public function single(Request $request)
    {
        $id = $request->id;
        $specialists = Specialist::where('id', $id)->first();
        if($specialists == null) {
            return response([]);
        }
        $specialists_reviews = Review::where('entity_id', $id)->with('childrenReviews')->with('users')->where('type', 2)->get();

        $services_spec = SpecialistServices::where('specialist_id', $specialists->id)->get();
        $data = [];
        foreach($services_spec as $value) {
            $data[] = $value->service_id;
        }
        $services = BathServices::whereIn('id', $data)->get();

        $data = [];
        $specialist_bath = SpecialistBath::where('specialist_id', $id)->get();
        foreach($specialist_bath as $value) {
            $data[] = $value->bath_id;
        }
     
        $bath = Bath::whereIn('id', $data)->get();
        return response([
            'specialist' => $specialists,
            'specialists_reviews' => $specialists_reviews,
            'services' => $services,
            'bath' => $bath,
            'city' => $this->getCity($specialists->bath_category_id)
        ]);
    }

    /**
     * @OA\Get(
     *      path="/popular/specialist/",
     *      tags={"Popular"},
     *      summary="Популярные специализации",
     *      description="Возвращается объект специализаций",
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
    public function popularList(Request $request)
    {
        $SpecialistAttrSpecialist = SpecialistAttrSpecialist::all();
        $specialist_attr_arr = [];
        foreach($SpecialistAttrSpecialist as $item) {
            $specialist_attr_arr[$item->specialist_value_id][] = $item->specialist_id;
        }
        $value_attr = [];
        foreach($specialist_attr_arr as $key => $item) {
            $specialist_attr_arr[$key] = count($item);
            $value_attr[] = $key;
        }

        
        return SpecialistValueAttr::whereIn('id', $value_attr)->limit(4)->get();
    }

    /**
     * @OA\Get(
     *      path="/popular/specialist/{attr_id}",
     *      tags={"Popular"},
     *      summary="Популярные специализации",
     *      description="Возвращается объект специализаций",
     *      @OA\Parameter(
     *          name="attr_id",
     *          description="id Атрибута",
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
    public function popular(Request $request)
    {
        $SpecialistAttrSpecialist = SpecialistAttrSpecialist::all();
        $specialist_attr_arr = [];
        foreach($SpecialistAttrSpecialist as $item) {
            $specialist_attr_arr[$item->specialist_value_id][] = $item->specialist_id;
        }
        $value_attr = [];
        foreach($specialist_attr_arr as $key => $item) {
            $specialist_attr_arr[$key] = count($item);
            $value_attr[] = $key;
        }

        
        return SpecialistValueAttr::whereIn('id', $value_attr)->where('specialist_attr_id', $request->attr_id)->limit(4)->get();
    }

    /**
     * @OA\Put(
     *      path="/specialists/",
     *      tags={"Specialist"},
     *      summary="Создать нового специалиста",
     *      description="Создать нового специалиста, возвращается объект специалиста",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Specialist")
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
     *          @OA\JsonContent(ref="#/components/schemas/Specialist")
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
    public function create(SpecialistRequest $request)
    {   
        $specialists = Specialist::create($request->validated());
        return $specialists;
    }




    /**
     * @OA\Patch(
     *      path="/specialists/{id}",
     *      tags={"Specialist"},
     *      summary="Обновить специалиста",
     *      description="Обновить специалиста, возвращается объект специалиста",
     *      @OA\Parameter(
     *          name="id",
     *          description="id Специалиста",
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
     *          @OA\JsonContent(ref="#/components/schemas/Specialist")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Specialist")
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
        Specialist::where('id', $request->id)->update($request->except('api_token'));
        return Specialist::where('id',$request->id)->first();
    }

     /**
     * @OA\Delete(
     *      path="/specialists/{id}",
     *      tags={"Specialist"},
     *      summary="Удалить специалиста",
     *      description="Удалить специалиста",
     *      @OA\Parameter(
     *          name="id",
     *          description="id специалиста",
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
        SpecialistBath::where('specialist_id', $request->id)->delete();
        Review::where('type', 2)->where('entity_id',$request->id)->delete();
        SpecialistAttrSpecialist::where('specialist_id', $request->id)->delete();
        return response([
            'status' => Specialist::where('id', $request->id)->delete()
        ]);
    }

    // Ищём категорию первого уровня (в системе это город)
    public function getCity($category_id) {
        $category = BathCategories::where('id', $category_id)->first();
        if($category == null) {
            return null;
        }
        if($category->parent_id == null) {
            return $category;   
        }
        $parent_id = $category->parent_id;
        while(true) {
            $middle_category = BathCategories::where('id', $parent_id)->first();
            if($middle_category->parent_id == null) {
                break;
            }
            $parent_id = $middle_category->parent_id;
        }
        return $middle_category;
    }
}
