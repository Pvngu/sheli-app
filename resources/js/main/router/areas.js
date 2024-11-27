import AreaIndex from '../views/areas/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/areas',
                component: AreaIndex,
                name: 'admin.areas.index',
                meta: {
                    requireAuth: true,
                    menuParent: "areas",
                    menuKey: "areas",
                    permission: "area_view"
                }
            },
        ]

    }
]
