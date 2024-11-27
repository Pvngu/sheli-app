<template>
    <a-drawer
        :open="visible"
        :get-container="false"
        :mask="false"
        placement="left"
        :title="$t('team_chat.add')"
        rootClassName="details-drawer-form new-chat-drawer"
        @close="$emit('onClose')"
        width="100%"
    >
        <a-row>
            <a-col :span="24">
                <a-typography-title :level="5">
                    {{ $t('user.staff_members') }}
                </a-typography-title>
            </a-col>
        </a-row>
        <a-row class="ml-25 mt-10" v-if="Object.keys(allStaffMembers).length === 0">
            <a-col :span="24">
                <a-skeleton class="mt-10" avatar :paragraph="{ rows: 0 }" />
                <a-skeleton class="mt-10" avatar :paragraph="{ rows: 0 }" />
                <a-skeleton class="mt-10" avatar :paragraph="{ rows: 0 }" />
                <a-skeleton class="mt-10" avatar :paragraph="{ rows: 0 }" />
                <a-skeleton class="mt-10" avatar :paragraph="{ rows: 0 }" />
            </a-col>
        </a-row>
        <a-row class="mt-10" v-else >
            <a-col v-for="( users, initial ) in allStaffMembers" :span="24">
                <a-row>
                    <a-typography-title :level="5">
                        {{ initial }}
                    </a-typography-title>
                </a-row>
                <a-row>
                    <a-col :span="24">
                        <a-list item-layout="horizontal" :data-source="users">
                            <template #renderItem="{ item }">
                                <a-list-item @click="findChat(item)" class="list-item">
                                    <a-list-item-meta :description="item.role">
                                        <template #title>
                                            <a-row justify="space-between">
                                                <a-col>
                                                    <strong>{{ item.name }}</strong>
                                                </a-col>
                                            </a-row>
                                        </template>
                                        <template #avatar>
                                            <a-avatar size="large" :src="item.profile_image_url" :alt="item.name" />
                                        </template>
                                    </a-list-item-meta>
                                </a-list-item>
                            </template>
                        </a-list>
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </a-drawer>
</template>

<script>
import { ref, watch } from 'vue';
export default {
    props: {
        visible: {
            default: false
        }
    },
    emits: ['fetchMessages', 'onClose', 'newChat'],
    setup(props, { emit }) {
        const allStaffMembers = ref([]);
        const staffMembersUrl = "get-alphabetical-users";

        const findChat = (user) => {
            const userData = {
                xid: user.xid,
                name: user.name,
                role: user.role,
                profile_image_url: user.profile_image_url,
            };

            axiosAdmin.get(`get-user-have-chat/${user.xid}`).then((response) => {
                if(response.data) {
                    let chatXId = response.data.id;
                    emit('fetchMessages', chatXId, userData);
                } else {
                    emit('newChat', userData);
                }
            });
            emit('onClose');
        }

        watch(() => props.visible, (newValue) => {
            if (newValue && allStaffMembers.value.length === 0) {
                axiosAdmin.get(staffMembersUrl).then((response) => {
                    allStaffMembers.value = response.data.users;
                });
            }
        });

        return {
            allStaffMembers,
            findChat
        }
    }
}
</script>

<style>
.new-chat-drawer {
    padding: 0;
}
</style>