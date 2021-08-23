export default async function ({store, redirect, app, $axios, error}) {
    try {
        await store.dispatch('authentication/loadUser');
    } catch (e) {
    }

    $axios.onError(function (err) {
        if (err.response.status === 401) {
            app.$cookies.set('redirect', app.context.route.fullPath, {
                maxAge: 60 * 5
            });
            return redirect({name: 'index-login'})
        }
        else if (err.response.status === 400) {
        }
        else {
            return error(err)
        }
    });
}
