<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.documents`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.documents`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-space>
                    <a-button type="primary" @click="addItem" v-if="permsArray.includes('documents_create') || permsArray.includes('admin')">
                        <PlusOutlined />
                        {{ $t("document.add") }}
                    </a-button>
                    <a-button v-if="table.selectedRowKeys.length > 0 && (permsArray.includes('documents_delete') || permsArray.includes('admin'))"
                        type="primary" @click="showSelectedDeleteConfirm" danger>
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button>
                </a-space>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="12">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12">
                        <a-select
                            v-model:value="filters.type"
                            :placeholder="$t('document.select_type')"
                            style="width: 100%"
                            @change="() => setUrlData()"
                            allow-clear
                        >
                            <a-select-option value="policy">{{ $t('document.type_policy') }}</a-select-option>
                            <a-select-option value="procedure">{{ $t('document.type_procedure') }}</a-select-option>
                            <a-select-option value="guideline">{{ $t('document.type_guideline') }}</a-select-option>
                            <a-select-option value="emergency">{{ $t('document.type_emergency') }}</a-select-option>
                            <a-select-option value="training">{{ $t('document.type_training') }}</a-select-option>
                            <a-select-option value="other">{{ $t('document.type_other') }}</a-select-option>
                        </a-select>
                    </a-col>
                </a-row>
            </a-col>
        </a-row>
    </admin-page-filters>

    <admin-page-table-content>
        <AddEdit
            :addEditType="addEditType"
            :visible="addEditVisible"
            :url="addEditUrl"
            @addEditSuccess="addEditSuccess"
            @closed="onCloseAddEdit"
            :formData="formData"
            :data="viewData"
            :pageTitle="pageTitle"
            :successMessage="successMessage"
        />

        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
                            getCheckboxProps: (record) => ({
                                disabled: false,
                                name: record.xid,
                            }),
                        }"
                        :columns="columns"
                        :row-key="(record) => record.xid"
                        :data-source="table.data"
                        :pagination="table.pagination"
                        :loading="table.loading"
                        @change="handleTableChange"
                        size="middle"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'type'">
                                <a-tag color="blue" v-if="record.type === 'policy'">
                                    <FileTextOutlined />
                                    {{ $t('document.type_policy') }}
                                </a-tag>
                                <a-tag color="green" v-else-if="record.type === 'procedure'">
                                    <FileDoneOutlined />
                                    {{ $t('document.type_procedure') }}
                                </a-tag>
                                <a-tag color="purple" v-else-if="record.type === 'guideline'">
                                    <FileSearchOutlined />
                                    {{ $t('document.type_guideline') }}
                                </a-tag>
                                <a-tag color="red" v-else-if="record.type === 'emergency'">
                                    <AlertOutlined />
                                    {{ $t('document.type_emergency') }}
                                </a-tag>
                                <a-tag color="cyan" v-else-if="record.type === 'training'">
                                    <ReadOutlined />
                                    {{ $t('document.type_training') }}
                                </a-tag>
                                <a-tag color="orange" v-else>
                                    <FileUnknownOutlined />
                                    {{ $t('document.type_other') }}
                                </a-tag>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-space>
                                    <a-button
                                        :href="'/uploads/documents/' + record.file" target="_blank"
                                        type="primary"
                                        @click="viewItem(record)"
                                        style="margin-left: 4px"
                                    >
                                        <template #icon><EyeOutlined /></template>
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        @click="editItem(record)"
                                        v-if="permsArray.includes('documents_edit') || permsArray.includes('admin')"
                                    >
                                        <template #icon><EditOutlined /></template>
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        @click="showDeleteConfirm(record.xid, record.file)"
                                        danger
                                        v-if="permsArray.includes('documents_delete') || permsArray.includes('admin')"
                                    >
                                        <template #icon><DeleteOutlined /></template>
                                    </a-button>
                                </a-space>
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
</template>

<script>
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined, EyeOutlined, FileTextOutlined, FileDoneOutlined, FileUnknownOutlined, FileSearchOutlined, AlertOutlined, ReadOutlined  } from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
        EyeOutlined,
        FileTextOutlined,
        FileDoneOutlined,
        FileUnknownOutlined,
        FileSearchOutlined,
        AlertOutlined,
        ReadOutlined,
    },
    setup() {
        const { permsArray, appSetting } = common();
        const { url, addEditUrl, initData, columns } = fields();
        const crudVariables = crud();
        const filters = {
            type: undefined,
        };

        onMounted(() => {
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "document";

            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };

            setUrlData();
        });

        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url,
                filters,
            };

            crudVariables.fetch({
                page: 1,
            });
        }

        return {
            permsArray,
            appSetting,
            columns,
            ...crudVariables,
            filters,
            setUrlData
        };
    },
};
</script>