export const token = {
    get() {
        try {
            return localStorage.getItem('token');
        } catch {
            return '';
        }
    },
    set(token = '') {
        return localStorage.setItem('token', token);
    }
}
