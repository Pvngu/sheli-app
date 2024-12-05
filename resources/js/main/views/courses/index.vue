<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.courses`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.courses`) }}
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
                            permsArray.includes('courses_create') ||
                            permsArray.includes('admin')
                        "
                    >
                        <a-button type="primary" @click="addItem">
                            <PlusOutlined />
                            {{ $t("course.add") }}
                        </a-button>
                    </template>
                    <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('courses_delete') ||
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
            <a-col :xs="24" :sm="24" :md="12" :lg="14" :xl="14">
                <a-row :gutter="[16, 16]" justify="end">
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                        <UserSelect
                            @onChange="
                                (id) => {
                                    filters.trainer_id = id;
                                    setUrlData();
                                }
                            "
                            :value="filters.trainer_id"
                            :data="allUsers"
                            :fetchUserData="false"
                        />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="12">
                        <a-select
                            v-model:value="filters.status"
                            :placeholder="$t('common.select_default_text', [$t('common.status')])"
                            style="width: 100%"
                            @change="() => setUrlData()"
                            allow-clear
                        >
                            <a-select-option value="active">{{ $t('course.active') }}</a-select-option>
                            <a-select-option value="inactive">{{ $t('course.inactive') }}</a-select-option>
                            <a-select-option value="finished">{{ $t('course.finished') }}</a-select-option>
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
            :allUsers="allUsers"
        />
        <a-row>
            <a-col :span="24">
                <div class="table-responsive">
                    <a-table
                        :row-selection="{
                            selectedRowKeys: table.selectedRowKeys,
                            onChange: onRowSelectChange,
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
                            <template v-if="column.dataIndex === 'trainer'">
                                {{ record.trainer.name }}
                            </template>
                            <template v-if="column.dataIndex === 'description'">
                                <p style="text-align: justify; white-space: wrap;" >
                                    {{ record.description }}
                                </p>
                            </template>
                            <template
                                v-if="
                                    column.dataIndex === 'start_date' ||
                                    column.dataIndex === 'end_date'
                                "
                            >
                                {{ formatDate(record[column.dataIndex]) }}
                            </template>
                            <template v-if="column.dataIndex === 'enrolled_members'">
                                <CourseMembers :members="record.enrollments" />
                            </template>
                            <template v-if="column.dataIndex === 'status'">
                                <a-tag :color="getStatusColor(record.status)">
                                    {{ record.status }}
                                </a-tag>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-space>
                                    <a-button
                                        v-if="
                                            permsArray.includes(
                                                'courses_edit'
                                            ) || permsArray.includes('admin')
                                        "
                                        type="primary"
                                        @click="editItem(record)"
                                    >
                                        <template #icon
                                            ><EditOutlined
                                        /></template>
                                    </a-button>
                                    <a-button
                                        v-if="
                                            permsArray.includes(
                                                'courses_delete'
                                            ) || permsArray.includes('admin')
                                        "
                                        type="primary"
                                        @click="showDeleteConfirm(record.xid)"
                                        danger
                                    >
                                        <template #icon
                                            ><DeleteOutlined
                                        /></template>
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
import {
    PlusOutlined,
    EditOutlined,
    DeleteOutlined,
} from "@ant-design/icons-vue";
import crud from "../../../common/composable/crud";
import common from "../../../common/composable/common";
import AdminPageHeader from "../../../common/layouts/AdminPageHeader.vue";
import fields from "./fields";
import AddEdit from "./AddEdit.vue";
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import CourseMembers from "./CourseMembers.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
        UserSelect,
        CourseMembers,
    },
    setup() {
        const { permsArray, appSetting, formatDate } = common();
        const {
            url,
            addEditUrl,
            initData,
            columns,
            hashableColumns,
            getPrefetchData,
            allUsers,
        } = fields();
        const crudVariables = crud();
        const filters = {
            trainer_id: undefined,
            status: undefined,
        };

        onMounted(() => {
            getPrefetchData().then(() => {
                crudVariables.crudUrl.value = addEditUrl;
                crudVariables.langKey.value = "course";
                crudVariables.tableUrl.value = { url, filters };

                crudVariables.hashableColumns.value = [...hashableColumns];

                setUrlData();
            });
        });

        const setUrlData = () => {
            crudVariables.tableUrl.value = {
                url,
                filters,
            };

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
        };

        const getStatusColor = (status) => {
            switch (status) {
                case 'active':
                    return 'green';
                case 'inactive':
                    return 'default';
                case 'finished':
                    return 'blue';
                default:
                    return 'default';
            }
        };

        return {
            permsArray,
            appSetting,
            columns,
            formatDate,
            ...crudVariables,
            allUsers,
            filters,
            setUrlData,
            getStatusColor,
        };
    },
};
</script>
