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
                :label="$t('accident.date_of_accident')"
                name="date_of_accident"
                :help="rules.date_of_accident ? rules.date_of_accident.message : null"
                :validateStatus="rules.date_of_accident ? 'error' : null"
                >
                <DateTimePicker
                    :dateTime="formData.date_of_accident"
                    :showTime="true"
                    @dateTimeChanged="
                        (changedDateTime) =>
                            (formData.date_of_accident = changedDateTime)
                    "
                />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                label="Injured Person"
                name="injured_person"
                :help="rules.injured_person ? rules.injured_person.message : null"
                :validateStatus="rules.injured_person ? 'error' : null"
                >
                <UserSelect
                    @onChange="(id) => {
                    formData.injured_person = id;
                    }"
                    :value="formData.injured_person"
                    :data="allUsers"
                    :fetchUserData="false"
                />
                </a-form-item>
            </a-col>
            </a-row>
            <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                label="Reporting User"
                name="reporting_user"
                :help="rules.reporting_user ? rules.reporting_user.message : null"
                :validateStatus="rules.reporting_user ? 'error' : null"
                >
                <UserSelect
                    @onChange="(id) => {
                    formData.reporting_user = id;
                    }"
                    :value="formData.reporting_user"
                    :data="allUsers"
                    :fetchUserData="false"
                />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                label="Area"
                name="area_id"
                :help="rules.area_id ? rules.area_id.message : null"
                :validateStatus="rules.area_id ? 'error' : null"
                >
                <a-select
                    v-model:value="formData.area_id"
                    placeholder="Select Area"
                >
                    <a-select-option
                    v-for="area in allAreas"
                    :key="area.xid"
                    :value="area.xid"
                    >
                    {{ area.name }}
                    </a-select-option>
                </a-select>
                </a-form-item>
            </a-col>
            </a-row>
            <a-row :gutter="16">
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                label="Days Absent"
                name="days_absent"
                :help="rules.days_absent ? rules.days_absent.message : null"
                :validateStatus="rules.days_absent ? 'error' : null"
                >
                <a-input-number
                    v-model:value="formData.days_absent"
                    :min="1" 
                    style="width: 100%"
                />
                </a-form-item>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-form-item
                label="Status"
                name="status"
                :help="rules.status ? rules.status.message : null"
                :validateStatus="rules.status ? 'error' : null"
                >
                <a-select
                    v-model:value="formData.status"
                    placeholder="Select Status"
                >
                    <a-select-option
                        value="resolved"
                    >
                        {{ $t('accident.resolved') }}
                    </a-select-option>
                    <a-select-option
                        value="reported"
                    >
                        {{ $t('accident.reported') }}
                    </a-select-option>
                    <a-select-option
                        value="in_progress"
                    >
                        {{ $t('accident.in_progress') }}
                    </a-select-option>
                </a-select>
                </a-form-item>
            </a-col>
            </a-row>
            <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="24" :lg="24">
                <a-form-item
                label="Description"
                name="description"
                :help="rules.description ? rules.description.message : null"
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
                    {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
                </a-button>
                <a-button key="back" @click="onClose">
                    {{ $t("common.cancel") }}
                </a-button>
            </a-space>
        </template>
    </a-drawer>
</template>
<script>
import { defineComponent} from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import Upload from "../../../common/core/ui/file/Upload.vue";
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";

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
        "allAreas",
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        Upload,
        UserSelect,
        DateTimePicker,
    },
    setup(props, { emit }) {
        const { appSetting } = common();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();

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

        const onClose = () => {
            rules.value = {};
            emit("closed");
        };
        return {
            appSetting,
            loading,
            rules,
            onClose,
            onSubmit,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>
