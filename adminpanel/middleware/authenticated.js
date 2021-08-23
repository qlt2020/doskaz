export default function ({store, app, redirect, error}) {
    if (!store.state.authentication.user) {
        app.$cookies.set('redirect', app.context.route.fullPath, {
            maxAge: 60 * 5
        });
        return redirect('/login')
    }
}
