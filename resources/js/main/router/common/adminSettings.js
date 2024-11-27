
import CommonSettings from "./settings";

// This is for admin panel for the sass
const appType = window.config.app_type;

const allRoutes = appType == 'saas' ?
    [
        {
            path: 'email',
            component: EmailEdit,
            name: 'admin.settings.email.index',
            meta: {
                requireAuth: true,
                menuParent: "settings",
                menuKey: route => "email_settings",
                permission: "email_edit"
            }
        },
    ] : [...CommonSettings];

export default allRoutes;
