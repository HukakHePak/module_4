import React from 'react';

function InfoCard(props) {
    const {img, name, onClick} = props;

    return (
        <figure className='info-card'>
            <img src={img}/>
            <legend>{name}</legend>
        </figure>
    );
}

export default InfoCard;
