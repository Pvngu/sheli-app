<template>
    <a-layout style="height: calc(100vh - 64px);">
        <a-layout-sider width="25%" class="chat-sider" style="position: relative; overflow: hidden;" >
            <ConversationLog
                chatType="chat"
                @fetchMessages="fetchMessages"
                @onOpenAddNewChat="() => { AddNewvisible = !AddNewvisible }"
            />
            <NewChat
                :visible="AddNewvisible"
                @fetchMessages="fetchMessages"
                @newChat="newChat"
                @onClose="() => { AddNewvisible = false }"
            />
        </a-layout-sider>
        <a-layout v-if="Object.keys(selectedUser).length !== 0" style="position: relative;">
            <a-layout-header class="chat-header">
                <a-row align="middle" justify="space-between">
                    <a-col>
                        <a-row align="middle">
                            <a-col>
                                <a-avatar :src="selectedUser.profile_image_url" size="large" :alt="selectedUser.name" />
                            </a-col>
                            <a-col class="ml-10">
                                <a-row>
                                    <a-typography-title :level="5" class="mb-0">
                                        {{ selectedUser.name }}
                                    </a-typography-title>
                                </a-row>
                                <a-row>
                                    <a-typography-paragraph class="font-small mb-0">
                                        {{ selectedUser.role }}
                                    </a-typography-paragraph>
                                </a-row>
                            </a-col>
                        </a-row>
                    </a-col>
                    <a-col>
                        <a-button shape="round" @click="() => { visible = !visible }">
                            <template #icon>
                                <ExclamationCircleOutlined />
                            </template>
                            {{ $t('common.details') }}
                        </a-button>
                    </a-col>
                </a-row>
            </a-layout-header>
            <ChatLog
                :isDetailOpen="visible"
                :messages="messages"
                :selectedUser="selectedUser"
                :scrollToBottom="scrollToBottom"
                :disableInfiniteScroll="disableInfiniteScroll"
                @loadMoreMessages="loadMoreMessages"
            />
            <a-layout-footer class="content-padding" :class="{ 'resize-content': visible }">
                <a-row :gutter="16">
                    <a-col :span="23">
                        <a-input 
                            :placeholder="$t('sms.type_a_message')"
                            size="large"
                            v-model:value="bodyMessage"
                            @keyup.enter="() => { bodyMessage ? onSubmit() : null }"
                        />
                    </a-col>
                    <a-col :span="1" style="margin: auto">
                        <a-button :disabled="!bodyMessage" type="primary" shape="circle" @click="onSubmit()">
                            <template #icon>
                                <SendOutlined />
                            </template>
                        </a-button>
                    </a-col>
                </a-row>
            </a-layout-footer>
        </a-layout>
        <EmptyChatLog  v-else />
    </a-layout>
</template>

<script> 
import { ref, onMounted } from 'vue';
import NewChat from './NewChat.vue';
import ConversationLog from '../../components/chat/ConversationLog.vue';
import ChatLog from '../../components/chat/ChatLog.vue';
import EmptyChatLog from '../../components/chat/EmptyChatLog.vue';
import { ExclamationCircleOutlined, SendOutlined } from '@ant-design/icons-vue';
import common from "../../../common/composable/common";
import { forEach } from "lodash-es";
export default {
    components: {
        ConversationLog,
        EmptyChatLog,
        ChatLog,
        NewChat,
        ExclamationCircleOutlined,
        SendOutlined
    },
    setup() {
        const messages = ref({});
        const bodyMessage = ref("");
        const selectedUser = ref({});
        const visible = ref(false);
        const AddNewvisible = ref(false);
        const { formatMsgDate } = common();
        const scrollToBottom = ref(false);
        const page = ref(1);
        const maxPage = ref(1);
        const disableInfiniteScroll = ref(false);
        const sentMsgCount = ref(0);

        const newChat = (data) => {
            messages.value = {};
            selectedUser.value = data;
        };

        const fetchMessages = (chatId, userData) => {
            if(selectedUser.value.x_user_id !== userData.xid) {
                messages.value = {};
                bodyMessage.value = "";
                page.value = 1;

                let messagesPromise = axiosAdmin.get(`get-team-messages/${chatId}`);

                Promise.all([messagesPromise]).then(
                    ([messagesResponse]) => {
                        messages.value = messagesResponse.data;
                        selectedUser.value = {
                            x_user_id: userData.xid,
                            name: userData.name,
                            role: userData.role,
                            profile_image_url: userData.profile_image_url,
                            team_chat_id: chatId,
                        };
                        maxPage.value = messagesResponse.max_page;
                        scrollToBottom.value = !scrollToBottom.value;
                        maxPage.value !== 1 ? disableInfiniteScroll.value = false : disableInfiniteScroll.value = true;
                        sentMsgCount.value = 0;

                        if(userData.unread_messages_count > 0) {
                            axiosAdmin.put('team-messages/update-read-status', {
                                team_chat_id: chatId
                            });
                        }

                        // window.Echo.channel(`team-chat.${selectedUser.value.team_chat_id}`)
                        // .listen('MessageSent', (res) => {
                        //     console.log(res);
                        //     const dateKey = formatMsgDate(new Date());
                        //     messages.value[dateKey].push(res.message);
                        // });

                        // Echo.private(`team-chat.1`)
                        // .listen('MessageSent', (res) => {
                        //     console.log(res);
                        //     const dateKey = formatMsgDate(new Date());
                        //     messages.value[dateKey].push(res.message);
                        // });
                    }
                );
            }
        };

        const loadMoreMessages = () => {
            if(page.value <= maxPage.value) {

                page.value++;
                if(page.value === maxPage.value) {
                    disableInfiniteScroll.value = true;
                }

                axiosAdmin.get(`get-team-messages/${selectedUser.value.team_chat_id}`, {
                    params: {
                        page: page.value,
                        sentMsgCount: sentMsgCount.value,
                    }
                })
                .then((res) => {
                    forEach(res.data, (value, key) => {
                        if(!messages.value[key]) {
                            messages.value = { [key]: [], ...messages.value };
                        }
                        messages.value[key].unshift(...value);
                    });
                });
            }
        }

        const onSubmit = () => {
            axiosAdmin.post('send-team-message', {
                team_chat_id: selectedUser.value.team_chat_id ?? null,
                x_user_id: selectedUser.value.xid,
                body: bodyMessage.value,
            }).then((res) => {
                if(res.data.success) {
                    const dateKey = formatMsgDate(new Date());
                    if (!messages.value[dateKey]) {
                        messages.value[dateKey] = [];
                    }
                    messages.value[dateKey].push(res.data.message);
                    bodyMessage.value = "";
                    scrollToBottom.value = !scrollToBottom.value;
                    sentMsgCount.value++;
                }
            });
        };
        
        return {
            fetchMessages,
            messages,
            bodyMessage,
            visible,
            selectedUser,
            onSubmit,
            AddNewvisible,
            newChat,
            formatMsgDate,
            loadMoreMessages,
            scrollToBottom,
            disableInfiniteScroll
        }
    }
}
</script>