<template>
    <a-row :gutter="[16,16]">
        <a-col :span="24" class="pt-10">
            <a-row align="middle" justify="space-between" class="px-20" :wrap="false">
                <a-col>
                    <a-typography-title :level="4" style="margin-bottom: 0;">
                    {{ $t(`menu.${chatType}`) }}
                    </a-typography-title>
                </a-col>
                <a-col v-if="chatType == 'chat'">
                    <a-space :size="16">
                        <MessageOutlined @click="$emit('onOpenAddNewChat')" class="icon-size" />
                    </a-space>
                </a-col>
            </a-row>
        </a-col>
        <a-col class="px-20" :span="24">
            <a-input
                v-model:value="filters.individualName"
                :placeholder="$t('common.search')"
                :bordered="false"
                style="background: #f0f0f0;"
                @change="() => {
                    searchLoading = true;
                    searchConversation()
                }"
            >
            <template #prefix>
                <SearchOutlined v-if="!searchLoading" class="ml-5 mr-15" />
                <LoadingOutlined v-else class="ml-5 mr-15" />
            </template>
            </a-input>
        </a-col>
        <a-col class="px-20" :span="24">
            <a-radio-group v-model:value="filters.messageStatus" buttonStyle="solid">
                <a-radio-button class="pill-radio" value="all">{{ $t('common.all') }}</a-radio-button>
                <a-radio-button class="pill-radio" value="unread">{{ $t('sms.unread') }}</a-radio-button>
                <a-radio-button v-if="chatType == 'chat'" class="pill-radio" value="groups">{{ $t('sms.groups') }}</a-radio-button>
            </a-radio-group>
        </a-col>
        <a-col :span=24>
            <perfect-scrollbar
            style="max-height: calc(100vh - 230px);"
            :options="{
            wheelSpeed: 1,
            swipeEasing: true,
            suppressScrollX: true,
            }"
            >
            <a-list item-layout="horizontal">
                <a-list-item v-for="conversation in conversations" class="list-item" :class="{ isActive : conversation.xid == selectedConversation }" @click="onSelectConversation(conversation)">
                    <a-list-item-meta>
                        <template #title>
                            <a-row justify="space-between">
                                <a-col>
                                    <strong>{{ chatType !== 'chat' ? `${conversation.first_name} ${conversation.last_name}` : conversation.chat_user[0].name }}</strong>
                                </a-col>
                                <a-col>
                                    <span style="color: #5762E1;" class="font-small">
                                        {{ chatType != 'chat' ? formatRelativeTime(conversation.last_message_date) : formatRelativeTime(conversation.last_message.created_at)  }}
                                    </span>
                                </a-col>
                            </a-row>
                        </template>
                        <template #avatar>
                            <a-avatar v-if="chatType != 'chat'" size="large">
                                {{ conversation.first_name.charAt(0) }}{{ conversation.last_name.charAt(0) }}
                            </a-avatar>
                            <a-avatar v-else :src="conversation.chat_user[0].profile_image_url" size="large" />
                        </template>
                        <template #description>
                            <a-row :wrap="false">
                                <a-col flex="auto">
                                    <a-typography-paragraph
                                        ellipsis
                                        :content="chatType != 'chat' ? conversation.last_message.content : conversation.last_message.content"
                                    />
                                </a-col>
                                <a-col flex="24px">
                                    <a-badge style="scale: 0.9;" v-if="conversation.unread_messages_count" :count="conversation.unread_messages_count" />
                                </a-col>
                            </a-row>
                        </template>
                    </a-list-item-meta>
                </a-list-item>
            </a-list>
            </perfect-scrollbar>
        </a-col>
    </a-row>
</template>

<script>
import { ref, onMounted, watch } from 'vue';
import { SearchOutlined, MessageOutlined, LoadingOutlined } from '@ant-design/icons-vue';
import common from "../../../common/composable/common";
import { debounce } from "lodash-es";

export default {
    props: {
        chatType: {
            default: '',
        }
    },
    components: {
        SearchOutlined,
        MessageOutlined,
        LoadingOutlined
    },
    emits: ['fetchMessages', 'onOpenAddNewChat'],
    setup(props, { emit }) {
        const conversations = ref({});
        const filters = ref({
            messageStatus: 'all',
            individualName: ''
        });
        const { formatRelativeTime } = common();
        const searchLoading = ref(false);
        const selectedConversation = ref('');

        onMounted(() => {
            getChatLog();
        });

        const getChatLog = () => {
            let url = '';

            url = props.chatType == 'messaging' ? 'get-individual-conversations' : 'get-team-chats';

            axiosAdmin.get(url, { 
                params: { 
                    status: filters.value.messageStatus,
                    nameFilter: filters.value.individualName,
                } 
            }).then((response) => {
                conversations.value = response.data;
            });
        };

        const onSelectConversation = (conversation) => {
            selectedConversation.value = conversation.xid;
            
            if(props.chatType === 'chat') {
                const { xid, name, role, profile_image_url, unread_messages_count } = conversation.chat_user[0];
                const userData = {
                    xid: conversation.chat_user[0].xid,
                    name: conversation.chat_user[0].name,
                    role: conversation.chat_user[0].role.name,
                    profile_image_url: conversation.chat_user[0].profile_image_url,
                    unread_messages_count: conversation.unread_messages_count
                };
                emit('fetchMessages', conversation.id, userData);
            } else {
                emit('fetchMessages', conversation);
            }
            
            conversation.unread_messages_count = 0;
        };

        watch(() => filters.value.messageStatus, (newValue) => {
            getChatLog();
        });

        return {
            filters,
            getChatLog,
            conversations,
            formatRelativeTime,
            searchLoading,
            selectedConversation,
            onSelectConversation,

            searchConversation: debounce(() => {
                getChatLog();
                searchLoading.value = false;
            }, 300)
        }
    }
}
</script>

<style>
.chat-sider {
    border-right: 1px solid #f0f0f0;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    background: #fff !important;
}

.pill-radio {
    border: #f0f0f0 solid 1px !important;
    border-radius: 20px !important;
    margin-right: 8px;
}

.pill-radio::before {
    width: 0 !important;
}

.isActive, .list-item:hover {
    background: #f0f0f0;
}

.list-item:hover {
    cursor: pointer;
}

.resize-content {
    width: calc(100% - 32%);
}
</style>

<style scoped>
.icon-size {
    font-size: 1rem;
}
</style>