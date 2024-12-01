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
                            <template v-if="column.dataIndex === 'action'">
                                <a-space>
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
import { onMounted } from "vue";
import { PlusOutlined, EditOutlined, DeleteOutlined } from "@ant-design/icons-vue";
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
    },
    setup() {
        const { permsArray, appSetting } = common();
        const { url, addEditUrl, initData, columns } = fields();
        const crudVariables = crud();

        onMounted(() => {
            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "audit";
            crudVariables.tableUrl.value = {
                url,
            };

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
        });

        return {
            permsArray,
            appSetting,
            columns,
            ...crudVariables,
        };
    },
};
</script>