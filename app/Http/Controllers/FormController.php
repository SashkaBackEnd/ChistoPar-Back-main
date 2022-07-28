<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormaRequest;
use App\Models\Form;
use App\Models\BathAttrBath;
use App\Models\Bath;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FormController extends Controller
{
    /**
     * @OA\Get(
     *      path="/form/{bath_id}",
     *      tags={"Form"},
     *      summary="Заявки банного комлекса",
     *      description="Возвращается объект заявок банного комлекса",
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
    public function list(Request $request) {
        $bath = Bath::query();
        
        if($request->has('price_start')) {
            $bath->where('price', '>', $request->price_start);
        }
        if($request->has('price_end')) {
            $bath->where('price', '<', $request->price_end);
        }
        if($request->has('category_id')) {
            $bath->where('category_id', $request->category_id);
        }
        if($request->has('category_id')) {
            $bath->where('category_id', $request->category_id);
        }
        if($request->has('name')) {
            $first = mb_substr($request->name ,1);
            $bath->where('name', 'like', "%{$first}%");
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
        
        $baths = $bath->get();
        
        $data = [];
        
        foreach($baths as $item) {
            $data[] = $item->id;
        }


        return response(Form::whereIn('bath_id', $item)->paginate(15));
    }


    /**
     * @OA\Get(
     *      path="/form/{id}",
     *      tags={"Form"},
     *      summary="Карточка заявки",
     *      description="Возвращается объект заявки",
     *      @OA\Parameter(
     *          name="id",
     *          description="id заявки",
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
        $form = Form::where('id', $request->id)->first();
        if($form == null) {
            return response([]);
        }
        return response($form);
    }


    /**
     * @OA\Get(
     *      path="/formviewed/{id}",
     *      tags={"Form"},
     *      summary="Добавить статус просмотрено",
     *      description="Возвращается объект заявки",
     *      @OA\Parameter(
     *          name="id",
     *          description="id заявки",
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
    public function viewed(Request $request) {
        return response([
            'status' => Form::where('id',$request->id)->update([
                'viewed' => true
            ])
        ]);
    }

    /**
     * @OA\Put(
     *      path="/form/",
     *      tags={"Form"},
     *      summary="Создать заявку",
     *      description="Создать заявку, возвращается объект заявки",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Form")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Form")
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
    public function create(FormaRequest $request) {
        $bath = Bath::where('id', $request->bath_id)->first();
        if(is_null($bath)) {
            return response([
                'error' => 'Bath with id '.$request->bath_id.' not found'
            ],400);
        }
        if($bath->email != '') {
            $custom_message = "Банный комплекс: ".$bath->name."\n";
            $custom_message .= "Имя: ".$request->name."\n";
            $custom_message .= "Телефон: ".$request->phone."\n";
            $custom_message .= "Сообщение: ".$request->message."\n";
            Mail::send(['text'=>'mail'],['custom_message'=>$custom_message], function ($message) use ($bath) {
                $message->to($bath->email,'Чистопар')->subject('Новая заявка');
                $message->from('nfs2025@mail.ru','Чистопар');
            });
        }
 
        $form = Form::create($request->validated());
        return $form;
    }


    /**
     * @OA\Put(
     *      path="/new-bath/",
     *      tags={"Form"},
     *      summary="Форма предложить свой БК",
     *      description="Создать заявку, возвращается объект заявки",
     *      @OA\Parameter(
     *          name="name",
     *          description="ФИО",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="phone",
     *          description="Телефон",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="Почта",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="message",
     *          description="Комментарий",
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
    **/
    public function newBath(FormaRequest $request) {

        $users = User::where('role_id', 1)->get();
        if($request->has('email')) {
            $email = $request->email;
        } else {
            $email = 'не указано';
        }
        $custom_message = "Новая предложение по размещению своего БК: \n";
        $custom_message .= "ФИО: ".$request->name."\n";
        $custom_message .= "Почта: ".$email."\n";
        $custom_message .= "Телефон: ".$request->phone."\n";
        $custom_message .= "Сообщение: ".$request->message."\n";
        foreach($users as $user) {
            Mail::send(['text'=>'mail'],['custom_message'=>$custom_message], function ($message) use ($user) {
                $message->to($user->email,'Чистопар')->subject('Новая заявка');
                $message->from('nfs2025@mail.ru','Чистопар');
            });
        }
 
        return response([
            'status' => 'ok'
        ],200);
    }

    /**
     * @OA\Delete(
     *      path="/form/{id}",
     *      tags={"Form"},
     *      summary="Удалить заявку",
     *      description="Удалить заявку",
     *      @OA\Parameter(
     *          name="id",
     *          description="id заявки",
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
            'status' => Form::where('id', $request->id)->delete()
        ]);
    }
}
