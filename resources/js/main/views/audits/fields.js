import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "audits?fields=id,xid,audit_name,audit_date,auditor_id,status,findings,corrective_actions";
    const addEditUrl = "audits";
    const { t } = useI18n();

    const initData = {
        audit_name: "",
        audit_date: "",
        auditor_id: "",
        status: "",
        findings: "",
        corrective_actions: ""
    };

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
            dataIndex: 'auditor_id',
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
    }
}

export default fields;