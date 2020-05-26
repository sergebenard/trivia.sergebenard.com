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

// Mysql import  LOAD DATA INFILE 'D:\\Sites\\JEOPARDY_CSV-cleaned.csv' IGNORE INTO TABLE questions FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS;

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/teams', function() {
    return view('teams');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/newRound/{type}', 'QuestionsController@getRoundQuestions')->name('columns.get');
