<template>
    <a-layout-sider
        :width="240"
        :style="{
            margin: '0 0 0 0',
            overflowY: 'auto',
            position: 'fixed',
            paddingTop: '8px',
            zIndex: 998,
        }"
        :trigger="null"
        :collapsed="menuCollapsed"
        :theme="appSetting.left_sidebar_theme"
        class="sidebar-right-border"
    >
        <div v-if="menuCollapsed" class="logo">
            <img
                :style="{
                    height: '32px',
                }"
                :src="
                    appSetting.left_sidebar_theme == 'dark'
                        ? appSetting.small_dark_logo_url
                        : appSetting.small_light_logo_url
                "
            />
        </div>
        <div v-else>
            <img
                :style="{
                    width: '150px',
                    height: '53px',
                    paddingLeft: appSetting.rtl ? '0px' : '20px',
                    paddingRight: appSetting.rtl ? '30px' : '0px',
                    paddingTop: '5px',
                    paddingBottom: '10px',
                    marginLeft: appSetting.rtl ? '0px' : '10px',
                    marginRight: appSetting.rtl ? '10px' : '0px',
                }"
                :src="
                    appSetting.left_sidebar_theme == 'dark'
                        ? appSetting.dark_logo_url
                        : appSetting.light_logo_url
                "
            />
            <CloseOutlined
                v-if="innerWidth <= 991"
                :style="{
                    marginLeft: appSetting.rtl ? '0px' : '45px',
                    marginRight: appSetting.rtl ? '45px' : '0px',
                    verticalAlign: 'super',
                    color: appSetting.left_sidebar_theme == 'dark' ? '#fff' : '#000000',
                }"
                @click="menuSelected"
            />
        </div>

        <div class="main-sidebar">
            <perfect-scrollbar
                :options="{
                    wheelSpeed: 1,
                    swipeEasing: true,
                    suppressScrollX: true,
                }"
            >
                <a-menu
                    :theme="appSetting.left_sidebar_theme"
                    :openKeys="openKeys"
                    v-model:selectedKeys="selectedKeys"
                    :mode="mode"
                    @openChange="onOpenChange"
                    :style="{ borderRight: 'none' }"
                >
                    <a-menu-item
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.dashboard.index' });
                            }
                        "
                        key="dashboard"
                    >
                        <HomeOutlined />
                        <span>{{ $t("menu.dashboard") }}</span>
                    </a-menu-item>

                    <!-- <a-menu-item
                        v-if="
                            (permsArray.includes('activity_log_view') ||
                                permsArray.includes('admin'))
                        "
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.activity_log.index' });
                            }
                        "
                        key="activity_log"
                    >
                        <FileSearchOutlined />
                        <span>{{ $t("menu.activity_log") }}</span>
                    </a-menu-item> -->

                    <!-- <a-menu-item
                        v-if="
                            permsArray.includes('team_chat_view') ||
                            permsArray.includes('admin')
                        "
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.team_chat.index' });
                            }
                        "
                        key="team_chat"
                    >
                        <a-badge dot :class="{ iconNotificationCollapsed : menuCollapsed, iconNotification : !menuCollapsed }" />
                        <CommentOutlined />
                        <span>{{ $t("menu.chat") }}</span>
                    </a-menu-item> -->

                    <a-menu-item
                        v-if="permsArray.includes('accidents_view') || permsArray.includes('admin')"
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.accidents.index' });
                            }
                        "
                        key="accidents"
                    >
                        <AlertOutlined />
                        <span>{{ $t("menu.accidents") }}</span>
                    </a-menu-item>

                    <a-menu-item
                        v-if="permsArray.includes('areas_view') || permsArray.includes('admin')"
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.areas.index' });
                            }
                        "
                        key="areas"
                    >
                        <ApartmentOutlined />
                        <span>{{ $t("menu.areas") }}</span>
                    </a-menu-item>

                    <a-menu-item
                        v-if="permsArray.includes('audits_view') || permsArray.includes('admin')"
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.audits.index' });
                            }
                        "
                        key="audits"
                    >
                        <FileProtectOutlined />
                        <span>{{ $t("menu.audits") }}</span>
                    </a-menu-item>

                    <a-menu-item
                        v-if="permsArray.includes('documents_view') || permsArray.includes('admin')"
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.documents.index' });
                            }
                        "
                        key="documents"
                    >    
                        <FileTextOutlined />
                        <span>{{ $t("menu.documents") }}</span>
                    </a-menu-item>

                    <LeftSideBarMainHeading
                        :title="$t('menu.training_management')"
                        :visible="menuCollapsed"
                    />

                    <a-menu-item
                        v-if="permsArray.includes('enrollments_view') || permsArray.includes('admin')"
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.enrollments.index' });
                            }
                        "
                        key="enrollments"
                    >
                        <BookOutlined />
                        <span>{{ $t("menu.trainings") }}</span>
                    </a-menu-item>

                    <a-menu-item
                        v-if="permsArray.includes('courses_view') || permsArray.includes('admin')"
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.courses.index' });
                            }
                        "
                        key="courses"
                    >  
                        <ReadOutlined />
                        <span>{{ $t("menu.courses") }}</span>
                    </a-menu-item>

                    <LeftSideBarMainHeading
                        :title="$t('menu.user_management')"
                        :visible="menuCollapsed"
                    />

                    <a-menu-item
                        v-if="
                            permsArray.includes('users_view') ||
                            permsArray.includes('admin')
                        "
                        @click="
                            () => {
                                menuSelected();
                                $router.push({ name: 'admin.users.index' });
                            }
                        "
                        key="users"
                    >
                        <SolutionOutlined />
                        <span>{{ $t("menu.staff_members") }}</span>
                    </a-menu-item>

                    <LeftSideBarMainHeading
                        :title="$t('menu.settings')"
                        :visible="menuCollapsed"
                    />

                    <component
                        v-for="(appModule, index) in appModules"
                        :key="index"
                        v-bind:is="appModule + 'Menu'"
                        @menuSelected="menuSelected"
                    />
                    
                    <a-menu-item
                        @click="
                            () => {
                                menuSelected();
                                $router.push({
                                    name: 'admin.settings.profile.index',
                                });
                            }
                        "
                        key="settings"
                    >
                        <SettingOutlined />
                        <span>{{ $t("menu.settings") }}</span>
                    </a-menu-item>

                    <a-menu-item @click="logout" key="logout">
                        <LogoutOutlined />
                        <span>{{ $t("menu.logout") }}</span>
                    </a-menu-item>
                </a-menu>
            </perfect-scrollbar>
        </div>
    </a-layout-sider>
</template>

<script>
import { defineComponent, ref, watch, onMounted, computed } from "vue";
import { Layout } from "ant-design-vue";
import { useStore } from "vuex";
import { useRoute } from "vue-router";
import {
    HomeOutlined,
    LogoutOutlined,
    UserOutlined,
    SettingOutlined,
    CloseOutlined,
    ShoppingCartOutlined,
    AppstoreOutlined,
    ShopOutlined,
    BarChartOutlined,
    CalculatorOutlined,
    TeamOutlined,
    WalletOutlined,
    BankOutlined,
    RocketOutlined,
    LaptopOutlined,
    AlertOutlined,
    DollarCircleOutlined,
    CopyrightCircleOutlined,
    IeOutlined,
    PhoneOutlined,
    FolderOpenOutlined,
    FileTextOutlined,
    SoundOutlined,
    ApartmentOutlined,
    ScheduleOutlined,
    SolutionOutlined,
    MailOutlined,
    MessageOutlined,
    FileProtectOutlined,
    FormOutlined,
    InsertRowBelowOutlined,
    FileSearchOutlined,
    FileDoneOutlined,
    CheckCircleOutlined,
    ReconciliationOutlined,
    CommentOutlined,
    BookOutlined,
    ReadOutlined,
} from "@ant-design/icons-vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import common from "../../common/composable/common";
import LeftSideBarMainHeading from "../components/common/typography/LeftSideBarMainHeading.vue";
const { Sider } = Layout;

export default defineComponent({
    components: {
        Sider,
        PerfectScrollbar,
        Layout,
        LeftSideBarMainHeading,

        HomeOutlined,
        LogoutOutlined,
        UserOutlined,
        SettingOutlined,
        CloseOutlined,
        ShoppingCartOutlined,
        AppstoreOutlined,
        ShopOutlined,
        BarChartOutlined,
        CalculatorOutlined,
        TeamOutlined,
        WalletOutlined,
        BankOutlined,
        RocketOutlined,
        LaptopOutlined,
        AlertOutlined,
        DollarCircleOutlined,
        CopyrightCircleOutlined,
        IeOutlined,
        FolderOpenOutlined,
        FileTextOutlined,
        SoundOutlined,
        PhoneOutlined,
        ApartmentOutlined,
        ScheduleOutlined,
        SolutionOutlined,
        MailOutlined,
        MessageOutlined,
        FileProtectOutlined,
        FormOutlined,
        InsertRowBelowOutlined,
        FileSearchOutlined,
        FileDoneOutlined,
        CheckCircleOutlined,
        ReconciliationOutlined,
        CommentOutlined,
        BookOutlined,
        ReadOutlined,
    },
    setup(props, { emit }) {
        const {
            appSetting,
            appType,
            user,
            permsArray,
            appModules,
            menuCollapsed,
            willSubscriptionModuleVisible,
        } = common();
        const rootSubmenuKeys = [
            "dashboard",
            "leads",
            "users",
            "salesmans",
            "forms",
            "form_field_names",
            "settings",
            "subscription",
        ];
        const store = useStore();
        const route = useRoute();

        const innerWidth = window.innerWidth;
        const openKeys = ref([]);
        const selectedKeys = ref([]);
        const mode = ref("inline");

        onMounted(() => {
            var menuKey =
                typeof route.meta.menuKey == "function"
                    ? route.meta.menuKey(route)
                    : route.meta.menuKey;

            if (route.meta.menuParent == "settings") {
                menuKey = "settings";
            }

            if (route.meta.menuParent == "subscription") {
                menuKey = "subscription";
            }

            if (innerWidth <= 991) {
                openKeys.value = [];
            } else {
                openKeys.value = menuCollapsed.value ? [] : [route.meta.menuParent];
            }

            selectedKeys.value = [menuKey.replace("-", "_")];
        });

        const logout = () => {
            store.dispatch("auth/logout");
        };

        const menuSelected = () => {
            if (innerWidth <= 991) {
                store.commit("auth/updateMenuCollapsed", true);
            }
        };

        const onOpenChange = (currentOpenKeys) => {
            const latestOpenKey = currentOpenKeys.find(
                (key) => openKeys.value.indexOf(key) === -1
            );

            if (rootSubmenuKeys.indexOf(latestOpenKey) === -1) {
                openKeys.value = currentOpenKeys;
            } else {
                openKeys.value = latestOpenKey ? [latestOpenKey] : [];
            }
        };

        watch(route, (newVal, oldVal) => {
            const menuKey =
                typeof newVal.meta.menuKey == "function"
                    ? newVal.meta.menuKey(newVal)
                    : newVal.meta.menuKey;

            if (innerWidth <= 991) {
                openKeys.value = [];
            } else {
                openKeys.value = [newVal.meta.menuParent];
            }

            if (newVal.meta.menuParent == "settings") {
                selectedKeys.value = ["settings"];
            } else if (newVal.meta.menuParent == "subscription") {
                selectedKeys.value = ["subscription"];
            } else {
                selectedKeys.value = [menuKey.replace("-", "_")];
            }
        });

        watch(
            () => menuCollapsed.value,
            (newVal, oldVal) => {
                const menuKey =
                    typeof route.meta.menuKey == "function"
                        ? route.meta.menuKey(route)
                        : route.meta.menuKey;

                if (innerWidth <= 991 && menuCollapsed.value) {
                    openKeys.value = [];
                } else {
                    openKeys.value = menuCollapsed.value ? [] : [route.meta.menuParent];
                }

                if (route.meta.menuParent == "settings") {
                    selectedKeys.value = ["settings"];
                } else if (route.meta.menuParent == "subscription") {
                    selectedKeys.value = ["subscription"];
                } else {
                    selectedKeys.value = [menuKey.replace("-", "_")];
                }
            }
        );

        return {
            mode,
            selectedKeys,
            openKeys,
            logout,
            innerWidth: window.innerWidth,

            onOpenChange,
            menuSelected,
            menuCollapsed,
            appSetting,
            appType,
            user,
            permsArray,
            appModules,
            willSubscriptionModuleVisible,
        };
    },
});
</script>

<style lang="less">
.main-sidebar .ps {
    height: calc(100vh - 62px);
}

@media only screen and (max-width: 1150px) {
    .ant-layout-sider.ant-layout-sider-collapsed {
        left: -80px !important;
    }
}

.iconNotification {
    position: absolute;
    top: 13px;
    left: 32px;
}

.iconNotificationCollapsed {
    position: absolute;
    top: 13px;
    left: 38px;
}
</style>
