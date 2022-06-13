<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MachinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private string $table = 'machines';

    private function get($name, $ids) {
        try {
            return DB::table($name)->find($ids[$name . 'Id']);
        }
        catch (\Exception $e) {
            return false;
        }
    }

    private function verify(Request $request, $full = false)
    {
        $ids = $request->all();

        $conflicts = collect();

        $motherboard = $this->get('motherboard', $ids);
        $processor = $this->get('processor', $ids);
        $power = $this->get('powerSupply', $ids);
        $ram = $this->get('ramMemory', $ids);
        $card = $this->get('graphicCard', $ids);
        $storage = collect();

        $ramAmount = $request->input('ramMemoryAmount');
        $cardCount = $request->input('graphicCardAmount');


        try {
          $storage = collect($ids['storageDevices'])->map(function ($item) {
                $s = $this->get('storageDevice', ['storageDeviceId' => $item['storageDeviceId']]);
                $s->amount = $item['amount'];

                return $s;
            });
        } catch (\Exception $e) {

        }

        if($processor) {
            if($motherboard->socketTypeId !== $processor->socketTypeId)
                $conflicts['motherboardId'] = 'Тип сокета материнской платы отличается от типа сокета процессора';

            if($motherboard->maxTdp < $processor->tdp)
                $conflicts['processorId'] = 'TDP процессора превышает максимальный TDP, поддерживаемый материнской платой';
        }

        if($ram) {
            if($ram->ramMemoryTypeId !== $motherboard->ramMemoryTypeId)
                $conflicts['ramMemoryId'] = 'Тип ОЗУ на материнской плате отличается от типа ОЗУ';

            if($ramAmount > $motherboard->ramMemorySlots)
                $conflicts['ramMemoryAmount'] = 'Количество ОЗУ превышает количество слотов на материнской плате';
        }

        if($card) {
            if($cardCount > $motherboard->pciSlots)
                $conflicts['graphicCardAmount'] = 'Количество видеокарт больше, чем количество слотов PCI Express на материнской плате';

            if($cardCount > 1 && !$card->supportMultiGpu)
                $conflicts['graphicCardId'] = 'Модель видеокарты не поддерживает SLI / Crossfire';

            if($power && ($power->potency < ($card->minimumPowerSupply * $cardCount)))
                $conflicts['powerSupplyId'] = 'Мощность блока питания меньше минимального значения мощности видеокарты';
        }

        if($storage->count()) {
            $count = $storage->reduce(function ($result, $item) {
                $result[$item->storageDeviceInterface === 'sata'] += $item->amount;

                return $result;
            }, [0, 0]);


            if($count[1] > $motherboard->m2Slots) $conflicts['storageDevices'] = 'Количество запоминающих устройств типа SATA больше, чем количество слотов SATA на материнской плате';
            if($count[0] > $motherboard->sataSlots) $conflicts['storageDevices'] = 'Количество запоминающих устройств типа M2 больше, чем количество слотов M2 на материнской плате';

            if($count[0] + $count[1] < 1) $conflicts['storageDevices'] = 'Должно быть, как минимум 1 устройство SATA или 1 устройство M2';
        }

        if(!$motherboard) $conflicts['motherBoardId'] = 'Отсутствует материнская плата';
        if(!$power) $conflicts['powerSupplyId'] = 'Отсутсвует блок питания';

        if(($full || $ram) && $ramAmount < 1)
            $conflicts['ramMemoryAmount'] = 'Недостаточное количество оперативной памяти';

        if(($full || $card) && $cardCount < 1) $conflicts['graphicCardAmount'] = 'Недостаточное количество видеокарт';

        if($full) {
            if(!$card || $cardCount < 1) $conflicts['graphicCardId'] = 'Отсутсвует видеокарта';
            if(!$processor) $conflicts['processorId'] = 'Отсутсвует процессор';
            if(!$ram) $conflicts['ramMemoryId'] = 'Отсутсвует оперативная память';
        }

        return $conflicts;
    }

    public function check(Request $request)
    {
        try
        {
            $conflicts = $this->verify($request);

            return $conflicts->isEmpty() ? response()->json(['message' => 'Действующая машина'])
                : response()->json($conflicts, 400);
        }
        catch (\Throwable $e)
        {
            return response()->json(['message' => 'Неверные параметры'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    private function save($code) {
        $filename = uniqid();

        Storage::put('/public/images/' . $filename . '.png', base64_decode($code));

        return $filename;
    }

    public function store(Request $request, $updId = null)
    {
        $image = $request->input('imageBase64');

        if(!$image) {
            return response()->json(['message' => 'Изображение не предоставлено'], 400);
        }

        try {
            $filename = $this->save($image);

            $conflicts = $this->verify($request, true);

            if($conflicts->count()) {
               return response()->json($conflicts, 400);
            }

            $machine = $request->all();

            unset($machine['imageBase64']);

            $machine['imageUrl'] = $filename;

            if($updId) {
                DB::table('machine')->where('id', $updId)->update($machine);

                return response()->json(DB::table('machine')->find($updId));
            }
            $id = DB::table('machine')->insertGetId($machine);

            return response()->json(DB::table('machine')->find($id));
        } catch (\Throwable $e) {
            return $e;
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->store($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        return DB::table('machine')->delete($id) ?
            response(null, 204) :
            response()->json(['message' => 'Модель машины не найдена'], 404);
    }
}
