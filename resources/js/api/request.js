export const url = 'http://localhost/api/';

export async function request(path, token = '', method = 'get', body) {
    const options = {
        method: method.toUpperCase(),
        headers: {
            'Content-Type': 'application/json'
        },
    }

    if (body) options.body = JSON.stringify(body);
    if (token) options.headers = {...options.headers, 'Authorization': `Bearer ${token}`};

    try {
        return fetch(url + path, options).then(r => {
            console.log(r);
            if (r.ok) return r.json();
            throw r.json();
        });
    } catch (e) {
        console.error(e);
    }
}
