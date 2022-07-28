<?php

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/email', function() {
    Mail::send(['text'=>'mail'],['custom_message'=>'Заживем!'], function ($message) {
        $message->to('paranoid202535@gmail.com','Some')->subject('Test');
        $message->from('nfs2025@mail.ru','Some');
    });
});
// Users
Route::get('/user/{user_id}', 'App\Http\Controllers\UserController@index');
Route::get('/userlist/', 'App\Http\Controllers\UserController@list');
Route::post('/registration', 'App\Http\Controllers\Auth\RegisterController@register');
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@auth');
Route::post('/confirm', 'App\Http\Controllers\Auth\LoginController@confirm');
Route::patch('/user/{id}', 'App\Http\Controllers\UserController@update');
Route::post('/email-verify/{id}', 'App\Http\Controllers\UserController@emailCheck');
Route::get('/user/{id}/{code}', 'App\Http\Controllers\UserController@emailVefified')->name('email_vefified');
Route::patch('/password/reset/{user_id}', 'App\Http\Controllers\UserController@passwordReset');

// Specialists
Route::get('/popular/specialist', 'App\Http\Controllers\SpecialistController@popularList');
Route::get('/popular/specialist/{attr_id}', 'App\Http\Controllers\SpecialistController@popular');
Route::get('/specialists', 'App\Http\Controllers\SpecialistController@specialists');
Route::get('/specialists/{id}', 'App\Http\Controllers\SpecialistController@single');

// Baths
Route::get('/bath/', 'App\Http\Controllers\BathController@bath');
Route::get('/bath-item/{link}', 'App\Http\Controllers\BathController@singleByLink');
Route::get('/bath-name/', 'App\Http\Controllers\BathController@bathNames');
Route::get('/bath/{id}', 'App\Http\Controllers\BathController@single');
Route::get('/popular/bath', 'App\Http\Controllers\BathController@popularList');
Route::get('/popular/bath/{attr_id}', 'App\Http\Controllers\BathController@popular');

// Sale
Route::get('/sale/{bath_id}', 'App\Http\Controllers\SaleController@list');
Route::get('/sale/{id}', 'App\Http\Controllers\SaleController@single');

// Orders
Route::get('/orders/', 'App\Http\Controllers\OrderController@list');

// Form
Route::get('/form/{bath_id}', 'App\Http\Controllers\FormController@list');
Route::get('/form/{id}', 'App\Http\Controllers\FormController@single');
Route::get('/formviewed/{id}', 'App\Http\Controllers\FormController@viewed');
Route::put('/form/', 'App\Http\Controllers\FormController@create');
Route::put('/new-bath/', 'App\Http\Controllers\FormController@newBath');
Route::delete('/form/{id}', 'App\Http\Controllers\FormController@delete');

// Bath Categories 
Route::get('/category', 'App\Http\Controllers\BathCategoriesController@category');
Route::get('/cities', 'App\Http\Controllers\BathCategoriesController@cities');
Route::get('/geo','App\Http\Controllers\BathCategoriesController@geo');

// Formats  
Route::get('/format/{bath_id}', 'App\Http\Controllers\FormatController@format');

// Specification  
Route::get('/specification/', 'App\Http\Controllers\SpecificationController@specification');
Route::get('/specification/{bath_id}', 'App\Http\Controllers\SpecificationController@single');

// TechSpecification  
Route::get('/techspecification/', 'App\Http\Controllers\TechSpecificationController@specification');
Route::get('/techspecification/{bath_id}', 'App\Http\Controllers\TechSpecificationController@single');

// SpecificationBath
Route::get('/specificationbath/{bath_id}', 'App\Http\Controllers\SpecificationBathController@specification');

// TechSpecificationBath 
Route::get('/techspecificationbath/{bath_id}', 'App\Http\Controllers\TechSpecificationBathController@specification');

// Services 
Route::get('/service/{bath_id}', 'App\Http\Controllers\BathServicesController@service');

// BathAttr 
Route::get('/bathattr/', 'App\Http\Controllers\BathAttrController@bathattr');
Route::get('/bathattr/{id}', 'App\Http\Controllers\BathAttrController@single');
Route::get('/bathattrcatalog/', 'App\Http\Controllers\BathAttrController@catalog');
Route::get('/bathattrcatalogsingle/{id}', 'App\Http\Controllers\BathAttrController@catalogSingle');


// SpecialistAttr 
Route::get('/specialist/attr/', 'App\Http\Controllers\SpecialistAttrController@specialistattr');
Route::get('/specialist/attr/{id}', 'App\Http\Controllers\SpecialistAttrController@single');
Route::get('/specialistattrcatalog/', 'App\Http\Controllers\SpecialistAttrController@catalog');
Route::get('/specialistattrcatalogsingle/{id}', 'App\Http\Controllers\SpecialistAttrController@catalogSingle');


// SpecialistValueAttr 
Route::get('/specialistattrvalue/', 'App\Http\Controllers\SpecialistValueAttrController@attr');


// SpecialistAttrSpecialist
Route::get('/specialist/attr/specialist/{specialist_id}', 'App\Http\Controllers\SpecialistAttrSpecialistController@specialistattr');

// SpecialistBath
Route::get('/specialistbath/{specialist_id}', 'App\Http\Controllers\SpecialistBathController@specialistbath');

// BathAttrBath
Route::get('/bathattrbath/{bath_id}', 'App\Http\Controllers\BathAttrBathController@bathattr');

// BathValueAttr 
Route::get('/bathattrvalue/', 'App\Http\Controllers\BathValueAttrController@attr');

// Journal
Route::get('/journal/', 'App\Http\Controllers\JournalController@journal');
Route::get('/journal/{id}', 'App\Http\Controllers\JournalController@single');
Route::get('/journal/addview/{id}', 'App\Http\Controllers\JournalController@addView');

// JournalCategory
Route::get('/journalcategory/', 'App\Http\Controllers\JournalCategoryController@category');

// Search
Route::get('/search/', 'App\Http\Controllers\BathController@search');

// Faq
Route::get('/faq/{id}', 'App\Http\Controllers\FaqController@single');

// Review
Route::get('/getreview/{user_id}', 'App\Http\Controllers\ReviewController@getReviewByUser');
Route::get('/review/', 'App\Http\Controllers\ReviewController@list');
Route::get('/getfavcount/{journal_id}', 'App\Http\Controllers\ReviewController@getFavCount');

// Review
Route::get('/moderatecount', 'App\Http\Controllers\ReviewController@moderateCount');


// SpecialistService
Route::get('/specialistservice/{service_id}', 'App\Http\Controllers\SpecialistServicesController@list');

Route::group(['middleware' => 'api_auth'], function () {
    // Review  
    Route::put('/review/bath/', 'App\Http\Controllers\ReviewController@createBath');
    Route::put('/review/specialist/', 'App\Http\Controllers\ReviewController@createSpecialist');
    Route::put('/review/journal/', 'App\Http\Controllers\ReviewController@createJournal');
    Route::patch('/review/{id}', 'App\Http\Controllers\ReviewController@update');
    Route::post('/review/moderate/{id}', 'App\Http\Controllers\ReviewController@moderate');
    Route::delete('/review/{id}', 'App\Http\Controllers\ReviewController@delete');
    
    // Orders
    Route::get('/order/{id}', 'App\Http\Controllers\OrderController@single');
    Route::put('/order/', 'App\Http\Controllers\OrderController@create');


    // Fav  
    Route::get('/fav/bath/{user_id}', 'App\Http\Controllers\FavController@favBath');
    Route::get('/fav/specialist/{user_id}', 'App\Http\Controllers\FavController@favSpecialist');
    Route::get('/fav/journal/{user_id}', 'App\Http\Controllers\FavController@favJournal');
    Route::put('/fav/bath/', 'App\Http\Controllers\FavController@createBath');
    Route::put('/fav/specialist/', 'App\Http\Controllers\FavController@createSpecialist');
    Route::put('/fav/journal/', 'App\Http\Controllers\FavController@createJournal');
    Route::delete('/fav/bath/{bath_id}/{user_id}', 'App\Http\Controllers\FavController@deleteBathFav');
    Route::delete('/fav/specialist/{specialist_id}/{user_id}', 'App\Http\Controllers\FavController@deleteSpecialistFav');
    Route::delete('/fav/journal/{journal_id}/{user_id}', 'App\Http\Controllers\FavController@deleteJournalFav');

    // Image Upload
    Route::post('/media/upload', 'App\Http\Controllers\MediaController@create');
    
    Route::group(['middleware' => 'admin'], function () { 
        // User
        Route::post('/usercreate', 'App\Http\Controllers\Auth\RegisterController@createNew');
        


        // Baths
        Route::put('/bath/', 'App\Http\Controllers\BathController@create');
        Route::patch('/bath/{id}', 'App\Http\Controllers\BathController@update');
        Route::delete('/bath/{id}', 'App\Http\Controllers\BathController@delete');


        // Faq
        Route::put('/faq/', 'App\Http\Controllers\FaqController@create');
        Route::patch('/faq/{id}', 'App\Http\Controllers\FaqController@update');
        Route::delete('/faq/{id}', 'App\Http\Controllers\FaqController@delete');

        // Specialists
        Route::put('/specialists/', 'App\Http\Controllers\SpecialistController@create');
        Route::patch('/specialists/{id}', 'App\Http\Controllers\SpecialistController@update');
        Route::delete('/specialists/{id}', 'App\Http\Controllers\SpecialistController@delete');

        // Bath Categories 
        Route::put('/category', 'App\Http\Controllers\BathCategoriesController@create');
        Route::patch('/category/{id}', 'App\Http\Controllers\BathCategoriesController@edit');
        Route::delete('/category/{id}', 'App\Http\Controllers\BathCategoriesController@delete');

        // Formats  
        Route::put('/format/{bath_id}', 'App\Http\Controllers\FormatController@create');
        Route::patch('/format/{id}', 'App\Http\Controllers\FormatController@edit');
        Route::delete('/format/{id}', 'App\Http\Controllers\FormatController@delete');

        // Sale  
        Route::put('/sale/', 'App\Http\Controllers\SaleController@create');
        Route::patch('/sale/{id}', 'App\Http\Controllers\SaleController@update');
        Route::delete('/sale/{id}', 'App\Http\Controllers\SaleController@delete');


        // Specification  
        Route::put('/specification/', 'App\Http\Controllers\SpecificationController@create');
        Route::patch('/specification/{id}', 'App\Http\Controllers\SpecificationController@edit');
        Route::delete('/specification/{id}', 'App\Http\Controllers\SpecificationController@delete');

        // TechSpecification  
        Route::put('/techspecification/', 'App\Http\Controllers\TechSpecificationController@create');
        Route::patch('/techspecification/{id}', 'App\Http\Controllers\TechSpecificationController@edit');
        Route::delete('/techspecification/{id}', 'App\Http\Controllers\TechSpecificationController@delete');


        // SpecificationBath
        Route::put('/specificationbath/{bath_id}', 'App\Http\Controllers\SpecificationBathController@create');
        Route::patch('/specificationbath/{id}', 'App\Http\Controllers\SpecificationBathController@edit');
        Route::delete('/specificationbath/{id}', 'App\Http\Controllers\SpecificationBathController@delete');

        // TechSpecificationBath 
        Route::put('/techspecificationbath/{bath_id}', 'App\Http\Controllers\TechSpecificationBathController@create');
        Route::patch('/techspecificationbath/{id}', 'App\Http\Controllers\TechSpecificationBathController@edit');
        Route::delete('/techspecificationbath/{id}', 'App\Http\Controllers\TechSpecificationBathController@delete');


        // Services 
        Route::put('/service/{bath_id}', 'App\Http\Controllers\BathServicesController@create');
        Route::patch('/service/{id}', 'App\Http\Controllers\BathServicesController@edit');
        Route::delete('/service/{id}', 'App\Http\Controllers\BathServicesController@delete');


        // BathAttr 
        Route::put('/bathattr/', 'App\Http\Controllers\BathAttrController@create');
        Route::patch('/bathattr/{id}', 'App\Http\Controllers\BathAttrController@edit');
        Route::delete('/bathattr/{id}', 'App\Http\Controllers\BathAttrController@delete');


        // SpecialistAttr 
        Route::put('/specialist/attr/', 'App\Http\Controllers\SpecialistAttrController@create');
        Route::patch('/specialist/attr/{id}', 'App\Http\Controllers\SpecialistAttrController@edit');
        Route::delete('/specialist/attr/{id}', 'App\Http\Controllers\SpecialistAttrController@delete');

        // SpecialistValueAttr 
        Route::put('/specialistattrvalue/', 'App\Http\Controllers\SpecialistValueAttrController@create');
        Route::patch('/specialistattrvalue/{id}', 'App\Http\Controllers\SpecialistValueAttrController@edit');
        Route::delete('/specialistattrvalue/{id}', 'App\Http\Controllers\SpecialistValueAttrController@delete');

        // SpecialistAttrSpecialist
        Route::put('/specialist/attr/specialist', 'App\Http\Controllers\SpecialistAttrSpecialistController@create');
        Route::patch('/specialist/attr/specialist/{id}', 'App\Http\Controllers\SpecialistAttrSpecialistController@edit');
        Route::delete('/specialist/attr/specialist/{id}', 'App\Http\Controllers\SpecialistAttrSpecialistController@delete');


        // SpecialistBath
        Route::put('/specialistbath', 'App\Http\Controllers\SpecialistBathController@create');
        Route::delete('/specialistbath/{id}', 'App\Http\Controllers\SpecialistBathController@delete');


        // BathAttrBath
        Route::put('/bathattrbath/', 'App\Http\Controllers\BathAttrBathController@create');
        Route::patch('/bathattrbath/{id}', 'App\Http\Controllers\BathAttrBathController@edit');
        Route::delete('/bathattrbath/{id}', 'App\Http\Controllers\BathAttrBathController@delete');

        // BathValueAttr 
        Route::put('/bathattrvalue/', 'App\Http\Controllers\BathValueAttrController@create');
        Route::patch('/bathattrvalue/{id}', 'App\Http\Controllers\BathValueAttrController@edit');
        Route::delete('/bathattrvalue/{id}', 'App\Http\Controllers\BathValueAttrController@delete');


        // SpecialistService
        Route::put('/specialistservice/', 'App\Http\Controllers\SpecialistServicesController@create');
        Route::delete('/specialistservice/{id}', 'App\Http\Controllers\SpecialistServicesController@delete');


        // Journal
        Route::put('/journal/', 'App\Http\Controllers\JournalController@create');
        Route::patch('/journal/{id}', 'App\Http\Controllers\JournalController@update');
        Route::delete('/journal/{id}', 'App\Http\Controllers\JournalController@delete');


        // JournalCategory
        Route::put('/journalcategory/', 'App\Http\Controllers\JournalCategoryController@create');
        Route::patch('/journalcategory/{id}', 'App\Http\Controllers\JournalCategoryController@update');
        Route::delete('/journalcategory/{id}', 'App\Http\Controllers\JournalCategoryController@delete');


        // Orders
        Route::patch('/order/{id}', 'App\Http\Controllers\OrderController@update');
        Route::delete('/order/{id}', 'App\Http\Controllers\OrderController@delete');


        // User

        Route::delete('/user/{id}', 'App\Http\Controllers\UserController@delete');
    });
});