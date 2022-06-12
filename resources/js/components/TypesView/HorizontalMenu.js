import React from 'react';

function HorizontalMenu(props) {
    const {data, selected, onSelect} = props;

    return (
        <ul className='horizontal-menu'>
            {data.map(({name}) =>
                <button className={'horizontal-menu__item' + (name === selected && ' active')}
                        onClick={() => onSelect(name)}>
                    {name}
                </button>)}
        </ul>);
}

export default HorizontalMenu;
