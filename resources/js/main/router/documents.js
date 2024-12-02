
import DocIndex from '../views/documents/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/documents',
                component: DocIndex,
                name: 'admin.documents.index',
                meta: {
                    requireAuth: true,
                    menuParent: "documents",
                    menuKey: "documents",
                    permission: "doc_view"
                }
            },
        ]
    }
]