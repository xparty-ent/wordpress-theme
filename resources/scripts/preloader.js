const preloader = {
    register() {

    },

    dismiss() {
        return new Promise((resolve, reject) => {
            resolve();
        });
    }
};

export default preloader;