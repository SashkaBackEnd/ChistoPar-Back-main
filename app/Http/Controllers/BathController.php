<?php

namespace App\Http\Controllers;

use App\Http\Requests\BathRequest;
use Illuminate\Http\Request;
use App\Models\Bath;
use App\Models\BathAttrBath;
use App\Models\BathCategories;
use App\Models\BathServices;
use App\Models\BathValueAttr;
use App\Models\Format;
use App\Models\Review;
use App\Models\Sale;
use App\Models\Specialist;
use App\Models\SpecialistBath;
use App\Models\SpecialistValueAttr;
use App\Models\Specification;
use App\Models\SpecificationBath;
use App\Models\TechSpecification;
use App\Models\TechSpecificationBath;
use Illuminate\Support\Facades\DB;
use Mekras\Speller\Hunspell\Hunspell;
use Mekras\Speller\Source\StringSource;

class BathController extends Controller
{
    /**
     * @OA\Get(
     *      path="/bath-name",
     *      tags={"Baths"},
     *      summary="Предложеные названия банных комлпексов",
     *      description="Возвращается массив названий банных комплексов",
     *      @OA\Parameter(
     *          name="name",
     *          description="Сортировка имени (ищет совпадения)",
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
    public function bathNames(Request $request) {
        $baths = Bath::query();

        if($request->has('name')) {
            $baths->where('name', 'ilike', "%{$request->name}%");
        }
        $baths = $baths->get();

        $data = [];
        foreach($baths as $bath) {
            $data[] = $bath->name;
        }
        $data = array_unique($data);
        return $data;
    }
    /**
     * @OA\Get(
     *      path="/bath",
     *      tags={"Baths"},
     *      summary="Каталог банных комлпексов",
     *      description="Возвращается объект каталога банных комплексов",
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
     *          name="price_start",
     *          description="price_start",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="price_end",
     *          description="price_end",
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
     *      @OA\Parameter(
     *          name="category_id",
     *          description="category_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="attr[]",
     *          description="Атрибуты",
     *          required=false,
     *          in="query",
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
    public function bath(Request $request) {
        $variants = $this->wordSearch($request->name);
        $bath = Bath::query();
        $attr_value_id = null;
        if($request->has('price_start')) {
            $bath->where('price', '>', $request->price_start);
        }
        if($request->has('price_end')) {
            $bath->where('price', '<', $request->price_end);
        }
        if($request->has('name')) {
            $bath->where(function($q) use($request, $variants) {
                $q->where('name', 'ilike', "%{$request->name}%")->orWhere(function ($query) use($variants) {
                    for ($i = 0; $i < count($variants); $i++){
                       $query->orWhere('name', 'ilike',  '%' . $variants[$i] .'%');
                    }    
               });
            });
           $bva = BathValueAttr::where('name','ilike',"%{$request->name}%")->first();
           if($bva != null) {
                $attr_value_id = $bva;
           }
        }
        if($request->has('category_id') && $request->category_id != 'NaN') {
            $bath->where('category_id', $request->category_id);
        }
        if($request->has('name_sort')) {
            if($request->name_sort == 'asc') {
                $bath->orderBy('name', 'ASC');
            } 
            if($request->name_sort == 'desc') {
                $bath->orderBy('name', 'desc');
            }
        }
        if($request->has('price_sort')) {
            if($request->price_sort == 'asc') {
                $bath->orderBy('price', 'ASC');
            } 
            if($request->price_sort == 'desc') {
                $bath->orderBy('price', 'desc');
            }
        }
        if($request->has('rating_sort')) {
            if($request->rating_sort == 'asc') {
                $bath->orderBy('rating', 'ASC');
            } 
            if($request->rating_sort == 'desc') {
                $bath->orderBy('rating', 'desc');
            }
        }
        if($request->has('attr')) {
            // return response($request->attr);
            $attr = $request->attr;
            $bathattrbath = BathAttrBath::whereIn('bath_value_id', $attr)->get();
            $bath_ids = [];
            foreach($bathattrbath as $value) {
                $bath_ids[] = $value['bath_id']; 
            }
            if(empty($bath_ids)) {
                return response([]);
            }
            // return $bath_ids;
            $bath->whereIn('id',$bath_ids);
        }
        $bath = $bath->get();
        $bath_ids = [];
        foreach($bath as $b) {
            if($b->parent_id == null) {
                $bath_ids[] = $b->id;
            } else {
                $bath_ids[] = $b->parent_id;
            }
        }
        $baths = Bath::with('format')->with('service')->with('category')->withCount('review')->with('childrens')->whereIn('id', $bath_ids)->paginate(15)->toArray();
        $baths['attr_value_id'] = $attr_value_id;

        // Добавляем города
        foreach($baths['data'] as $key => $bath) {
            $baths['data'][$key]['city'] = $this->getCity($bath['category_id']); 
        }
        return $baths;
    }
    /**
     * @OA\Get(
     *      path="/bath/{id}",
     *      tags={"Baths"},
     *      summary="Карточка банного комлпекса",
     *      description="Возвращается объект карточки банного комплекса",
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
        $id = $request->id;
        $bath = Bath::where('id', $id)->with('childrens')->first();
        $bath_reviews = Review::where('entity_id', $id)->where('type', 1)->with('childrenReviews')->with('users')->get();
        $bath_services = BathServices::where('bath_id', $id)->orderBy('created_at', 'asc')->get();
        $format = Format::where('bath_id', $id)->orderBy('created_at', 'asc')->get();
       
        // Спецификации 
        $specification = SpecificationBath::where('bath_id', $id)->get();
        $data = [];
        foreach($specification as $value) {
            $data[] = $value->specification_id;
        }
        $specification = Specification::where('id', $data)->get();


        //  Тех. спецификации 
        $tech_specification = TechSpecificationBath::where('bath_id', $id)->get();
        $data = [];
        foreach($tech_specification as $value) {
            $data[] = $value->tech_specification_id;
        }
        $tech_specification = TechSpecification::where('id', $data)->get();

        $sales = Sale::where('bath_id', $id)->orderBy('created_at', 'asc')->get();

        return response([
            'bath' => $bath,
            'reviews' => $bath_reviews,
            'services' => $bath_services,
            'specification' => $specification,
            'tech_specification' => $tech_specification,
            'format' => $format,
            'sales' => $sales,
            'city' => $this->getCity($bath->category_id)
        ]);
    }


    /**
     * @OA\Get(
     *      path="/bath-item/{link}",
     *      tags={"Baths"},
     *      summary="Карточка банного комлпекса",
     *      description="Возвращается объект карточки банного комплекса",
     *      @OA\Parameter(
     *          name="link",
     *          description="link",
     *          required=true,
     *          in="path",
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
    // Карточка банного комлпекса
    public function singleByLink(Request $request) {
        $link = $request->link;
        $bath = Bath::where('link', $link)->with('childrens')->first();
        $bath_reviews = Review::where('entity_id', $bath->id)->where('type', 1)->with('childrenReviews')->with('users')->get();
        $bath_services = BathServices::where('bath_id', $bath->id)->get();
        $format = Format::where('bath_id', $bath->id)->get();
       
        // Спецификации 
        $specification = SpecificationBath::where('bath_id', $bath->id)->get();
        $data = [];
        foreach($specification as $value) {
            $data[] = $value->specification_id;
        }
        $specification = Specification::where('id', $data)->get();


        //  Тех. спецификации 
        $tech_specification = TechSpecificationBath::where('bath_id', $bath->id)->get();
        $data = [];
        foreach($tech_specification as $value) {
            $data[] = $value->tech_specification_id;
        }
        $tech_specification = TechSpecification::where('id', $data)->get();

        return response([
            'bath' => $bath,
            'reviews' => $bath_reviews,
            'services' => $bath_services,
            'specification' => $specification,
            'tech_specification' => $tech_specification,
            'format' => $format
        ]);
    }

    /**
     * @OA\Put(
     *      path="/bath/",
     *      tags={"Baths"},
     *      summary="Создать новый банный комлекс",
     *      description="Создать новый банный комлекс, возвращается объект банного комплекса",
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
    **/
    public function create(BathRequest $request) {
        function translit_sef($value)
        {
            $converter = array(
                'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
                'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
                'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
                'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
                'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
                'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
                'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
            );
        
            $value = mb_strtolower($value);
            $value = strtr($value, $converter);
            $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
            $value = mb_ereg_replace('[-]+', '-', $value);
            $value = trim($value, '-');	
        
            return $value;
        }


        if($request->parent_id != null && $request->parent_id != 0) {
            $parent_bath = Bath::where('id',$request->parent_id)->first();
            if($parent_bath == null) {
                return response([
                    'message' => 'Bath with id '.$request->parent_id.' not found'
                ],400);
            }
            if($request->operation_mode == null && $request->operation_mode == '') {
                return response([
                    'message' => 'column operation_mode is required'
                ],400);
            }
            if($request->media == null && $request->media == '') {
                return response([
                    'message' => 'column media is required'
                ],400);
            }
        }
        $data = $request->validated();
        if(!isset($data['link']) || $data['link'] == '' || $data['link'] == null) {
            $data['link'] = translit_sef($data['name']);
        }

        $bath_alredy_link_count = Bath::where('link', $data['link'])->count();
        if($data['link'] != '' && $bath_alredy_link_count > 0) {
            $new_count = 1;
            while(true) {
                if(Bath::where('link', $data['link'].'-'.$new_count)->count() == 0) {
                    break;
                }
                $new_count++;
            }
            $data['link'] = $data['link'].'-'.$new_count;
        }
        
        $bath = Bath::create($data);
        return $bath;
    }


    
    /**
     * @OA\Patch(
     *      path="/bath/{id}",
     *      tags={"Baths"},
     *      summary="Обновить банный комплекс",
     *      description="Обновить банный комплекс, возвращается объект банного комплекса",
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
        if($request->has('link') && Bath::where('link', $request->link)->where('id','!=', $request->id)->first() != null) {
            return response([
                'message' => 'Bath with link '.$request->link.' already exist'
            ],400);
        }
        Bath::where('id', $request->id)->update($request->except('api_token'));
        return Bath::where('id',$request->id)->first();
    }


    /**
     * @OA\Delete(
     *      path="/bath/{id}",
     *      tags={"Baths"},
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
        SpecialistBath::where('bath_id', $request->id)->delete();
        Format::where('bath_id', $request->id)->delete();
        Review::where('type', 1)->where('entity_id',$request->id)->delete();
        BathAttrBath::where('bath_id',$request->id)->delete();
        TechSpecificationBath::where('bath_id',$request->id)->delete();
        SpecificationBath::where('bath_id',$request->id)->delete();
        
        return response([
            'status' => Bath::where('id', $request->id)->delete()
        ]);
    }



    /**
     * @OA\Get(
     *      path="/popular/bath/",
     *      tags={"Popular"},
     *      summary="Популярные значения всех атрибутов",
     *      description="Возвращается объект значения всех атрибутов",
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
        $BathAttrBath = BathAttrBath::all();
        $bath_attr_arr = [];
        foreach($BathAttrBath as $item) {
            $bath_attr_arr[$item->bath_value_id][] = $item->bath_id;
        }
        $value_attr = [];
        foreach($bath_attr_arr as $key => $item) {
            $bath_attr_arr[$key] = count($item);
            $value_attr[] = $key;
        }

        
        return BathValueAttr::whereIn('id', $value_attr)->limit(5)->get();
    }

    /**
     * @OA\Get(
     *      path="/popular/bath/{attr_id}",
     *      tags={"Popular"},
     *      summary="Популярные значения атрибута БК",
     *      description="Возвращается объект значений атрибута",
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
        $BathAttrBath = BathAttrBath::all();
        $bath_attr_arr = [];
        foreach($BathAttrBath as $item) {
            $bath_attr_arr[$item->bath_value_id][] = $item->bath_id;
        }
        $value_attr = [];
        foreach($bath_attr_arr as $key => $item) {
            $bath_attr_arr[$key] = count($item);
            $value_attr[] = $key;
        }

        
        return BathValueAttr::whereIn('id', $value_attr)->where('bath_attr_id', $request->bath_attr_id)->limit(5)->get();
    }

    private function wordSearch($name) {
        // Поиск с учётом опечаток
        $search = array(
            "й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
            "ф","ы","в","а","п","р","о","л","д","ж","э",
            "я","ч","с","м","и","т","ь","б","ю"
        );

        $stringArr = explode(" ", $name);
        $variants = [];

        foreach($stringArr as $string) {
            $variants[] = $string;

            $len = mb_strlen($string, 'UTF-8');
            $result = [];
            for ($i = 0; $i < $len; $i++) {
                $result[] = mb_substr($string, $i, 1, 'UTF-8');
            }
            
            $count = 0;
            
            foreach($result as $word) {
                $some = $string;
                $key = array_search($word, $search);
                if(isset($search[$key + 1])) {
                    $variants[] = str_replace($word, $search[$key + 1], $some);
                }
                if(isset($search[$key - 1])) {
                    $variants[] = str_replace($word, $search[$key - 1], $some);
                }
                $count++;
            }
            if($len > 4) {
                $variants[] = mb_substr($string, 0, round($len/2));
                $variants[] = mb_substr($string, round($len/2));
            }
        }
        $en = $this->switcher_ru($name);
        $stringArr = explode(" ", $en);
        foreach($stringArr as $string) {
            $variants[] = $string;
        }
        return $variants;
    }

    /**
     * @OA\Get(
     *      path="/search/",
     *      tags={"Search"},
     *      summary="Поиск",
     *      description="Возвращается объект специалистов и банных комлексов",
     *      @OA\Parameter(
     *          name="s",
     *          description="Поиск",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
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
    public function search(Request $request) {
        $variants = $this->wordSearch($request->s);
        $bath = Bath::query();
        $bath_attr_value_id = null;
        $specialist_attr_value_id = null;
        if($request->has('s')) {
            $bath->where('name', 'ilike', "%{$request->s}%")->orWhere(function ($query) use($variants) {
                for ($i = 0; $i < count($variants); $i++){
                   $query->orwhere('name', 'ilike',  '%' . $variants[$i] .'%');
                }      
           });
           $bva = BathValueAttr::where('name','ilike',"%{$request->s}%")->first();
           if($bva != null) {
                $bath_attr_value_id = $bva;
           }
           $sva = SpecialistValueAttr::where('name','ilike',"%{$request->s}%")->first();
           if($sva != null) {
                $specialist_attr_value_id = $sva;
            }

        }

        // Поиск с учётом дочерних
        // $bath = $bath->get();
        // $bath_ids = [];
        // foreach($bath as $b) {
        //     if($b->parent_id == null) {
        //         $bath_ids[] = $b->id;
        //     } else {
        //         $bath_ids[] = $b->parent_id;
        //     }
        // }
        // $baths = Bath::whereIn('id', $bath_ids)->paginate(15);


        $baths = $bath->paginate(15);
        

        $specialist = Specialist::query();
    
        if($request->has('s')) {
            $specialist->where('name', 'ilike', "%{$request->s}%")->orWhere(function ($query) use($variants) {
                for ($i = 0; $i < count($variants); $i++){
                   $query->orwhere('name', 'ilike',  '%' . $variants[$i] .'%');
                }      
           });
        }
        
        $specialist = $specialist->paginate(15);

 
        return response([
            'baths' => $baths,
            'specialist' => $specialist,
            'bath_attr_value_id' => $bath_attr_value_id,
            'specialist_attr_value_id' => $specialist_attr_value_id
        ]);
    }


    private function switcher_ru($value)
    {
        $converter = array(
            'f' => 'а',	',' => 'б',	'd' => 'в',	'u' => 'г',	'l' => 'д',	't' => 'е',	'`' => 'ё',
            ';' => 'ж',	'p' => 'з',	'b' => 'и',	'q' => 'й',	'r' => 'к',	'k' => 'л',	'v' => 'м',
            'y' => 'н',	'j' => 'о',	'g' => 'п',	'h' => 'р',	'c' => 'с',	'n' => 'т',	'e' => 'у',
            'a' => 'ф',	'[' => 'х',	'w' => 'ц',	'x' => 'ч',	'i' => 'ш',	'o' => 'щ',	'm' => 'ь',
            's' => 'ы',	']' => 'ъ',	"'" => "э",	'.' => 'ю',	'z' => 'я',					
     
            'F' => 'А',	'<' => 'Б',	'D' => 'В',	'U' => 'Г',	'L' => 'Д',	'T' => 'Е',	'~' => 'Ё',
            ':' => 'Ж',	'P' => 'З',	'B' => 'И',	'Q' => 'Й',	'R' => 'К',	'K' => 'Л',	'V' => 'М',
            'Y' => 'Н',	'J' => 'О',	'G' => 'П',	'H' => 'Р',	'C' => 'С',	'N' => 'Т',	'E' => 'У',
            'A' => 'Ф',	'{' => 'Х',	'W' => 'Ц',	'X' => 'Ч',	'I' => 'Ш',	'O' => 'Щ',	'M' => 'Ь',
            'S' => 'Ы',	'}' => 'Ъ',	'"' => 'Э',	'>' => 'Ю',	'Z' => 'Я',					
     
            '@' => '"',	'#' => '№',	'$' => ';',	'^' => ':',	'&' => '?',	'/' => '.',	'?' => ',',
        );
     
        $value = strtr($value, $converter);
        return $value;
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
