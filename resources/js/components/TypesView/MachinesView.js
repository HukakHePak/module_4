import React, {useEffect, useState} from 'react';
import InfoCard from "./InfoCard";
import HorizontalMenu from "./HorizontalMenu";
import Button from "../Button";
import {request} from "../../api/request";

function TypesView(props) {
    const {types, onCreate, token, full, toolTip, onDrag} = props;
    const [list, setList] = useState([]);
    const [selected, setSelected] = useState(types[0]);
    const [tip, setTip] = useState();

    useEffect(async () => {
        if (!selected) return;
        const data = await request(selected, token);
        const brands = new Map((await request('brands?pageSize=100', token)).map(({id, name}) => [id, name]));

        setList(data.map(item => {
            return {...item, brand: brands.get(item.brandId)}
        }))
    }, [selected]);

    return <div className={'machines-view ' + (!full && ' list')}>
        <HorizontalMenu selected={selected} data={types} onSelect={setSelected} asList={!full}/>

        {full && <Button onClick={onCreate}>Create machine</Button>}

        <div className='machines-list'>
            <div className='machines-grid'>
                {list.map((item) => <InfoCard key={item.id}
                                              data={item}
                                              onMouseMove={setTip}
                                              onMouseLeave={() => setTip()}
                                              onMouseDown={onDrag}
                                              type={selected}
                />)}
            </div>
        </div>

        {toolTip && tip &&
            <ul className='machines-tooltip' style={{top: tip.top, left: tip.left}}>
                <li>
                    <span>Name</span>
                    <span>{tip.data.name}</span>
                </li>
                {tip.data.brand && <li><span>Brand</span><span>{tip.data.brand}</span></li>}
            </ul>}
    </div>;
};

export default TypesView;
