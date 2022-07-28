<?php

namespace App\Http\Controllers;

use App\Http\Requests\BathCategoriesRequest;
use App\Models\Bath;
use Illuminate\Http\Request;
use App\Models\BathCategories;
use MoveMoveIo\DaData\Facades\DaDataAddress;
use Petstore30\Category;

class BathCategoriesController extends Controller
{
    protected $treeItem;
    protected $result;
    protected $delete_ids;
    function __construct()
    {
        $this->treeItem = [];
        $this->result = [];
        $this->delete_ids = [];
    }
    /**
     * @OA\Get(
     *      path="/category",
     *      tags={"Category"},
     *      summary="Список категорий",
     *      description="Возвращается список категорий",
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
    
    public function category()
    {
        $categories = BathCategories::whereNull('parent_id')
        ->with('childrenCategories')
        ->with('baths')
        ->get();
        return $categories;
    }
    /**
     * @OA\Get(
     *      path="/cities/",
     *      tags={"Category"},
     *      summary="Список категорий первого уровня",
     *      description="Возвращается список категорий первого уровня",
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
    
    public function cities(Request $request)
    {
        $categories = BathCategories::where('parent_id', null);

        if($request->has('name')) {
            $categories->where('name', 'ilike', "%{$request->name}%");
        }
    

        $categories = $categories->get();

        
        $data = [];
        foreach($categories as $value) {
            $data[$value->id]['name'] = $value->name; 
            $data[$value->id]['media'] = $value->media; 
            $data[$value->id]['bath_count'] = Bath::where('category_id', $value->id)->count();
            $bath = Bath::where('category_id', $value->id)->orderBy('price', 'asc')->first();
            if($bath != null) {
                $data[$value->id]['min_price'] = $bath->price;
            } else {
                $data[$value->id]['min_price'] = 0;
            }
            
        }

        usort($data, function($a, $b) {
            return strcmp($b["bath_count"],$a["bath_count"]);
        });

        if($request->has('name_sort')) {
            if($request->name_sort == 'asc') {
                usort($data, function($a, $b) {
                    return strcmp($a["name"],$b["name"]);
                });
            } 
            if($request->name_sort == 'desc') {
                usort($data, function($a, $b) {
                    return strcmp($b["name"],$a["name"]);
                });
            }
        }
        return response($data);
    }
    /**
     * @OA\Put(
     *      path="/category",
     *      tags={"Category"},
     *      summary="Создать новую категорию",
     *      description="Создать новую категорию, возвращается объект категории",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/BathCategories")
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
     *          @OA\JsonContent(ref="#/components/schemas/BathCategories")
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
    public function create(Request $request)
    {
        if(BathCategories::where('id', $request->parent_id)->first() == null &&  $request->parent_id != 0) {
            return response([
                'status' => 'error',
                'description' => 'BathCategories with id '.$request->parent_id.' not found'
            ]);
        }
        
        $category = BathCategories::create($request->except('api_token'));
        return $category;
    }
    /**
     * @OA\Patch(
     *      path="/category/{id}",
     *      tags={"Category"},
     *      summary="Обновить категорию",
     *      description="Обновить категорию, возвращается объект категории",
     *      @OA\Parameter(
     *          name="id",
     *          description="id Категории",
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
     *          @OA\JsonContent(ref="#/components/schemas/BathCategories")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/BathCategories")
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
        if(BathCategories::where('id', $request->parent_id)->first() == null &&  $request->parent_id != 0) {
            return response([
                'status' => 'error',
                'description' => 'BathCategories with id '.$request->parent_id.' not found'
            ]);
        }
        BathCategories::where('id', $request->id)->update($request->except('api_token'));
        return BathCategories::where('id',$request->id)->first();
    }
    /**
     * @OA\Delete(
     *      path="/category/{id}",
     *      tags={"Category"},
     *      summary="Удалить категорию",
     *      description="Удалить категорию",
     *      @OA\Parameter(
     *          name="id",
     *          description="id категории",
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
            'status' => BathCategories::where('id', $request->id)->delete()
        ]);
    }


     /** @OA\Get(
     *      path="/geo",
     *      tags={"Category"},
     *      summary="Определение местоположения",
     *      description="Возвращается категория",
     *      @OA\Parameter(
     *          name="ip",
     *          description="ipv4",
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

    public function geo(Request $request)
    {
        $dadata = DaDataAddress::iplocate($request->ip, 2);
        $category =  BathCategories::where('name', 'like', '%'.$dadata['location']['data']['city'].'%')->first();
        if(!$category || !$dadata['location']) {
            return response([]);
        } 
        return $category;
    }
    
}
