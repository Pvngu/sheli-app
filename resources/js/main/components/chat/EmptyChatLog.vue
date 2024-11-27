<template>
    <a-layout>
        <a-row align="middle" justify="center" style="height: 100%;">
            <a-col>
                <a-row>
                    <img class="debichat-logo" src="/images/debichat_logo.svg" draggable="false" alt="debichat">
                </a-row>
                <a-row justify="center">
                    <a-typography-title class="debichat-name" :level="4">
                        DEBICHAT
                    </a-typography-title>
                </a-row>
            </a-col>
        </a-row>
    </a-layout>
</template>

<style scoped>
.debichat-name {
    color: #d9d9d9;
    user-select: none;
}
</style>