<template>
    <a-drawer
        :title="pageTitle"
        :width="drawerWidth"
        :open="visible"
        :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }"
        :maskClosable="false"
        @close="onClose"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        label="Course Name"
                        name="course_name"
                        :help="
                            rules.course_name ? rules.course_name.message : null
                        "
                        :validateStatus="rules.course_name ? 'error' : null"
                    >
                        <a-input
                            v-model:value="formData.course_name"
                            placeholder="Enter Course Name"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        label="User"
                        name="trainer_id"
                        :help="rules.trainer_id ? rules.trainer_id.message : null"
                        :validateStatus="rules.trainer_id ? 'error' : null"
                    >
                        <UserSelect
                            @onChange="
                                (id) => {
                                    formData.trainer_id = id;
                                }
                            "
                            :value="formData.trainer_id"
                            :data="allUsers"
                            :fetchUserData="false"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        label="Start Date"
                        name="start_date"
                        :help="
                            rules.start_date ? rules.start_date.message : null
                        "
                        :validateStatus="rules.start_date ? 'error' : null"
                    >
                        <DateTimePicker
                            :dateTime="formData.start_date"
                            :showTime="false"
                            :onlyDate="true"
                            :isFutureDateDisabled="false"
                            @dateTimeChanged="
                                (changedDateTime) =>
                                    (formData.start_date = changedDateTime)
                            "
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        label="End Date"
                        name="end_date"
                        :help="rules.end_date ? rules.end_date.message : null"
                        :validateStatus="rules.end_date ? 'error' : null"
                    >
                        <DateTimePicker
                            :dateTime="formData.end_date"
                            :isFutureDateDisabled="false"
                            :showTime="false"
                            :onlyDate="true"
                            @dateTimeChanged="
                                (changedDateTime) =>
                                    (formData.end_date = changedDateTime)
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            
            <a-row v-if="addEditType === 'edit'" :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item
                        label="Status"
                        name="status"
                        :help="rules.status ? rules.status.message : null"
                        :validateStatus="rules.status ? 'error' : null"
                    >
                        <a-select v-model:value="formData.status" placeholder="Select Status">
                            <a-select-option value="active">{{ $t("course.active") }}</a-select-option>
                            <a-select-option value="inactive">{{ $t("course.inactive") }}</a-select-option>
                            <a-select-option value="finished">{{ $t("course.finished") }}</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="16">
                <a-col :span="24">
                    <a-form-item
                        label="Description"
                        name="description"
                        :help="
                            rules.description ? rules.description.message : null
                        "
                        :validateStatus="rules.description ? 'error' : null"
                    >
                        <a-textarea
                            v-model:value="formData.description"
                            :rows="4"
                            placeholder="Enter Description"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('user.members')"
                        name="user_id"
                        :help="rules.user_id ? rules.user_id.message : null"
                        :validateStatus="rules.user_id ? 'error' : null"
                        class="required"
                    >
                        <span style="display: flex">
                            <a-select
                                v-model:value="formData.user_id"
                                mode="multiple"
                                :placeholder="
                                    $t('common.select_default_text', [
                                        $t('user.members'),
                                    ])
                                "
                                :allowClear="true"
                            >
                                <a-select-option
                                    v-for="allStaffMember in allStaffMembers"
                                    :key="allStaffMember.xid"
                                    :value="allStaffMember.xid"
                                >
                                    {{ allStaffMember.name }}
                                </a-select-option>
                            </a-select>
                            <StaffMemberAddButton
                                @onAddSuccess="staffMemberAdded"
                            />
                        </span>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>

        <template #footer>
            <a-space>
                <a-button
                    key="submit"
                    type="primary"
                    :loading="loading"
                    @click="onSubmit"
                >
                    <template #icon>
                        <SaveOutlined />
                    </template>
                    {{
                        addEditType == "add"
                            ? $t("common.create")
                            : $t("common.update")
                    }}
                </a-button>
                <a-button key="back" @click="onClose">
                    {{ $t("common.cancel") }}
                </a-button>
            </a-space>
        </template>
    </a-drawer>
</template>

<script>
import { defineComponent, onMounted, ref, watch } from "vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";
import StaffMemberAddButton from "../users/StaffAddButton.vue";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "allUsers",
    ],
    components: {
        SaveOutlined,
        DateTimePicker,
        UserSelect,
        StaffMemberAddButton,
    },
    setup(props, { emit }) {
        const { appSetting } = common();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const staffMembersUrl = "users?limit=10000";
        const allStaffMembers = ref([]);

        const onSubmit = () => {
            addEditRequestAdmin({
                url: props.url,
                data: props.formData,
                successMessage: props.successMessage,
                success: (res) => {
                    emit("addEditSuccess", res.xid);
                },
            });
        };

        onMounted(() => {
            const staffMemberPromise = axiosAdmin.get(staffMembersUrl);
            Promise.all([staffMemberPromise]).then(([staffMemberResponse]) => {
                allStaffMembers.value = staffMemberResponse.data;
            });
        });

        const staffMemberAdded = () => {
            axiosAdmin.get(staffMembersUrl).then((response) => {
                allStaffMembers.value = response.data;
            });
        };

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };

        watch(
            () => props.visible,
            (newVal, oldVal) => {
                // For campaign members
                if (
                    newVal &&
                    props.addEditType == "edit" &&
                    props.data &&
                    props.data.enrollments &&
                    props.data.enrollments.length > 0
                ) {
                    props.formData.user_id = props.data.enrollments.map(function (el) {
                        return el.x_user_id;
                    });
                } else {
                    props.formData.user_id = undefined;
                }
            }
        );
        return {
            appSetting,
            loading,
            rules,
            onClose,
            onSubmit,
            allStaffMembers,
            staffMemberAdded,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>
