export default function ({ app }) {
    app.i18n.onLanguageSwitched = (oldLocale, newLocale) => {
        app.store.dispatch('cities/load')
        app.store.dispatch('objectCategories/load')
    }
}