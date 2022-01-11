import { createI18n } from 'vue-i18n';

function loadLocaleMessages() {
    // unit test --> eviter error "require.context is not a function"
    // const locales = (process.env.NODE_ENV !== 'test') && require.context('../assets/locales', true, /[A-Za-z0-9-_,\s]+\.json$/i);
    const locales = (process.env.NODE_ENV !== 'test') && import.meta.glob('/locales/*.json');
    const messages = {};
    if (locales) {
        for (const path in locales) {
            locales[path]().then((mod) => {
                console.log(path, mod)
            })
        }

        /*
        locales.keys().forEach((key) => {
            const matched = key.match(/([A-Za-z0-9-_]+)\./i);
            if (matched && matched.length > 1) {
                const locale = matched[1];
                messages[locale] = locales(key);
            }
        });
         */
    }
    return messages;
}
const i18n = createI18n({
    legacy: false,
    locale: 'fr-FR',
    globalInjection: true,
    messages: loadLocaleMessages(),
});
export default i18n;