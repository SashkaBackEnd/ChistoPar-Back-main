<?php

namespace App\Http\Controllers;

use App\Http\Requests\JournalRequest;
use App\Models\Journal;
use App\Models\Review;
use Illuminate\Http\Request;

class JournalController extends Controller
{

    /**
     * @OA\Get(
     *      path="/journal/addview/{id}",
     *      tags={"Journal"},
     *      summary="Инкрементор просмотров журнала",
     *      description="Инкрементор просмотров журнала",
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
    public function addView(Request $request)
    {
        $journal = Journal::where('id', $request->id)->first();
        if($journal == null) {
            return response([
                'status' => false
            ]);
        }
        $views = $journal->views + 1;
        return response([
            'status' => Journal::where('id', $request->id)->update([
                'views' => $views
            ])
        ]);
    }

    
    /**
     * @OA\Get(
     *      path="/journal/",
     *      tags={"Journal"},
     *      summary="Каталог журнала",
     *      description="Возвращается объект каталога журнала",
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
     *          name="view_sort",
     *          description="Сортировка по просмотрам. Передавать asc/desc",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="title",
     *          description="Сортировка имени (ищет совпадения)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="hashtags",
     *          description="Сортировка по хештегам",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="title_sort",
     *          description="Сортировка заголовка по алфавиту. Передавать asc/desc",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="journal_category_id",
     *          description="journal_category_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="bath_id",
     *          description="bath_id",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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
     *          name="type",
     *          description="type",
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
    public function journal(Request $request) {
        $journal = Journal::withCount('fav');
        
        if($request->has('journal_category_id')) {
            $journal->where('journal_category_id', $request->journal_category_id);
        }
        if($request->has('bath_id')) {
            $journal->where('bath_id', $request->bath_id);
        }
        if($request->has('city_id')) {
            $journal->where('city_id', $request->city_id);
        }
        if($request->has('type')) {
            $journal->where('type', $request->type);
        }
        if($request->has('title')) {
            $journal->where('title', 'ilike', "%{$request->title}%");
        }
        if($request->has('hashtags')) {
            $journal->where('hashtags', 'ilike', "%{$request->hashtags}%");
        }
        if($request->has('title_sort')) {
            if($request->title_sort == 'asc') {
                $journal->orderBy('title', 'ASC');
            } 
            if($request->title_sort == 'desc') {
                $journal->orderBy('title', 'desc');
            }
        }
        if($request->has('view_sort')) {
            if($request->view_sort == 'asc') {
                $journal->orderBy('views', 'ASC');
            } 
            if($request->view_sort == 'desc') {
                $journal->orderBy('views', 'desc');
            }
        }
        return $journal->orderBy('created_at', 'desc')->paginate(15);
    }


    /**
     * @OA\Get(
     *      path="/journal/{id}",
     *      tags={"Journal"},
     *      summary="Карточка записи в журнале",
     *      description="Возвращается объект карточки записи в журнале",
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
    public function single(Request $request) {
        $journal = Journal::withCount('fav')->where('id', $request->id)->first();
        if($journal == null) {
            return response([]);
        }
        return response($journal);
    }

    /**
     * @OA\Put(
     *      path="/journal/",
     *      tags={"Journal"},
     *      summary="Создать новую запись в журнале",
     *      description="Создать новую запись в журнале, возвращается объект журнала",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Journal")
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
     *          @OA\JsonContent(ref="#/components/schemas/Journal")
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
    public function create(JournalRequest $request) {
        $journal = Journal::create($request->validated());
        return $journal;
    }


    /**
     * @OA\Patch(
     *      path="/journal/{id}",
     *      tags={"Journal"},
     *      summary="Обновить запись в журнале",
     *      description="Обновить запись в журнале, возвращается объект записи в журнале",
     *      @OA\Parameter(
     *          name="id",
     *          description="id записи в журнале",
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
     *          @OA\JsonContent(ref="#/components/schemas/Journal")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Journal")
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
    public function update(JournalRequest $request)
    {   
        Journal::where('id', $request->id)->update($request->except('api_token'));
        return Journal::where('id',$request->id)->first();
    }


    /**
     * @OA\Delete(
     *      path="/journal/{id}",
     *      tags={"Journal"},
     *      summary="Удалить запись в журнале",
     *      description="Удалить запись в журнале",
     *      @OA\Parameter(
     *          name="id",
     *          description="id записи в журнале",
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
        Review::where('type', 3)->where('entity_id',$request->id)->delete();
        return response([
            'status' => Journal::where('id', $request->id)->delete()
        ]);
    }
}
