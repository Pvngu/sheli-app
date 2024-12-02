
import CourseIndex from '../views/courses/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/courses',
                component: CourseIndex,
                name: 'admin.courses.index',
                meta: {
                    requireAuth: true,
                    menuParent: "courses",
                    menuKey: "courses",
                    permission: "course_view"
                }
            },
        ]
    }
]