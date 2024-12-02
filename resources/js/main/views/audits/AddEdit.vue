<template>
    <a-drawer :title="pageTitle" :width="drawerWidth" :open="visible" :body-style="{ paddingBottom: '80px' }"
        :footer-style="{ textAlign: 'right' }" :maskClosable="false" @close="onClose">
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item label="Audit Name" name="audit_name"
                        :help="rules.audit_name ? rules.audit_name.message : null"
                        :validateStatus="rules.audit_name ? 'error' : null" class="required">
                        <a-input v-model:value="formData.audit_name" placeholder="Enter Audit Name" />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item label="Audit Date" name="audit_date"
                        :help="rules.audit_date ? rules.audit_date.message : null"
                        :validateStatus="rules.audit_date ? 'error' : null" class="required">
                        <DateTimePicker :dateTime="formData.audit_date" :showTime="false" :onlyDate="true"
                            @dateTimeChanged="(changedDateTime) =>
                                (formData.audit_date = changedDateTime)
                                " />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item label="Auditor" name="auditor_id"
                        :help="rules.auditor_id ? rules.auditor_id.message : null"
                        :validateStatus="rules.auditor_id ? 'error' : null" class="required">
                        <UserSelect
                            @onChange="(id) => {
                            formData.auditor_id = id;
                            }"
                            :value="formData.auditor_id"
                            :data="auditors"
                            :fetchUserData="false"
                        />
                    </a-form-item>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item label="Status" name="status" :help="rules.status ? rules.status.message : null"
                        :validateStatus="rules.status ? 'error' : null" class="required">
                        <a-select v-model:value="formData.status" placeholder="Select Status">
                            <a-select-option value="pending">Pending</a-select-option>
                            <a-select-option value="completed">Completed</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="12" :lg="12">
                    <a-form-item label="Area" name="area_id"
                        :help="rules.area_id ? rules.area_id.message : null"
                        :validateStatus="rules.area_id ? 'error' : null" class="required">
                        <a-select v-model:value="formData.area_id" placeholder="Select Area">
                            <a-select-option v-for="area in areas" :key="area.xid" :value="area.xid">
                                {{ area.name }}
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="[16, 16]">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item label="Findings" name="findings" :help="rules.findings ? rules.findings.message : null"
                        :validateStatus="rules.findings ? 'error' : null">
                        <a-textarea v-model:value="formData.findings" :rows="4" placeholder="Enter Findings" />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="[16, 16]">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item label="Corrective Actions" name="corrective_actions"
                        :help="rules.corrective_actions ? rules.corrective_actions.message : null"
                        :validateStatus="rules.corrective_actions ? 'error' : null">
                        <a-textarea v-model:value="formData.corrective_actions" :rows="4"
                            placeholder="Enter Corrective Actions" />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gitter="[16, 16]">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <div class="clearfix">
                        <a-upload v-model:file-list="fileList"
                            list-type="picture-card" @preview="handlePreview">
                            <div v-if="fileList.length < 8">
                                <plus-outlined />
                                <div style="margin-top: 8px">Upload</div>
                            </div>
                        </a-upload>
                        <a-modal :open="previewVisible" :title="previewTitle" :footer="null" @cancel="handleCancel">
                            <img alt="example" style="width: 100%" :src="previewImage" />
                        </a-modal>
                    </div>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-space>
                <a-button key="submit" type="primary" :loading="loading" @click="onSubmit">
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
import { defineComponent, ref } from "vue";
import { PlusOutlined, LoadingOutlined, SaveOutlined } from "@ant-design/icons-vue";
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";
import Upload from "../../../common/core/ui/file/Upload.vue";
import DateTimePicker from "../../../common/components/common/calendar/DateTimePicker.vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";

export default defineComponent({
    props: [
        "formData",
        "data",
        "visible",
        "url",
        "addEditType",
        "pageTitle",
        "successMessage",
        "auditors",
        "areas"
    ],
    components: {
        PlusOutlined,
        LoadingOutlined,
        SaveOutlined,
        DateTimePicker,
        UserSelect,
    },
    setup(props, { emit }) {
        const { appSetting } = common();
        const { addEditRequestAdmin, loading, rules } = apiAdmin();
        const fileList = ref([]);
        const previewVisible = ref(false);
        const previewImage = ref('');
        const previewTitle = ref('');

        function getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
            });
        }

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

        const handleCancel = () => {
            previewVisible.value = false;
            previewTitle.value = '';
        };

        const handlePreview = async file => {
            if (!file.url && !file.preview) {
                file.preview = await getBase64(file.originFileObj);
            }
            previewImage.value = file.url || file.preview;
            previewVisible.value = true;
            previewTitle.value = file.name || file.url.substring(file.url.lastIndexOf('/') + 1);
        };

        return {
            appSetting,
            loading,
            rules,
            onClose,
            onSubmit,
            fileList,
            previewVisible,
            previewImage,
            previewTitle,
            handleCancel,
            handlePreview,

            drawerWidth: window.innerWidth <= 991 ? "90%" : "45%",
        };
    },
});
</script>

<style scoped>
.ant-upload-select-picture-card i {
    font-size: 32px;
    color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
    margin-top: 8px;
    color: #666;
}
</style>