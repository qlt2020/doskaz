export default function ({$axios}) {
    $axios.onRequest(config => {
        if(config.method !== 'get') {
            if(!config.params) {
                config.params = {};
            }
            config.params.validationErrorsOld = 1
        }
    })
}
