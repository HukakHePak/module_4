import React from 'react';
import Button from "./Button";

function WarningModal(props) {
    return (
        <div className='warning-modal'>
            <div>
                <span>{props.children}</span>
                <Button onClick={props.onClick}>Попробовать снова</Button>
            </div>
        </div>
    );
};

export default WarningModal;
