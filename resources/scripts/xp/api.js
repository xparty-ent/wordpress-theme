import axios from 'axios';

const api = {
    login(username, password, remember_me = false) {
        return new Promise((resolve, reject) => {
            axios.post('/account/login', {
                user_login: username,
                user_password: password,
                remember_me: remember_me
            })
            .then(result => {
                if(!result.data.success) {
                    reject({
                        code: result.data.error_code,
                        message: result.data.error_message
                    });
                    return;
                }

                resolve();
            })
            .catch(reject);
        });
    }
};

export default api;