import React from 'react';
import { getImage } from '../../api/get';

function InfoCard(props) {
    const {data, onMouseMove, onMouseOver, onMouseDown} = props;

    return (<figure className='info-card' onMouseMove={(e) => {
        onMouseMove({left: e.clientX, top: e.clientY, data});
    }} onMouseOver={onMouseOver} onMouseDown={() => onMouseDown(data)}>
            <img src={getImage(data.imageUrl)}/>
            <legend>{data.name}</legend>
        </figure>);
}

export default InfoCard;
