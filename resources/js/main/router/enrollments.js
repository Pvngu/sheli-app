
import EnrollmentIndex from '../views/enrollments/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/enrollments',
                component: EnrollmentIndex,
                name: 'admin.enrollments.index',
                meta: {
                    requireAuth: true,
                    menuParent: "enrollments",
                    menuKey: "enrollments",
                    permission: "enrollment_view"
                }
            },
        ]

    }
]