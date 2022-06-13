import React from 'react';
import Button from "./Button";

function LoginForm(props) {
    function submitHandler(e) {
        e.preventDefault();
        const [l, p] = e.target;

        props.onSubmit({username: l.value, password: p.value});
    }

    return (
        <form className='login-form' onSubmit={submitHandler}>
            <span>Authorization</span>
            <input type='text' placeholder='login'/>
            <input type='text' placeholder='password'/>
            <Button type='submit'>Login</Button>
        </form>);
}

export default LoginForm;
