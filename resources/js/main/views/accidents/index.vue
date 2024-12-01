<template>
    <AdminPageHeader>
        <template #header>
            <a-page-header :title="$t(`menu.accidents`)" class="p-0" />
        </template>
        <template #breadcrumb>
            <a-breadcrumb separator="-" style="font-size: 12px">
                <a-breadcrumb-item>
                    <router-link :to="{ name: 'admin.dashboard.index' }">
                        {{ $t(`menu.dashboard`) }}
                    </router-link>
                </a-breadcrumb-item>
                <a-breadcrumb-item>
                    {{ $t(`menu.accidents`) }}
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
                            permsArray.includes('accidents_create') ||
                            permsArray.includes('admin')
                        "
                    >
                        <a-button type="primary" @click="addItem">
                            <PlusOutlined />
                            {{ $t("accident.add") }}
                        </a-button>
                    </template>
                    <a-button
                        v-if="
                            table.selectedRowKeys.length > 0 &&
                            (permsArray.includes('accidents_delete') ||
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
                    <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="2">
                        <ExportButton
                            :dates="extraFilters.dates"
                        />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="10" :xl="6">
                        <a-select
                            v-model:value="filters.area_id"
                            :placeholder="
                                $t('common.select_default_text', [
                                    $t('accident.area'),
                                ])
                            "
                            style="width: 100%"
                            @change="() => setUrlData()"
                            allow-clear
                        >
                            <a-select-option
                                v-for="area in allAreas"
                                :key="area.xid"
                                :value="area.xid"
                            >
                                {{ area.name }}
                            </a-select-option>
                        </a-select>
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="8">
                        <UserSelect
                            @onChange="(id) => {
                            filters.reporting_user = id;
                            setUrlData();
                            }"
                            :value="filters.reporting_user"
                            :data="allUsers"
                            :fetchUserData="false"
                        />
                    </a-col>
                    <a-col :xs="24" :sm="24" :md="12" :lg="12" :xl="8">
                        <DateRangePicker
                            @dateTimeChanged="
                                (changedDateTime) => {
                                    extraFilters.dates = changedDateTime;
                                    setUrlData();
                                }
                            "
                        />
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
            :allAreas="allAreas"
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
                        bordered
                        size="middle"
                    >
                        <template #bodyCell="{ column, record }">
                            <template v-if="column.dataIndex === 'date_of_accident'">
                                {{ formatDateTime(record.date_of_accident) }}
                            </template>
                            <template v-if="column.dataIndex === 'injured_person'">
                                {{ record.injured_person.name }}
                            </template>
                            <template v-if="column.dataIndex === 'reporting_user'">
                                {{ record.reporting_user.name }}
                            </template>
                            <template v-if="column.dataIndex === 'area'">
                                {{ record.area.name }}
                            </template>
                            <template v-if="column.dataIndex === 'description'">
                                <p style="text-align: justify; white-space: wrap;" >
                                    <a-typography-paragraph
                                        :ellipsis="{
                                            rows: 2,
                                            expandable: true,
                                            symbol: $t('common.more'),
                                        }"
                                        :content="record.description"
                                    />
                                </p>
                            </template>
                            <template v-if="column.dataIndex === 'status'">
                                <a-tag
                                    :color="
                                        record.status === 'resolved'
                                            ? 'green'
                                            : record.status === 'in_progress'
                                            ? 'blue'
                                            : record.status === 'reported'
                                            ? 'orange'
                                            : ''
                                    "
                                >
                                    {{
                                        record.status === 'resolved'
                                            ? $t('accident.resolved')
                                            : record.status === 'in_progress'
                                            ? $t('accident.in_progress')
                                            : record.status === 'reported'
                                            ? $t('accident.reported')
                                            : ''
                                    }}
                                </a-tag>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <a-button
                                    v-if="
                                        permsArray.includes('accidents_edit') ||
                                        permsArray.includes('admin')
                                    "
                                    type="primary"
                                    @click="editItem(record)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><EditOutlined /></template>
                                </a-button>
                                <a-button
                                    v-if="
                                        (permsArray.includes('accidents_delete') ||
                                            permsArray.includes('admin')) &&
                                        (!record.children || record.children.length == 0)
                                    "
                                    type="primary"
                                    @click="showDeleteConfirm(record.xid)"
                                    style="margin-left: 4px"
                                >
                                    <template #icon><DeleteOutlined /></template>
                                </a-button>
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
import UserSelect from "../../../common/components/common/select/UserSelect.vue";
import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";
import ExportButton from "./ExportButton.vue";

export default {
    components: {
        PlusOutlined,
        EditOutlined,
        DeleteOutlined,
        AddEdit,
        AdminPageHeader,
        UserSelect,
        DateRangePicker,
        ExportButton,
    },
    setup() {
        const { permsArray, appSetting, formatDateTime } = common();
        const { url, addEditUrl, initData, columns, filterableColumns, getPrefetchData, allUsers, allAreas, hashableColumns } = fields();
        const crudVariables = crud();
        const filters = {
            area_id: undefined,
            reporting_user: undefined,
            status: undefined,
        }
        const extraFilters = {
            date: [],
        }

        onMounted(() => {
            getPrefetchData().then(() => {
                crudVariables.crudUrl.value = addEditUrl;
                crudVariables.langKey.value = "accident";

                crudVariables.hashableColumns.value = [...hashableColumns];
                
                setUrlData();
            });
        });

        const setUrlData = () => {

            crudVariables.tableUrl.value = {
                url,
                filters,
                extraFilters
            };

            crudVariables.fetch({
                page: 1,
            });

            crudVariables.initData.value = { ...initData };
            crudVariables.formData.value = { ...initData };
        }

        return {
            permsArray,
            appSetting,
            columns,
            ...crudVariables,
            filterableColumns,
            formatDateTime,
            allUsers,
            allAreas,
            filters,
            extraFilters,
            setUrlData,
        };
    },
};
</script>
