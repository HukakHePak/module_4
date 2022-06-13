import React, {useEffect, useState} from 'react';
import InfoCard from "./InfoCard";
import HorizontalMenu from "./HorizontalMenu";
import Button from "../Button";
import {request} from "../../api/request";
import {types} from "../../api/data";

function TypesView(props) {
    const {onCreate, token, full, toolTip} = props;
    const [list, setList] = useState([]);
    const [selected, setSelected] = useState(types[0]);
    const [tip, setTip] = useState();

    useEffect(() => {
        request(selected, token, 'get').then(setList);
    }, [selected]);

    return <div className={'machines-view' + (full && ' full')}>
        <HorizontalMenu selected={selected} data={types} onSelect={setSelected} asList={!full}/>
        {!full && <Button onClick={onCreate}>Создать новую машину</Button>}
        <div className='machines-list'>
            <div className='machines-grid'>
                {list.map((item) => <InfoCard key={item.id} data={item} onMouseMove={setTip}
                                              onMouseOver={() => setTip()}/>)}
            </div>
        </div>
        {toolTip && tip &&
            <ul className='machines-tooltip' style={{position: 'fixed', top: tip.top, left: tip.left}}>
                <li>{tip.name}</li>
                <li>{tip.brand}</li>
            </ul>}
    </div>;
};

export default TypesView;
