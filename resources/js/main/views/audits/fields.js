import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "audits?fields=id,xid,audit_name,audit_date,auditor_id,area_id,x_auditor_id,x_area_id,status,findings,corrective_actions,auditor{id,xid,name},area{id,xid,name}";
    const addEditUrl = "audits";
    const { t } = useI18n();
    const auditors = ref([]);
    const areas = ref([]);

    const initData = {
        audit_name: "",
        audit_date: "",
        auditor_id: "",
        area_id: "",
        status: "",
        findings: "",
        corrective_actions: ""
    };

    const getPrefetchData = () => {
        const auditorsPromise = axiosAdmin.get("all-users?role=auditor");
        const areasPromise = axiosAdmin.get("areas?fields=id,xid,name&limit=1000");

        return Promise.all([auditorsPromise, areasPromise]).then(([auditorsResponse, areasResponse]) => {
            auditors.value = auditorsResponse.data.users;
            areas.value = areasResponse.data;
        })
    }

    const hashableColumns = [
        'auditor_id',
        'area_id'
    ]

    const columns = [
        {
            title: t('audit.audit_name'),
            dataIndex: 'audit_name',
        },
        {
            title: t('audit.audit_date'),
            dataIndex: 'audit_date',
        },
        {
            title: t('audit.auditor'),
            dataIndex: 'auditor',
        },
        {
            title: t('accident.area'),
            dataIndex: 'area',
        },
        {
            title: t('audit.status'),
            dataIndex: 'status',
        },
        {
            title: t('common.action'),
            dataIndex: 'action',
            width: '150px',
        }
    ];

    return {
        url,
        columns,
        initData,
        addEditUrl,
        auditors,
        areas,
        getPrefetchData,
        hashableColumns
    }
}

export default fields;