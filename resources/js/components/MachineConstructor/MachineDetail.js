import React from 'react';
import {url} from "../../api/request";
import InfoCard from "../TypesView/InfoCard";

function MachineDetail(props) {
    const { type, onMouseUp, inline, data, onRemove, onChange, counted } = props;
    return (
        <div className={'machine-detail ' + (inline && ' inline ') + (counted && ' counted ')}
        onMouseUp={() => onMouseUp(type)}>
            { counted ?
                (<>
                    <div className='machine-detail__header'>
                        <span className='machine-detail__name'>{type}</span>
                        <button onClick={onRemove}>remove</button>
                    </div>
                    <div className='machine-detail__info'>
                        {data && <InfoCard data={data}/>}
                        <div className='machine-detail__count'>
                            <span>Количество</span>
                            <input type='number' min='1' onChange={onChange}/>
                        </div>
                    </div>
                 </>) :
                (<>
                    <div className='machine-detail__header'>
                        <div className='machine-detail__info'>
                            <span className='machine-detail__name'>{type}</span>
                            <div className='machine-detail__count'>
                                <span>Количество</span>
                                <input type='number' min='1' onChange={onChange}/>
                            </div>
                        </div>
                        <button onClick={onRemove}>remove</button>
                    </div>
                    {data && <InfoCard data={data}/>}
                </>)
            }

        </div>
    );
}

export default MachineDetail;
