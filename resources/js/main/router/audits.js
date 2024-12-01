
import AuditIndex from '../views/audits/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/audits',
                component: AuditIndex,
                name: 'admin.audits.index',
                meta: {
                    requireAuth: true,
                    menuParent: "audits",
                    menuKey: "audits",
                    permission: "audit_view"
                }
            },
        ]
    }
]