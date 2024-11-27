import Translation from '../../views/settings/translations/index.vue';
import StorageEdit from '../../views/settings/storage/Edit.vue';

// Defining route prefix and permission
// According to app_type
const appType = window.config.app_type;
const routePrefix = appType == 'non-saas' ? 'admin' : 'superadmin';

export default [
    {
        path: 'translations',
        component: Translation,
        name: `${routePrefix}.settings.translations.index`,
        meta: {
            requireAuth: true,
            menuParent: "settings",
            menuKey: route => "translations",
            permission: appType == "non-saas" ? "translations_view" : "superadmin"
        }
    },
];
