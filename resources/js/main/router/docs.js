
import DocIndex from '../views/docs/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/docs',
                component: DocIndex,
                name: 'admin.docs.index',
                meta: {
                    requireAuth: true,
                    menuParent: "docs",
                    menuKey: "docs",
                    permission: "doc_view"
                }
            },
        ]
    }
]