import React, {useState} from 'react';
import ReactDOM from 'react-dom';
import {Logo} from "./Logo";
import {token as t} from "../storage/token";
import LoginForm from "./Login/LoginForm";
import WarningModal from "./WarningModal";
import {request} from "../api/request";


function App() {
    const [token, setToken] = useState('');
    const [warn, setWarn] = useState(false);

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
        request('login', token, 'delete');
    }

    return (<>
            <header>
                <Logo/>
                <button className="login-button" onClick={logoutHandler}>
                    {token ? 'Logout' : 'Register'}
                </button>
            </header>
            <main>
                {token ? <div className="container">

                    </div>
                    : <LoginForm onSubmit={loginHandler}/>
                }
                {warn && <WarningModal onClick={()=>setWarn(false)}>Invalid login or password</WarningModal>}
            </main>

        </>

    );
}

if (document.getElementById('app')) {
    ReactDOM.render(<App/>, document.getElementById('app'));
}
