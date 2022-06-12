import React, {useState} from 'react';
import TypesView from "../TypesView/TypesView";
import MachineDetail from "./MachineDetail";
import InfoCard from "../TypesView/InfoCard";

function MachineConstructor(props) {
    const {data} = props;
    const [selected, setSelected] = useState(null);

    function selectHandler(item) {

    }

    function dropHandler(item) {

    }

    function moveHandler(item) {

    }

    return (
        <div className='machine-constructor'>
            <TypesView data={data} onClick={}/>
            <div className='machine-board'>
                <MachineDetail />
                <MachineDetail />
                <MachineDetail counted />
                <MachineDetail />
                <MachineDetail />
                <div className='storage-devices'>

                </div>
            </div>
            { selected && <InfoCard style={}></InfoCard>}
        </div>
    );
}

export default MachineConstructor;
