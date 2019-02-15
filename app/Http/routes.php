<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('email/emailCampaignSend', 'emailController@emailCampaignSend');
Route::resource('email', 'emailController');

Route::get('/auth/register', function () {
    return view('welcome');
});

Route::get('/errors', function () {
    return view('errors');
});

Route::get( '/', 'PagesController@index' );
Route::get( 'about', 'PagesController@about' );
Route::get( 'contact', 'PagesController@contact' );
//Route::get( 'operator', 'PagesController@contact' );

Route::controllers( [
	'auth'     => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
] );


Route::get('/auth/register', function () {
    return view('welcome');
});

Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'registrationController@confirm'
]);

Route::resource('register', 'registrationController');
// Nos indica que las rutas que están dentro de él sólo serán mostradas si antes el usuario se ha autenticado.
/*Route::group(array('before' => 'Auth'), function()
{
    
    Route::get('/', function()
    {
        return View::make('hello');
    });
    Route::get('/2', function()
    {
        return View::make('helloadmin');
    });
    
    Route::get('logout', 'AuthController@logOut');
});*/

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
    return view('welcome');
});



Route::get('/errors', function () {
    return view('errors');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/homeOperator', function () {
    return view('homeOperator');
});
Route::get( '/', 'PagesController@index' );
Route::get( 'about', 'PagesController@about' );
Route::get( 'contact', 'PagesController@contact' );
//Route::get( 'operator', 'PagesController@contact' );

Route::resource('account', 'accountController');
Route::resource('user', 'UserController');
Route::resource('userOperator', 'UserOperatorController');
Route::get('deactivatedOperator', 'UserOperatorController@deactivatedOperator');
Route::get('userOperator/activateOperator/{id}', 'UserOperatorController@activateOperator');
Route::get('user/createOperator/{id}', 'UserController@createOperator');
Route::get('user/editOperator/{id}', 'UserController@editOperator');
//Route::post('storeOperator', 'UserController@storeOperator');
//Route::patch('updateOperator/{id}', 'UserController@updateOperator');

Route::resource('campaign', 'campaignController');
Route::resource('campaignFiltrar', 'campaignFiltrarController');
Route::get('campaign/activar/{id}', 'campaignController@activar');
Route::get('campaign/desactivar/{id}', 'campaignController@desactivar');
Route::resource('campaignSms', 'campaignSmsController');
Route::resource('campaignEmail', 'campaignEmailController');
Route::resource('campaignEncuesta', 'campaignEncuestaController');
Route::get('campaign/type/{id}', 'campaignController@type');
//Route::post('campaignFiltrar', 'campaignController@campaignFiltrar');
//Route::post('campaignFiltrar', ['as' => 'filtrar', 'uses' => 'campaignController@filtrar']);MessageSend

Route::resource('messageSend', 'messageSendController');

Route::resource('publicPerson', 'publicPersonController');
Route::resource('publicPersonGroup', 'publicPersonGroupController');
//Route::resource('publicPersonPublicPersonGroup', 'publicPersonPublicPersonGroupController');
Route::resource('publicPersonGroupCampaign', 'publicPersonGroupCampaignController');
Route::resource('publicPersonGroupShowGroup', 'publicPersonGroupShowGroupController');
Route::resource('publicPersonGroupToCsv', 'publicPersonGroupShowGroupController@publicPersonGroupToCsv');


Route::resource('reporte', 'reporteController');
Route::resource('reporteOperador', 'reporteOperadorController');
Route::resource('reporteDestinatario', 'reporteDestinatarioController');
Route::resource('reporteTiempoOperador', 'reporteTiempoOperadorController');

Route::resource('resetClave', 'resetClaveController');

Route::resource('personGroup', 'personGroupController');
Route::resource('personPersonGroup', 'personPersonGroupController');
Route::resource('personGroupCampaign', 'personGroupCampaignController');
Route::resource('personGroupShowGroup', 'personGroupShowGroupController');

Route::get('autocomplete', 'personGroupShowGroupController@autocomplete');
Route::get('autocomplete2', 'personGroupShowGroupController@searchUser');

Route::get('campaignDate', 'campaignController@campaignDate');
Route::get('campaignDestinatary', 'campaignController@campaignDestinatary');
Route::get('campaignOperator', 'campaignController@campaignOperator');
Route::get('campaignOperatorList', 'campaignController@campaignOperatorList');

//Route::get('publicPersonGroup', 'publicpersonGroupController@index');
Route::resource('question', 'questionController');
Route::put('question/{id}', 'questionController@create');
Route::get('question/createCondition/{id}', 'questionController@createCondition');
Route::get('question/editCondition/{id}', 'questionController@editCondition');
Route::get('question/up/{id}', 'questionController@up');
Route::get('question/down/{id}', 'questionController@down');


Route::resource('callEncuesta', 'callEncuestaController');
//Route::resource('callEncuestaForm/to/{id}', 'callEncuestaController@callEncuestaForm');
//Route::resource('callEncuestaForm/{id}', 'callEncuestaController@callEncuestaForm');

Route::get('callEncuestaForm/to/{id}', 'callEncuestaController@callEncuestaForm');
Route::get('callEncuestaForm/{id}', 'callEncuestaController@callEncuestaForm');

Route::get('callEncuesta/to/{id}', 'callEncuestaController@callEncuesta');


Route::resource('call', 'callController');
Route::resource('call/to/{id}', 'callController@call');

Route::post('/homeOperator', 'callController@loginCall');
//Route::post( 'question/up/{id}', 'questionController@up' );
//Route::put( 'question/up/{id}', 'questionController@up' );
//Route::get('campaign/{type}', ['as' => 'campaign.create', 'uses' => 'campaignController@create']);
/*Route::get( 'file-parse/{id}', 'FileParserController@index' );*/
Route::get( 'file-upload', 'FileUpload\FileUploadController@index' );
Route::get( 'file-upload/create', 'FileUpload\FileUploadController@create' );
Route::post( 'file-upload', 'FileUpload\FileUploadController@store' );
Route::get( 'file-view/{id}', 'FileViewController@show' );
// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');
// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


});
