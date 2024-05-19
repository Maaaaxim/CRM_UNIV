<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {

    Route::view('/test', 'super-admin.testone');

    Route::group(['middleware' => 'auth'], function () {

        //пути для пользователей
        Route::get('/userCreation', 'UserController@userCreation')->name('userCreation');
        Route::post('/store', 'UserController@store')->name('store');
        Route::get('/logout', 'UserController@logout')->name('logout');
        Route::get('/showUsers', 'UserController@showUsers')->name('showUsers');
        Route::get('/deleteUser/{id}', 'UserController@deleteUser')->name('deleteUser');
        Route::get('/userPage/{id}', 'UserController@userPage')->name('userPage');
        Route::post('/update', 'UserController@update')->name('update');

        //пути для лидов и моих лидов
        Route::get('/showLeads', 'LeadController@showLeads')->name('showLeads');
        Route::get('/getLeads/{id}', 'LeadController@getLeads')->name('getLeads');
        Route::post('/leadsAssign', 'LeadController@leadsAssign')->name('leadsAssign');
        Route::post('/massleadsAssign', 'LeadController@massleadsAssign')->name('massleadsAssign');
        Route::get('/myLeads', 'LeadController@showLeads')->name('myLeads');
        Route::get('/creationLeadPage', 'LeadController@creationLeadPage')->name('creationLeadPage');
        Route::post('/createLead', 'LeadController@createLead')->name('createLead');
        Route::get('/deleteLead/{id}', 'LeadController@deleteLead')->name('deleteLead');
        Route::delete('/deleteMassLeads', 'LeadController@deleteMassLeads')->name('deleteMassLeads');
        Route::post('/leadsAssignPlus', 'LeadController@leadsAssignPlus')->name('leadsAssignPlus');
        Route::post('/leadsImport', 'LeadController@leadsImport')->name('leadsImport');
        Route::get('/getComments/{leadId}', 'LeadController@getComments')->name('getComments');
        Route::post('/setStatus', 'LeadController@setStatus')->name('setStatus');
        Route::post('/setCombinedStatus', 'LeadController@setCombinedStatus')->name('setCombinedStatus');
        Route::post('/reassignment', 'LeadController@reassignment')->name('reassignment');
        Route::post('/changeCountry', 'LeadController@changeCountry')->name('changeCountry');
        Route::post('/addCommentFetch', 'LeadController@addCommentFetch')->name('addCommentFetch');
        Route::post('/addValueFetch', 'LeadController@addValueFetch')->name('addValueFetch');

        //пути для страницы лида и управления биржевым аккаунтом
        Route::get('/showLeadPage/{id}', 'LeadPageController@showLeadPage')->name('showLeadPage');
        Route::get('/showLeadComments/{id}', 'LeadPageController@showLeadComments')->name('showLeadComments');
        Route::get('/deleteComment/{id}', 'LeadController@deleteComment')->name('deleteComment');
        Route::get('/showLeadPaymanets/{id}', 'LeadPageController@showLeadPaymanets')->name('showLeadPaymanets');
        Route::get('/showLeadHistory/{id}', 'LeadPageController@showLeadHistory')->name('showLeadHistory');
        Route::post('/updateLead', 'LeadPageController@updateLead')->name('updateLead');

        //пути для страницы дистрибуции
        Route::match(['get', 'post'], '/leadsDistributionPage', 'DistributionController@leadsDistributionPage')->name('leadsDistributionPage');
        Route::post('/distributionleadsAssign', 'DistributionController@distributionleadsAssign')->name('distributionleadsAssign');

        //пути для статусов
        Route::get('/showStatuses', 'StatusController@showStatuses')->name('showStatuses');
        Route::post('/createStatus', 'StatusController@createStatus')->name('createStatus');
        Route::get('/deleteStatus/{id}', 'StatusController@deleteStatus')->name('deleteStatus');
        Route::post('/setColor', 'StatusController@setColor')->name('setColor');

        //пути для стран
        Route::get('/showCountries', 'CountryController@showCountries')->name('showCountries');
        Route::post('/createCountry', 'CountryController@createCountry')->name('createCountry');
        Route::get('/deleteCountry/{id}', 'CountryController@deleteCountry')->name('deleteCountry');

        //пути для дэсков
        Route::get('/showDesks', 'DeskController@showDesks')->name('showDesks');
        Route::post('/createDesk', 'DeskController@createDesk')->name('createDesk');
        Route::get('/deleteDesk/{id}', 'DeskController@deleteDesk')->name('deleteDesk');

        //пути для команд
        Route::get('/showTeams', 'TeamController@showTeams')->name('showTeams');
        Route::post('/createTeam', 'TeamController@createTeam')->name('createTeam');
        Route::get('/deleteTeam/{id}', 'TeamController@deleteTeam')->name('deleteTeam'); //пути для команд

        //пути для апи
        Route::get('/showeApiKeys', 'ApiController@showeApiKeys')->name('showeApiKeys');
        Route::post('/createApiKey', 'ApiController@createApiKey')->name('createApiKey');
        Route::get('/deleteApiKey/{id}', 'ApiController@deleteApiKey')->name('deleteApiKey');

        //слушатель для pusher
        Route::get('/listenerone', 'ListenerController@listenerone')->name('listenerone');

        Route::get('/', 'UserController@redirektych')->name('index');

    });
    //пути для пользователей, но не аудентефицированых
    Route::get('/login', 'UserController@login')->name('login');
    Route::post('/signin', 'UserController@signin')->name('signin');

});


