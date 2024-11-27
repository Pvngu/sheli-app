import UserIndex from '../views/users/index.vue';
import TeamChatIndex from '../views/team-chat/index.vue';

export default [
    {
        path: '/',
        component: () => import('../../common/layouts/Admin.vue'),
        children: [
            {
                path: '/admin/users',
                component: UserIndex,
                name: 'admin.users.index',
                meta: {
                    requireAuth: true,
                    menuParent: "users",
                    menuKey: "users",
                    permission: "users_view"
                }
            },
            {
                path: '/admin/team-chat',
                component: TeamChatIndex,
                name: 'admin.team_chat.index',
                meta: {
                    requireAuth: true,
                    menuParent: "team_chat",
                    menuKey: "team_chat",
                    permission: "team_chat_view"
                }
            }
        ]

    }
]
