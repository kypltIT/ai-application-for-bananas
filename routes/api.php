<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\District;
use App\Models\Ward;
use App\Listeners\SePayWebhookListener;

Route::get('/districts', function (Request $request) {
    $districts = District::where('city_id', $request->city_id)->get();
    return response()->json($districts);
});

Route::get('/wards', function (Request $request) {
    $wards = Ward::where('district_id', $request->district_id)->get();
    return response()->json($wards);
});

Route::post('/webhook/sepayout', function (Request $request) {
    $listener = new SePayWebhookListener();
    $listener->handle($request->all());

    return response()->json(['status' => 'success']);
});
