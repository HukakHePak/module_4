import React, {useEffect, useState} from 'react';
import ReactDOM from 'react-dom';
import {Logo} from "./Logo";
import LoginForm from "./LoginForm";
import WarningModal from "./WarningModal";
import {request} from "../api/request";
import MachinesView from "./TypesView/MachinesView";


function App() {
    const [token, setToken] = useState('test-token');
    const [warn, setWarn] = useState(false);
    const [view, setView] = useState('machines');

    useEffect(() => {
        window.addEventListener('beforeunload', logoutHandler);

        return () => window.removeEventListener('beforeunload', logoutHandler);
    }, [token]);

    function loginHandler(e) {
        request('login', null, 'post', e).then(response => {
            setToken(response.token);
            setWarn(false);
        }).catch((e) => {
            setWarn(true);
        });
    }

    function logoutHandler() {
        setToken('');
        //request('logout', token, 'delete').catch(() => {});
    }

    return (<>
            <header>
                <Logo/>
                <button className="login-button" onClick={logoutHandler}>
                    {token ? 'Logout' : 'Register'}
                </button>
            </header>
            <main>
                {token ?
                    (view === 'machines' && <MachinesView onCreate={() => setView('create')} token={token} full/>)
                    : <LoginForm onSubmit={loginHandler}/>
                }
                {warn && <WarningModal onClick={() => setWarn(false)}>Invalid login or password</WarningModal>}
            </main>
        </>
    );
}

if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}


