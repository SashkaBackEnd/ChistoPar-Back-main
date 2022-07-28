<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\Bath;
use App\Models\Fav;
use App\Models\Specialist;
use App\Models\Journal;
use App\Models\User;

class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *      path="/review/",
     *      tags={"Review"},
     *      summary="Список отзывов",
     *      description="Возвращается объект отзывов",
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
     *          name="moderate",
     *          description="0 - ожидает модерации. 1 - опубликовано",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="rating",
     *          description="Оценка",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="type",
     *          description="Тип",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="date_from",
     *          description="С какой даты",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),  
     *      @OA\Parameter(
     *          name="date_to",
     *          description="По какую дату",
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
    public function list(Request $request) {
        $reviews = Review::with('childrenReviews')->with('users');
        if($request->has('rating')) {
            $reviews->where('rating', $request->rating);
        }
        if($request->has('time_from')) {
            $reviews->where('created_at', '>=', $request->time_from);
        }
        if($request->has('time_to')) {
            $reviews->where('created_at', '<=', $request->time_to);
        }
        if($request->has('moderate')) {
            $reviews->where('moderate',  $request->moderate);
        }
        if($request->has('type')) {
            $reviews->where('type',  $request->type);
        }
        return ReviewResource::collection($reviews->orderBy('created_at', 'desc')->paginate(15));
    }
    /**
     * @OA\Get(
     *      path="/getreview/{user_id}",
     *      tags={"Review"},
     *      summary="Отзывы пользователя",
     *      description="Возвращается объект отзывов",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="user_id",
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
    public function getReviewByUser(Request $request) {
        return Review::where('user_id', $request->user_id)->with('childrenReviews')->with('users')->get();
    }
    /**
     * @OA\Get(
     *      path="/moderatecount/",
     *      tags={"Review"},
     *      summary="Кол-во неотмодерированных отзывов",
     *      description="Кол-во неотмодерированных отзывов",
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
    public function moderateCount() {
        // return 'hi';
        return Review::where('moderate', 0)->get()->count();
    }
    /**
     * @OA\Get(
     *      path="/getfavcount/{journal_id}",
     *      tags={"Review"},
     *      summary="Получить количество добавлений в избранное",
     *      description="Возвращается объект отзывов",
     *      @OA\Parameter(
     *          name="journal_id",
     *          description="journal_id",
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
    public function getFavCount(Request $request) {
        return Fav::where('entity_id', $request->journal_id)->where('type', 3)->get()->count();
    }
    /**
     * @OA\Put(
     *      path="/review/bath",
     *      tags={"Review"},
     *      summary="Добавить отзыв БК",
     *      description="Добавить отзыв БК",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Review")
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
     *          @OA\JsonContent(ref="#/components/schemas/Review")
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
    public function createBath(Request $request)
    {
        if($request->has('entity_id') && $request->has('user_id')) {
            if(Bath::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
            if(User::where('id', $request->user_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'User with id '.$request->user_id.' not found'
                ]);
            }
            $review = Review::create([
                'type' => 1,
                'parent_id' => $request->parent_id,
                'entity_id' => $request->entity_id,
                'user_id' => $request->user_id,
                'advantage' => $request->advantage,
                'flaw' => $request->flaw,
                'rating' => $request->rating,
                'images' => $request->images,
                'comment' => $request->comment
            ]);

            $reviews = Review::where('entity_id', $request->entity_id)->where('type', 1)->where('moderate', true)->get();
            $count = 0;
            $rating = 0;
            foreach($reviews as $value){
                $count++;
                $rating += $value->rating;
            }
            $rating = $rating / $count;
            Bath::where('id', $request->entity_id)->update([
                'rating' => $rating
            ]);
            return $review;
        }
        return response([
            'status' => false,
            'error' => 'Missing parameters'
        ]);
    }


    /**
     * @OA\Put(
     *      path="/review/specialist",
     *      tags={"Review"},
     *      summary="Добавить отзыв специалиста",
     *      description="Добавить отзыв специалиста",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Review")
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
     *          @OA\JsonContent(ref="#/components/schemas/Review")
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
    public function createSpecialist(Request $request)
    {
        if($request->has('entity_id') && $request->has('user_id')) {
            if(Specialist::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
            if(User::where('id', $request->user_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'User with id '.$request->user_id.' not found'
                ]);
            }
            $review = Review::create([
                'type' => 2,
                'parent_id' => $request->parent_id,
                'entity_id' => $request->entity_id,
                'user_id' => $request->user_id,
                'advantage' => $request->advantage,
                'flaw' => $request->flaw,
                'rating' => $request->rating,
                'images' => $request->images,
                'comment' => $request->comment
            ]);

            $reviews = Review::where('entity_id', $request->entity_id)->where('type', 2)->where('moderate', true)->get();
            $count = 0;
            $rating = 0;
            foreach($reviews as $value){
                $count++;
                $rating += $value->rating;
            }
            if($count != 0) {
                $rating = $rating / $count;
            }
            Specialist::where('id', $request->entity_id)->update([
                'rating' => $rating
            ]);
            return $review;
        }
        return response([
            'status' => false,
            'error' => 'Missing parameters'
        ]);
    }

    /**
     * @OA\Put(
     *      path="/review/journal",
     *      tags={"Review"},
     *      summary="Добавить отзыв журнала",
     *      description="Добавить отзыв журнала",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/Review")
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
     *          @OA\JsonContent(ref="#/components/schemas/Review")
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
    public function createJournal(Request $request)
    {
        
        if($request->has('entity_id') && $request->has('user_id')) {
            if(Journal::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
            if(User::where('id', $request->user_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'User with id '.$request->user_id.' not found'
                ]);
            }
            return Review::create([
                'type' => 3,
                'parent_id' => $request->parent_id,
                'entity_id' => $request->entity_id,
                'user_id' => $request->user_id,
                'advantage' => $request->advantage,
                'flaw' => $request->flaw,
                'rating' => $request->rating,
                'images' => $request->images,
                'comment' => $request->comment
            ]);
        }
        return response([
            'status' => false,
            'error' => 'Missing parameters'
        ]);
    }

    /**
     * @OA\Post(
     *      path="/review/moderate",
     *      tags={"Review"},
     *      summary="Модерация отзыва",
     *      description="Модерация отзыва",
     *      @OA\Parameter(
     *          name="id",
     *          description="id отзыва",
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
    public function moderate(Request $request)
    {   
        return response([
            'status' => Review::where('id', $request->id)->update(['moderate' => true])
        ]);
    }

    /**
     * @OA\Patch(
     *      path="/review/{id}",
     *      tags={"Review"},
     *      summary="Обновить отзыв",
     *      description="Обновить отзыв, возвращается объект отзыва",
     *      @OA\Parameter(
     *          name="id",
     *          description="id отзыва",
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
     *          @OA\JsonContent(ref="#/components/schemas/Review")
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Review")
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
        $review = Review::where('id', $request->id)->first();
        if($review == null) {
            return response([
                'status' => false,
                'error' => 'Review with id '.$request->id.' not found'
            ]);
        }
        if($review->type == 1) {
            if(Bath::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
        }
        if($review->type == 2) {
            if(Specialist::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
        }
        if($review->type == 3) {
            if(Journal::where('id', $request->entity_id)->first() == null) {
                return response([
                    'status' => false,
                    'error' => 'Bath with id '.$request->entity_id.' not found'
                ]);
            }
        }
        if(User::where('id', $request->user_id)->first() == null) {
            return response([
                'status' => false,
                'error' => 'User with id '.$request->user_id.' not found'
            ]);
        }
        Review::where('id', $request->id)->update($request->except('api_token'));
        $review = Review::where('id', $request->id)->first();
        $reviews = Review::where('entity_id', $review->entity_id)->where('moderate', true)->where('type', $review->type)->get();
        $count = 0;
        $rating = 0;
        foreach($reviews as $value){
            $count++;
            $rating += $value->rating;
        }
        if($count != 0) {
            $rating = $rating / $count;
        }
        if($review->type == 1) {
            Bath::where('id', $review->entity_id)->update([
                'rating' => $rating
            ]);
        }
        if($review->type == 2) {
            Specialist::where('id', $review->entity_id)->update([
                'rating' => $rating
            ]);
        }
        return $review;
    }

    /**
     * @OA\Delete(
     *      path="/review/{id}",
     *      tags={"Review"},
     *      summary="Удалить отзыв",
     *      description="Удалить отзыв",
     *      @OA\Parameter(
     *          name="id",
     *          description="id отзыва",
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
        $review = Review::where('id', $request->id)->first();
        if($review == null) {
            return response([
                'status' => false,
                'error' => 'Review with id '.$request->id.' not found'
            ]);
        }
        $reviews = Review::where('entity_id', $review->entity_id)->where('moderate', true)->where('type', $review->type)->get();
        $count = 0;
        $rating = 0;
        foreach($reviews as $value){
            if($value->id == $review->id) {
                continue;
            }
            $count++;
            $rating += $value->rating;
        }
        if($count != 0) {
            $rating = $rating / $count;
        }

        if($review->type == 1) {
            Bath::where('id', $review->entity_id)->update([
                'rating' => $rating
            ]);
        }
        if($review->type == 2) {
            Specialist::where('id', $review->entity_id)->update([
                'rating' => $rating
            ]);
        }

        return response([
            'status' => Review::where('id', $request->id)->delete()
        ]);
    }
}
