<template>
    <a-button
        v-if="
            (permsArray.includes('reports_view') ||
                permsArray.includes('admin'))
        "
        type="primary"
        @click = "visible = true"
    >
        <template #icon>
            <CloudDownloadOutlined />
        </template>
    </a-button>
    <a-modal
        v-model:open = "visible"
        :title="$t('accident.export_accidents')"
        centered
        @close="handleClose"
    >
        <a-form layout="vertical">
            <a-row :gutter="16">
                <a-col :span="24">
                    <a-form-item
                        :label="$t('common.export_as')"
                        name="export_type"
                        :help="
                            rules.export_type
                                ? rules.export_type.message
                                : null
                        "
                        :validateStatus="
                            rules.export_type ? 'error' : null
                        "
                        class="required"
                    >
                        <a-select
                            v-model:value="exportData.export_type"
                            :placeholder="$t('common.select_default_text', [''])"
                        >
                            <a-select-option value="xlsx">
                                XLSX
                            </a-select-option>
                            <a-select-option value="csv">
                                CSV
                            </a-select-option>
                            <a-select-option value="pdf">
                                PDF
                            </a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="24">
                    <a-form-item
                        :label="$t('common.columns')"
                        name="columns"
                        :help="rules.columns ? rules.columns.message : null"
                        :validateStatus="rules.columns ? 'error' : null"
                        class="required"
                    >
                        <a-select
                            v-model:value="exportData.columns"
                            mode="multiple"
                            style="width: 100%"
                            :placeholder="$t('common.select_default_text', [$t('common.columns')])"
                            :options="columnsOptions"
                        />
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
        <template #footer>
            <a-button key="back" @click="handleClose">
                {{ $t("common.close") }}
            </a-button>
            <a-button 
                @click="exportUsers(exportData)"
                type="primary"
                :disabled="!exportData.columns.length || !exportData.export_type"
            >
                {{ $t("common.export") }}
            </a-button>
        </template>
    </a-modal>
</template>

<script>
import { defineComponent, ref } from "vue";
import { CloudDownloadOutlined } from "@ant-design/icons-vue";
import { useI18n } from "vue-i18n";
import apiAdmin from "../../../common/composable/apiAdmin";
import common from "../../../common/composable/common";
import DateRangePicker from "../../../common/components/common/calendar/DateRangePicker.vue";

export default defineComponent({
    props: {
        dates: {
            default: [],
        },
    },
    components: {
        CloudDownloadOutlined,
        DateRangePicker
    },
    setup(props) {
        const { rules } = apiAdmin();
        const { permsArray, exportItems } = common();
        const exportData = ref({
            export_type: "",
            columns: [],
        });
        const { t } = useI18n();
        const visible = ref(false);

        const exportUsers = () => {
            axiosAdmin.get('/accidents/export', {
                responseType: 'arraybuffer',
                params: {
                    columns: exportData.value.columns,
                    dates: props.dates,
                    format: exportData.value.export_type,
                    selectedRowKeys: []
                }
            }).then((res) => {
                exportItems(res, exportData.value.export_type, 'accidents', props.dates);
                visible.value = false;
                resetExportData();
            });
        };

        const handleClose = () => {
            visible.value = false;
            resetExportData();
        }

        const resetExportData = () => {
            exportData.value = {
                export_type: "",
                columns: [],
            };
        }

        const columnsOptions = ref([
            {
            label: t('accident.date_of_accident'),
            value: 'date_of_accident',
            },
            {
            label: t('accident.injured_person'),
            value: 'injured_person',
            },
            {
            label: t('accident.reporting_user'),
            value: 'reporting_user',
            },
            {
            label: t('accident.area'),
            value: 'area',
            },
            {
            label: t('accident.days_absent'),
            value: 'days_absent',
            },
            {
            label: t('accident.description'),
            value: 'description',
            width: '40%',
            },
            {
            label: t('accident.status'),
            value: 'status',
            },
        ]);

        return {
            exportData,
            columnsOptions,
            rules,
            exportUsers,
            permsArray,
            exportItems,
            visible,
            handleClose,
        };
    }
});
</script>