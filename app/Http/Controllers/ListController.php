<?php

namespace App\Http\Controllers;

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

    public function select(Request $request, $type)
    {
        try {
            $tableName = $this->tables[$type];
            $collection = collect(DB::table($this->tables[$type])->get(['*']));

            $size = $request->input('pageSize', '10');
            $page = $request->input('page', 1);

            $chunks = $collection->chunk($size);
            $length = $chunks->count();
            if($length < $page || $page < 1) return [];

            return $chunks[$page - 1];
        }
        catch (\Exception $e) {
            return ['error' => 'invalid parameters'];
        }
    }
}
