import React, { useState } from 'react';
import MachineDetail from "./MachineDetail";
import InfoCard from "../TypesView/InfoCard";
import MachinesView from "../TypesView/MachinesView";
import Button from "../Button";
import { request } from "../../api/request";
import { plural } from "../../helpers/helpers";

const toolTipOffset = 20;

function MachineConstructor(props) {
    const { token, types } = props;
    const [selected, setSelected] = useState();
    const [position, setPosition] = useState();
    const [machine, setMachine] = useState({});
    const [devices, setDevices] = useState([]);
    const [warn, setWarn] = useState(false);

    function formalize(data) {
        const { motherboard, processor, ram, power, graphic } = data;
        return {
            motherboardId: motherboard?.id,
            processorId: processor?.id,
            ramMemoryId: ram?.id,
            ramMemoryAmount: ram?.amount,
            powerSupplyId: power?.id,
            graphicCardId: graphic?.id,
            graphicCardAmount: graphic?.amount,
            storageDevices: devices?.map(item => {
                return {
                    storageDeviceId: item?.id, storageDeviceAmount: item?.amount
                }
            }),
        }
    }

    function selectHandler(item) {
        setSelected(item);
        console.log(item)
    }

    function dropHandler(type) {
        console.log(selected, type)

        if (selected.type == plural(type)) {
            request('verify-compatibility', token, 'post', formalize(machine)).then(setMachine({
                ...machine, [type]: selected
            }))


            setSelected();
        }
    }

    function moveHandler(e) {
        //console.log(selected, e)

        if (selected) setPosition({ top: e.clientY + toolTipOffset, left: e.clientX + toolTipOffset });
    }

    function createHandler() {
        request('machines', token, 'post', formalize(machine));
    }

    function cancelHandler() {
        setSelected();
        setPosition();
    }

    function dropDeviceHandler() {

    }

    return (<div className='machine-constructor' onMouseMove={moveHandler} onMouseUp={cancelHandler}>
        <div className='machine-header'>
            <span>Create new machine</span>
            <Button onClick={createHandler}>Create</Button>
        </div>
        <div className='machine-container'>
            <div className='machine-details'>
                <MachinesView types={types} toolTip token={token} onDrag={selectHandler} />
            </div>
            <div className='machine-board'>
                {[['motherboard'], ['graphic-card', true], ['power-supply'], ['processor'], ['ram-memory', true]]
                    .map(([type, counted]) => <MachineDetail key={type}
                        type={type}
                        data={machine[type]}
                        counted={counted}
                        onMouseUp={() => dropHandler(type)}
                        onRemove={() => removeHandler(type)}
                        onChange={(e) => changeHandler(type, e)}
                    />)}
                <div onMouseUp={() => dropDeviceHandler}>
                    <span className='storage-devices__header'>StorageDevices</span>
                    <div className='storage-devices'>
                        {machine['storage-devices']?.map((data) => <MachineDetail type='storage-devices' inline
                            data={data}
                            key={data.id}
                            onRemove={() => setDevices()}
                            onChange={(e) => changeHandler('storage-devices', e)} />)}
                    </div>
                </div>
            </div>
            {selected && position && <InfoCard style={{ position: 'fixed', top: position?.top, left: position?.left }}
                data={selected} />}
        </div>
    </div>);
}

export default MachineConstructor;
