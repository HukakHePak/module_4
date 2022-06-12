import React from 'react';

function MachineDetail(props) {
    const { type, onMouseUp, inline, data, onRemove, onChange } = props;
    return (
        <div className={'machine-detail' + (inline && ' inline')}
        onMouseUp={onMouseUp}>
            <img src={data.src}/>
            <div>
                <div>
                    <span>{data.name}</span>
                    <div>
                        <span>Количество</span>
                        <input onChange={onChange}/>
                    </div>
                </div>
                <button onClick={onRemove}>remove</button>
            </div>
        </div>
    );
}

export default MachineDetail;
