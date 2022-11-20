<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

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

Route::group(
    ['namespace' => 'App\Http\Controllers'],
    static function (Router $router) {
        $router->middleware('auth:api')
            ->group(static function (Router $router) {
                $router->group(
                    ['prefix' => 'auth'],
                    static function (Router $router) {
                        $router->post('me', 'AuthController@me')->name('auth.me');
                        $router->post('login', 'AuthController@login')->name('auth.login');
                        $router->post('logout', 'AuthController@logout')->name('auth.logout');
                        $router->post('refresh', 'AuthController@refresh')->name('auth.refresh');
                    }
                );

                $router->group(
                    ['prefix' => 'queue'],
                    static function (Router $router) {
                        $router->get('/', 'Controller@QueueAll')->name('queue.all');
                        $router->put('/', 'Controller@QueueCreate')->name('queue.create');
                        $router->get('/{queue_id}', 'Controller@QueueFind')->name('queue.find');
                        $router->patch('/{queue_id}', 'Controller@QueueUpdate')->name('queue.update');
                        $router->delete('/{queue_id}', 'Controller@QueueDelete')->name('queue.delete');
                    }
                );
            });

        $router->group(
            [
                'middleware' => 'oauth',
                'prefix' => 'queue/{queue_id}/music',
            ],
            static function (Router $router) {
                $router->get('/', 'Controller@QueueMusicAll')->name('queue_music.all');
                $router->put('/', 'Controller@QueueMusicCreate')->name('queue_music.create');
                $router->get('/{music_id}', 'Controller@QueueMusicFind')->name('queue_music.find');
                $router->patch('/{music_id}', 'Controller@QueueMusicUpdate')->name('queue_music.update');
                $router->delete('/{music_id}', 'Controller@QueueMusicDelete')->name('queue_music.delete');
            }
        );

        $router->get('/oauth/login', 'Controller@OAuthLogin')->name('oauth.login');
    }
);
