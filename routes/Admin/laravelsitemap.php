<?php

use Rvsitebuilder\Laravelsitemap\Http\Controllers\Admin\LaravelSitemapController;
use Rvsitebuilder\Laravelsitemap\Http\Controllers\Admin\SettingController;

Route::group([
  'prefix' => 'admin',
  'as' => 'admin.',
  'middleware' => 'web',
], function () {
  Route::group([
    'prefix' => 'laravelsitemap',
    'as' => 'laravelsitemap.',
    'middleware' => 'admin',
  ], function () {
    Route::group([
      'prefix' => 'laravelsitemap',
      'as' => 'laravelsitemap.',
    ], function () {
      // 'admin.laravelsitemap.laravelsitemap.'
      Route::get('/', [LaravelSitemapController::class, 'index'])->name('index');
      Route::get('/generate', [LaravelSitemapController::class, 'generate'])->name('generate');
      Route::get('/download', [LaravelSitemapController::class, 'download'])->name('download');
    });

    Route::group([
      'prefix' => 'setting',
      'as' => 'setting.',
    ], function () {
      // 'admin.laravelsitemap.setting.'

      Route::get('/', [SettingController::class, 'index'])->name('index');
      Route::post('/savesetting', [SettingController::class, 'savesetting'])->name('savesetting');
    });
  });
});
