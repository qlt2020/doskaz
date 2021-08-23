export default function ({$axios, app}) {
    $axios.onRequest(config => {
        config.headers['Accept-Language'] = app.i18n.locale
        if(config.method !== 'get') {
            if(!config.params) {
                config.params = {};
            }
            config.params.validationErrorsOld = 1
        }
    })
}
