<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImagesController extends Controller
{
    public function __invoke($id) {

        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return \response()->json(['message' => 'Изображение не найдено'], 404);
        }

        $validated = $validator->validated()['id'];

        return \response()->file("storage/images/$validated.png");
    }
}
