import React from 'react';
import {url} from "../../api/request";

function InfoCard(props) {
    const {data, onMouseMove, onMouseOver, onMouseDown} = props;

    return (<figure className='info-card' onMouseMove={(e) => {
        onMouseMove({left: e.clientX, top: e.clientY, data});
    }} onMouseOver={onMouseOver} onMouseDown={() => onMouseDown(data)}>
            <img src={url + 'images/' + data.imageUrl}/>
            <legend>{data.name}</legend>
        </figure>);
}

export default InfoCard;
