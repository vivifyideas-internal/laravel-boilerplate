<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group whichh
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([ 'namespace' => 'Api' ], function () {
    Route::group([ 'prefix' => 'auth' ], function ($router) {
        Route::post('register', 'AuthController@register');
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::get('me', 'AuthController@me');
    });

    Route::group([
        'prefix' => 'user',
        'namespace' => 'User'
    ], function () {
        Route::post('forgot-password', 'ForgotPasswordController@forgotPassword');
        Route::post('reset-password', 'ForgotPasswordController@resetPassword');
    });

    Route::group([ 'middleware' => 'auth:api' ], function () {
        Route::get('user/search', 'User\UserController@search');
    });

    Route::group([ 'middleware' => [ 'auth:api', 'email-verified' ] ], function () {
        Route::group([
            'prefix' => 'user',
            'namespace' => 'User'
        ], function () {
            Route::post('change-password', 'UserController@changePassword');
            Route::post('/', 'UserController@updateProfile');
            Route::post('/verify/resend', 'UserController@sendVerifyEmail');
        });

        Route::group([
            'prefix' => 'chats',
            'namespace' => 'Chat'
            // 'middleware' => 'can:accessChat,conversation'
        ], function () {
            Route::get('/', 'ChatController@index');
            Route::post('/start', 'ChatController@store');
            Route::get('/{conversation}', 'ChatMessageController@index')
                ->middleware('can:partOfConversation,conversation');
            Route::post('/{conversation}/messages', 'ChatMessageController@store')
                ->middleware('can:partOfConversation,conversation');
        });
    });
});
