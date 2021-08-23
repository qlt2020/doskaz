export default function ({redirect, app, $axios}) {
    $axios.onError(function (error) {
        if (error.response.status === 401) {
            app.$cookies.set('redirect', app.context.route.fullPath, {
                maxAge: 60 * 5,
                path: '/'
            });
            return redirect(app.localePath({name: 'login'}))
        }
    });
}
