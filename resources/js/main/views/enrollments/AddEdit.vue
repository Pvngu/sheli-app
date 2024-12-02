<template>
    <a-modal
        :title="pageTitle"
        :visible="visible"
        width="500px"
        @cancel="onClose"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        label="Completion Date"
                        name="completion_date"
                        :help="rules.completion_date ? rules.completion_date.message : null"
                        :validateStatus="rules.completion_date ? 'error' : null"
                    >
                        <DateTimePicker
                            :dateTime="formData.completion_date"
                            :isFutureDateDisabled="false"
                            :showTime="false"
                            :onlyDate="true"
                            @dateTimeChanged="
                                (changedDateTime) =>
                                    (formData.completion_date = changedDateTime)
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="[16, 16]">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
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
                            <a-select-option value="completed">{{ $t("common.completed") }}</a-select-option>
                            <a-select-option value="pending">{{ $t("common.pending") }}</a-select-option>
                        </a-select>
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
    </a-modal>
</template>
<script>
import { defineComponent } from "vue";
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
