<template>
    <a-layout-content class="message-container" :class="{ 'resize-content': isDetailOpen }">
        <div v-if="Object.keys(this.messages).length === 0 && this.selectedUser.team_chat_id !== ''" class="content-padding message-spacer">
            <div class="inbox-container">
                <a-col style="height: 100%;" :span="24">
                    <a-skeleton />
                    <a-skeleton class="mt-20" />
                    <a-skeleton class="mt-20" />
                    <a-skeleton class="mt-20" />
                    <a-skeleton class="mt-20" />
                    <a-skeleton class="mt-20" />
                    <a-skeleton class="mt-20" />
                    <a-skeleton class="mt-20" />
                    <a-skeleton class="mt-20" />
                </a-col>
            </div>
        </div>
        <div v-else class="content-padding message-spacer" ref="scrollElement">
            <div class="inbox-container">
                <InfiniteLoading v-if="!disableInfiniteScroll" @infinite="load">
                    <template #spinner>
                        <a-row justify="center">
                            <a-col>
                                <a-spin :indicator="indicator" />
                            </a-col>
                        </a-row>
                    </template>
                </InfiniteLoading>
                <div v-for="( messages, date ) in messages" :key="date">
                    <a-divider>
                        <div class="font-small date-divider">
                            {{ formatDayOrDate(date) }}
                        </div>
                    </a-divider>
                    <a-row v-for="message in messages" :class="{ 'bubble-end' : isMsgInbound(message) }">
                        <a-col class="message-bubble">
                            <a-row justify="end">
                                <a-col>
                                    <span class="font-small">{{ formatTime(message.created_at) }}</span>
                                    <MessageOutlined class="font-small ml-5" />
                                </a-col>
                            </a-row>
                            <a-row>
                                <a-col class="message-bubble-content" :class="{ 'message-inbound' : isMsgInbound(message) }">
                                    {{ message.content }}
                                </a-col>
                            </a-row>
                        </a-col>
                    </a-row>
                </div>
            </div>
        </div>
    </a-layout-content>
</template>

<script>
import { MessageOutlined } from '@ant-design/icons-vue';
import common from "../../../common/composable/common";
import { ref, h, computed, nextTick, watch } from 'vue';
import InfiniteLoading from "v3-infinite-loading";
import { LoadingOutlined } from '@ant-design/icons-vue';

export default {
    props: {
        messages: {
            default: {},
        },
        isDetailOpen: {
            default: false,
        },
        selectedUser: {
            default: {},
        },
        disableInfiniteScroll: {
            default: false,
        },
        scrollToBottom: {
            default: false,
        },
    },
    components: {
        MessageOutlined,
        InfiniteLoading,
        LoadingOutlined
    },
    emits: ['loadMoreMessages'],
    setup(props, { emit }) {
        const { formatTime, formatDayOrDate } = common();
        const showMessages = ref(false);
        const indicator = h(LoadingOutlined);
        const scrollElement = ref(null);
        const height = ref(0);
        const newHeight = ref(0);

        const isMsgInbound = (message) => {
            if(!props.selectedUser.x_user_id) {
                return !message.is_inbound;
            }
            return message.x_sender_id != props.selectedUser.x_user_id;
        }

        const load = () => {
            emit('loadMoreMessages');

            setTimeout(() => {
                newHeight.value = scrollElement.value.scrollHeight;
                scrollElement.value.scrollTop = newHeight.value - height.value;
                height.value = newHeight.value;
            }, 80);
        }

        const scrollToBottom = () => {
            console.log('scrolling to bottom');
            nextTick(() => {
                scrollElement.value.scrollTop = scrollElement.value.scrollHeight;
                height.value = scrollElement.value.scrollHeight;
            });
        }

        watch(() => props.scrollToBottom, () => {
            scrollToBottom();
        });

        return {
            formatTime,
            formatDayOrDate,
            isMsgInbound,
            showMessages,
            load,
            indicator,
            height,
            scrollElement,
        }
    }
}
</script>

<style scoped>
.content-padding {
    padding: 10px 20px !important;
}

.message-container {
  --dot-bg: #f6f8fa;
  --dot-color: rgb(0, 0, 0, 0.5);
  --dot-size: 1px;
  --dot-space: 30px;
	background:
		linear-gradient(90deg, var(--dot-bg) calc(var(--dot-space) - var(--dot-size)), transparent 1%) center / var(--dot-space) var(--dot-space),
		linear-gradient(var(--dot-bg) calc(var(--dot-space) - var(--dot-size)), transparent 1%) center / var(--dot-space) var(--dot-space),
		var(--dot-color);
}

.inbox-container {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    min-height: 100%;
}

.message-spacer {
    width: 100%;
    height: 100%;
    overflow-y: scroll;
}

.date-divider {
    background: #f0f0f0;
    padding: 5px;
    border-radius: 5px;
}

.message-bubble-content {
    padding-inline: 12px;
    padding-block: 16px;
    border-radius: 7.5px;
    background: #f0f0f0;
}

.message-color {
    background: #f0f0f0;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 10px;
}

.message-inbound {
    background: #e8f1fe !important;
}

.bubble-end {
    justify-content: end;
}
</style>