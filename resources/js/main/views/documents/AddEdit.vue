
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
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('document.name')"
                        name="name"
                        :help="
                            rules.name
                                ? rules.name.message
                                : null
                        "
                        :validateStatus="
                            rules.name ? 'error' : null
                        "
                        class="required"
                    >
                    <a-input
                        v-model:value="formData.name"
                        :placeholder="
                            $t(
                                'common.placeholder_default_text',
                                [$t('document.name')]
                            )
                        "
                    />
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('document.type')"
                        name="type"
                        :help="
                            rules.type
                                ? rules.type.message
                                : null
                        "
                        :validateStatus="
                            rules.type ? 'error' : null
                        "
                        class="required"
                    >
                        <a-select
                            v-model:value="formData.type"
                            :placeholder="$t('document.select_type')"
                            style="width: 100%"
                        >
                            <a-select-option value="policy">{{ $t('document.type_policy') }}</a-select-option>
                            <a-select-option value="procedure">{{ $t('document.type_procedure') }}</a-select-option>
                            <a-select-option value="guideline">{{ $t('document.type_guideline') }}</a-select-option>
                            <a-select-option value="emergency">{{ $t('document.type_emergency') }}</a-select-option>
                            <a-select-option value="training">{{ $t('document.type_training') }}</a-select-option>
                            <a-select-option value="other">{{ $t('document.type_other') }}</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="16" v-if="addEditType === 'add'">
                <a-col :xs="24" :sm="24" :md="24" :lg="24">
                    <a-form-item
                        :label="$t('document.file')"
                        name="file"
                        :help="
                            rules.type
                                ? rules.file.message
                                : null
                        "
                        :validateStatus="
                            rules.file ? 'error' : null
                        "
                        class="required"
                    >
                        <UploadFile
                            :acceptFormat="'image/*,.pdf'"
                            :formData="formData"
                            folder="documents"
                            uploadField="file"
                            @onFileUploaded="
                                (file) => {
                                    formData.file = file.file;
                                    formData.file_url = file.file_url;
                                }
                            "
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-space>
                <a-button type="primary" :loading="loading" @click="onSubmit">
                    <template #icon><SaveOutlined /></template>
                    {{ addEditType == "add" ? $t("common.create") : $t("common.update") }}
                </a-button>
                <a-button @click="onClose">{{ $t("common.cancel") }}</a-button>
            </a-space>
        </template>
    </a-drawer>
</template>

<script>
import { defineComponent } from "vue";
import { SaveOutlined } from "@ant-design/icons-vue";
import common from "../../../common/composable/common";
import apiAdmin from "../../../common/composable/apiAdmin";
import UploadFile from "../../../common/core/ui/file/UploadFile.vue";

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
        SaveOutlined,
        UploadFile,
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
            drawerWidth: window.innerWidth <= 991 ? "90%" : "30%",
        };
    },
});
</script>