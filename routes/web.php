<?php

use App\Http\Requests\StoreRequest;
use App\Services\ContactService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('contact');
});

Route::post('/contact', function (StoreRequest $request) {
    $data = $request->validated();
    
    $service = app(ContactService::class);
    $service->store($data);
    
    return redirect('/')->with('success', 'Сообщение отправлено!');
});