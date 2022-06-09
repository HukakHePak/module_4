<?php

namespace App\Http\Controllers;

use \Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListController extends Controller
{
    private array $tables = [
        'motherboards' => 'motherboard',
        'processors' => 'processor',
        'ram-memories' => 'ramMemory',
        'storage-devices' => 'storageDevice',
        'graphic-cards' => 'graphicCard',
        'power-supplies' => 'powerSupply',
        'machines' => 'machine',
        'brands' => 'brand'
    ];

//    private function validate($parameters) {
//        $validator = Validator::make($parameters, [
//            'pageSize' => 'numeric',
//            'page' => 'numeric',
//            'category' => 'required|alpha_dash',
//            'q' => 'alpha',
//        ], []);
//
//        return $validator->validated();
//    }

    private function chunking(Request $request, $table) {
        $collection = collect($table);
        $size = $request->input('pageSize', '10');
        $page = $request->input('page', 1);

        $chunks = $collection->chunk($size);
        $length = $chunks->count();
        if($length < $page || $page < 1) return [];

        return $chunks[$page - 1];
    }

    public function select(Request $request, $category)
    {
        try {
            $table = DB::table($this->tables[$category])->get(['*']);

            return $this->chunking($request, $table);
        }
        catch (\Exception $e) {
            abort(404);
        }
    }

    public function search(Request $request, $category) {
        try {
            $q = $request->input('q'); // validate

            $table = DB::table($this->tables[$category])->where('name', 'like', '%' . $q . '%') ->get(['*']);

            return $this->chunking($request, $table);
        }
        catch (\Exception $e) {
            abort(404);
        }
    }
}
