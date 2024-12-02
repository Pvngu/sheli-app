<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.audits`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.audits`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-space>
                    <template v-if="permsArray.includes('admin')">
                        <a-button type="primary" @click="addItem">
                            <template #icon><PlusOutlined /></template>
                            {{ $t("audit.add") }}
                        </a-button>
                    </template>
                    <a-button
                        v-if="table.selectedRowKeys.length > 0"
                        type="primary"
                        @click="showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button>
                </a-space>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="12" :lg="6" :xl="6">
                    <a-select
                        v-model:value="filters.status"
                        placeholder="Select Status"
                        style="width: 100%"
                        @change="setUrlData"
                        allow-clear
                    >
                        <a-select-option value="pending">Pending</a-select-option>
                        <a-select-option value="completed">Completed</a-select-option>
                        <a-select-option value="in-progress">In Progress</a-select-option>
                    </a-select>
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                    <UserSelect
                        @onChange="(id) => {
                        filters.auditor_id = id;
                        setUrlData();
                        }"
                        :value="filters.auditor_id"
                        :data="auditors"
                        :fetchUserData="false"
                    />
                </a-col>
                <a-col :xs="24" :sm="24" :md="12" :lg="8" :xl="8">
                    <a-select
                        v-model:value="filters.area_id"
                        placeholder="Select Area"
                        style="width: 100%"
                        @change="setUrlData"
                        allow-clear
                    >
                        <a-select-option v-for="area in areas" :key="area.xid" :value="area.xid">
                            {{ area.name }}
                        </a-select-option>
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
            :auditors="auditors"
            :areas="areas"
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
                            <template v-if="column.dataIndex === 'status'">
                                <a-tag v-if="record.status === 'pending'" color="orange">{{ $t('common.pending') }}</a-tag>
                                <a-tag v-if="record.status === 'completed'" color="green">{{ $t('common.completed') }}</a-tag>
                                <a-tag v-if="record.status === 'in-progress'" color="blue">{{ $t('common.in_progress') }}</a-tag>
                            </template>
                            <template v-if="column.dataIndex === 'auditor'">
                                {{ record.auditor.name }}
                            </template>
                            <template v-if="column.dataIndex === 'area'">
                                {{ record.area.name }}
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-space>
                                    <a-button
                                        type="primary"
                                        @click="downloadPdf(record)"
                                        :disabled="record.status !== 'completed'"
                                    >
                                        <template #icon><DownloadOutlined /></template>
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        @click="editItem(record)"
                                        v-if="permsArray.includes('admin')"
                                    >
                                        <template #icon><EditOutlined /></template>
                                    </a-button>
                                    <a-button
                                        type="primary"
                                        @click="deleteItem(record)"
                                        danger
                                        v-if="permsArray.includes('admin')"
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
import { ref, onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined, DownloadOutlined } from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        DownloadOutlined,
        AddEdit,
        AdminPageHeader,
        UserSelect,
    },
    setup() {
        const { permsArray, appSetting } = common();
        const { url, addEditUrl, initData, columns, hashableColumns, auditors, areas, getPrefetchData } = fields();
        const crudVariables = crud();
        const filters = ref({
            status: undefined,
            auditor_id: undefined,
            area_id: undefined,
        });

        onMounted(() => {
            getPrefetchData().then(() => {
                crudVariables.crudUrl.value = addEditUrl;
                crudVariables.langKey.value = "audit";
                crudVariables.hashableColumns.value = hashableColumns;

                setUrlData();
            });
        });
        
        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url,
                filters
            };

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
        }

        const downloadPdf = (record) => {
    axiosAdmin.get(`audits/generated-pdf/${record.xid}`, {
        responseType: 'blob', // Important
    }).then((response) => {
        const blob = new Blob([response], { type: 'application/pdf' });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        const fileName = `audit-${record.xid}.pdf`;
        link.download = fileName;
        link.click();
    }).catch((error) => {
        console.error('Error downloading PDF:', error);
        alert('Failed to download PDF.');
    });
};

        return {
            permsArray,
            appSetting,
            columns,
            ...crudVariables,
            filters,
            setUrlData,
            auditors,
            areas,
            downloadPdf,
        };
    },
};
</script>