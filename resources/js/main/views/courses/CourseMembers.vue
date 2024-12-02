<template>
    <a-avatar-group
        :max-count="4"
        :max-style="{
            color: '#f56a00',
            backgroundColor: '#fde3cf',
        }"
    >
        <a-tooltip
            v-for="member in members"
            :key="member.x_user_id"
            :title="member.user.name"
            placement="top"
        >
            <a-avatar :src="member.user.profile_image_url" />
        </a-tooltip>
    </a-avatar-group>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: ["members"],
    setup(props, { emit }) {
        return {};
    },
});
</script>
