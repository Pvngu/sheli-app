import AccidentIndex from '../views/accidents/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/accidents',
                component: AccidentIndex,
                name: 'admin.accidents.index',
                meta: {
                    requireAuth: true,
                    menuParent: "accidents",
                    menuKey: "accidents",
                    permission: "accidents_view"
                }
            },
        ]

    }
]
