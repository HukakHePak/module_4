import React, {useState} from 'react';
import HorizontalMenu from "./HorizontalMenu";
import Button from "bootstrap/js/src/button";
import InfoCard from "./InfoCard";

function TypesView(props) {
    const {full, data} = props;
    const [selected, setSelected] = useState('');

    function createHandler() {

    }

    return (full ?
            <div className='types-view full'>
                <HorizontalMenu selected={selected} data={data} onClick={setSelected}/>
                <Button onClick={createHandler}>Создать новую машину</Button>
                <div>
                    {data.map((item) => <InfoCard img={item.img} name={item.name}/>)} //filter
                </div>
            </div> :
            <div className='types-view'>

            </div>
    );
};

export default TypesView;
