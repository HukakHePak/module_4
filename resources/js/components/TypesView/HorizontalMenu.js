import React from 'react';
import {formalize} from "../../helpers/helpers";

function HorizontalMenu(props) {
    const {data, selected, onSelect, asList} = props;

    return (
        asList ?
            <select className='type-menu' onChange={(e) => onSelect(e.target.value)}>
                {data.map((name, index) =>
                    <option className='type-menu__item'
                            value={name}
                            key={index}
                            selected={name === selected}
                    >
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
