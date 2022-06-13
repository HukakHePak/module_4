import React from 'react';

function HorizontalMenu(props) {
    const {data, selected, onSelect, asList} = props;

    function formalize(name) {
        return name[0].toUpperCase() + name.slice(1).replace('-', ' ');
    }

    return (
        asList ?
            <select className='type-menu'>
                {data.map((name, index) =>
                    <option key={index} selected={name === selected} className={'type-menu__item'}
                            onClick={() => onSelect(name)}>
                        {formalize(name)}
                    </option>)}
            </select> :
            <ul className='horizontal-menu'>
                {data.map((name, index) =>
                    <li key={index} className={'horizontal-menu__item ' + (name === selected && ' active')}
                            onClick={() => onSelect(name)}>
                        {formalize(name)}
                    </li>)}
            </ul>
    )
        ;
}

export default HorizontalMenu;
