<?php

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

Route::get('/', function () {
    //return view('welcome');
    return view('/layout.front');
});

// Route untuk user yang baru register
Route::group(['prefix' => 'home', 'middleware' => ['auth']], function(){
    Route::get('/', function(){
        $data['role'] = \App\UserRole::whereUserId(Auth::id())->get();
        return view('font', $data);
    });
    Route::post('upgrade', function(Request $request){
        if($request->ajax()){
            $msg['success'] = 'false';
            $user = \App\User::find($request->id);
            if($user)
                $user->putRole($request->level);
                $msg['success'] = 'true';
            return response()
                ->json($msg);
        }
    });
});
// Route untuk user yang admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth','role:admin']], function(){
    Route::get('/', function(){
        $data['users'] = \App\User::whereDoesntHave('roles')->get();
        return view('admin', $data);
    });
});
// Route untuk user yang member
Route::group(['prefix' => 'karyawan', 'middleware' => ['auth','role:karyawan']], function(){
    Route::get('/', function(){
        return view('member');
    });
});
Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');





/*
//ini untuk routing
Route::get('/karyawan','KaryawanController@index');
//ini untuk create
Route::post('/karyawan/create','KaryawanController@create');
//ini untuk edit
Route::get('/karyawan/{id}/edit','KaryawanController@edit');
Route::post('/karyawan/{id}/update','KaryawanController@update');
Route::get('/karyawan/{id}/delete', 'KaryawanController@delete');
*/
Route::get('/login','AuthController@login')->name('login');
Route::post('/postlogin','AuthController@postlogin');
Route::get('/logout','AuthController@logout');
Route::group(['middleware' => 'auth'], function(){
Route::get('/karyawan/export/','KaryawanController@export');
Route::get('/karyawan/exportPdf/','KaryawanController@exportPdf');

	
Route::get('/home', function () {
    //return view('welcome');
    return view('/layouts/home');
	});
Route::get('/about', function () {
    //return view('welcome');
    return view('/layouts/about');
});
Route::get('/perusahaan', function () {
    //return view('welcome');
    return view('/layouts/perusahaan');
});

Route::resource('karyawan','KaryawanController');
Route::resource('posisi','PosisiController');
Route::resource('absensi','AbsensiController');

});