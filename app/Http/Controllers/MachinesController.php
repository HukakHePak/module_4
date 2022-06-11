<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            return DB::table($name)->where('id', $ids[$name . 'Id'])->first();
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function verify(Request $request, $full = false)
    {
//        $request->validate([
//            // все поля являются числами
//        ]);

        $ids = $request->all();

        $conflicts = collect();

        $motherboard = $this->get('motherboard', $ids);
        $processor = $this->get('processor', $ids);
        $power = $this->get('powerSupply', $ids);
        $ram = $this->get('ramMemory', $ids);
        $card = $this->get('graphicCard', $ids);
        $storage = [];

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
            if($ram->ramMemoryType !== $motherboard->ramMemoryType)
                $conflicts['ramMemoryId'] = 'Тип ОЗУ на материнской плате отличается от типа ОЗУ';

            if($ramAmount > $motherboard->ramMemorySlots)
                $conflicts['ramMemoryAmount'] = 'Тип ОЗУ на материнской плате отличается от типа ОЗУ';
        }

        if($card) {
            if($cardCount > $motherboard->pciSlots)
                $conflicts['graphicCardAmount'] = 'Количество видеокарт больше, чем количество слотов PCI Express на материнской плате';

            if($cardCount > 1 && !$card->supportMultiGpu)
                $conflicts['graphicCardId'] = 'Модель видеокарты не поддерживает SLI / Crossfire';

            if($power && $power->potency < $card->minimumPowerSupply * $cardCount)
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
            if($storage->count() < 1) $conflicts['storageDevices'] = 'Отсутствуют запоминающие устройства';
        }

        return $conflicts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table($this->table)->delete($id);
    }
}
