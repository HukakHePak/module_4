<?php

namespace App\Http\Controllers;

use App\Exceptions\Handler;
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
            $tableName = $this->tables[$type]; // try catch
            $collection = collect(DB::table($this->tables[$type])->get(['*']));

            $size = $request->input('pageSize');
            $page = $request->input('page');
            //$collection->chunk()
//        $query->partition()
            $chunks = $collection->chunk(10);

            return ['response' => [$size, $page]];
            //return DB::table($this->tables[$type]);
        }
        catch (Handler) {
            return ['error' => 'invalid parameters'];
        }
    }
}
