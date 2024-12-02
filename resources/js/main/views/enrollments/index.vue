<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.enrollments`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.enrollments`) }}
                </a-breadcrumb-item>
            </a-breadcrumb>
        </template>
    </AdminPageHeader>

    <admin-page-filters>
        <a-row :gutter="[16, 16]">
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-space>
                    <template
                        v-if="
                            permsArray.includes('enrollments_create') ||
                            permsArray.includes('admin')
                        "
                    >
                        <a-button type="primary" @click="addItem">
                            <PlusOutlined />
                            {{ $t("training.add") }}
                        </a-button>
                    </template>
                    <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('enrollments_delete') ||
                                permsArray.includes('admin'))
                        "
                        type="primary"
                        @click="showSelectedDeleteConfirm"
                        danger
                    >
                        <template #icon><DeleteOutlined /></template>
                        {{ $t("common.delete") }}
                    </a-button>
                </a-space>
            </a-col>
            <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="10">
                <a-select
                    v-model="filters.status"
                    @change="setUrlData"
                    placeholder="Select status"
                    style="width: 200px"
                >
                    <a-select-option value="completed">{{ $t('common.completed') }}</a-select-option>
                    <a-select-option value="finished">{{ $t('common.finished') }}</a-select-option>
                </a-select>
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
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-tabs
                    v-model:activeKey="extraFilters.status"
                    @change="onTabChange"
                >
                    <a-tab-pane key="active" :tab="$t('common.active')" />
                    <a-tab-pane key="finished" :tab="$t('common.finished')" />
                </a-tabs>
            </a-col>
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
                        bordered
                        size="middle"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'user'">
                                {{ record.user.name }}
                            </template>
                            <template v-if="column.dataIndex === 'course'">
                                {{ record.course.course_name }}
                            </template>
                            <template
                                v-if="column.dataIndex === 'enrollment_date'"
                            >
                                {{ formatDate(record.enrollment_date) }}
                            </template>
                            <template v-if="column.dataIndex === 'status'">
                                <a-tag :color="getStatusColor(record.status)">
                                    {{ record.status }}
                                </a-tag>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('enrollments_edit') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <!-- <a-button
                                    v-if="
                                        (permsArray.includes('enrollments_delete') ||
                                            permsArray.includes('admin')) &&
                                        (!record.children || record.children.length == 0)
                                    "
                                    type="primary"
                                    @click="showDeleteConfirm(record.xid)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><DeleteOutlined /></template>
                                </a-button> -->
                            </template>
                        </template>
                    </a-table>
                </div>
            </a-col>
        </a-row>
    </admin-page-table-content>
</template>

<script>
import { onMounted, reactive } from "vue";
import {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";
import { Select } from "ant-design-vue";
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
        Select,
    },
    setup() {
        const { permsArray, appSetting, formatDate } = common();
        const { url, addEditUrl, initData, columns, hashableColumns } = fields();
        const crudVariables = crud();
        const extraFilters = reactive({
            status: "active",
        });
        const filters = {
            status: undefined,
        };

        onMounted(() => {
            setUrlData();

            crudVariables.crudUrl.value = addEditUrl;
            crudVariables.langKey.value = "enrollment";
            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
            crudVariables.hashableColumns.value = [...hashableColumns];
        });

        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url,
                filters,
                extraFilters,
            };

            crudVariables.fetch({
                page: 1,
            });
        };

        const getStatusColor = (status) => {
            if (status === "completed") {
                return "green";
            } else if (status === "pending") {
                return "orange";
            } else {
                return "red";
            }
        };

        const onTabChange = (key) => {
            extraFilters.status = key;
            setUrlData();
        };

        return {
            permsArray,
            appSetting,
            columns,
            ...crudVariables,
            formatDate,
            getStatusColor,
            filters,
            setUrlData,
            extraFilters,
            onTabChange,
        };
    },
};
</script>
