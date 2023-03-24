<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImagesController extends Controller
{
    public function __invoke($id) {
        try{
            return \response()->file("app/storage/public/images/$id.png");
        }
        catch(\Exception $e) {
            return \response()->json(['message' => 'Изображение не найдено'], 404);
        }
    }
}
