import React, {useState} from 'react';
import TypesView from "../TypesView/TypesView";
import MachineDetail from "./MachineDetail";
import InfoCard from "../TypesView/InfoCard";
import HorizontalMenu from "../TypesView/HorizontalMenu";
import MachinesView from "../TypesView/MachinesView";
import Button from "../Button";
import {request} from "../../api/request";

function MachineConstructor(props) {
    const {token} = props;
    const [selected, setSelected] = useState(null);
    const [position, setPosition] = useState();
    const [machine, setMachine] = useState({});
    const [warn, setWarn] = useState(false);

    function formalize(data) {
        const {motherboard, processor, ram, power, graphic, devices} = data;
        return {
            motherboardId: motherboard.id,
            processorId: processor.id,
            ramMemoryId: ram.id,
            ramMemoryAmount: ram.amount,
            powerSupplyId: power.id,
            graphicCardId: graphic.id,
            graphicCardAmount: graphic.amount,
            storageDevices: devices?.map(item => {
                    return {
                        storageDeviceId: item.id,
                        storageDeviceAmount: item.amount
                    }
                }
            ),
        }
    }

    function selectHandler(item) {
        setSelected(item);
    }

    function dropHandler(type) {
        if (selected.type == type) {
            //machine[type] = selected;
            request('verify-compability', token, 'post', formalize(machine)).then(setMachine({
                ...machine,
                [type]: selected
            }))


            setPosition();
        }
    }

    function moveHandler(e) {
        setPosition({top: e.clientTop, left: e.clientLeft});
    }

    function createHandler() {
        request('machine', token, 'post',)
    }

    return (
        <div className='machine-constructor' onMouseMove={moveHandler}>
            <div className='machine-header'>
                <span>Create new machine</span>
                <Button onClick={createHandler}>Create</Button>
            </div>
            <div className='machine-container'>
                <div className='machine-details'>
                    <MachinesView token={token} onDrag={selectHandler}/>
                </div>
                <div className='machine-board'>
                    <MachineDetail data={machine.motherboard} onMouseUp={dropHandler}/>
                    <MachineDetail data={machine.processor} onMouseUp={dropHandler}/>
                    <MachineDetail counted data={machine.ram} onMouseUp={dropHandler}/>
                    <MachineDetail data={machine.power} onMouseUp={dropHandler}/>
                    <MachineDetail data={machine.graphic} onMouseUp={dropHandler}/>
                    <div className='storage-devices'>
                        machine.devices?.map(item=><MachineDetail inline counted data={item}/>)
                    </div>
                </div>
                {selected && position &&
                    <InfoCard style={{position: 'fixed', top: position?.top, left: position?.left}}
                              img={selected.img}
                              name={selected.name}/>}
            </div>
        </div>
    );
}

export default MachineConstructor;
