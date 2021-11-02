export default function({ store, app, redirect }) {
  if (store.state.authentication.user) {
      return redirect(app.localePath({name: 'profile'}))
  }
}
