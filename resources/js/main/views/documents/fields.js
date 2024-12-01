
import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "documents?fields=id,xid,name,file,file_url,type,created_at";
    const addEditUrl = "documents";
    const { t } = useI18n();

    const initData = {
        name: "",
        file: undefined,
        type: undefined,
    }

    const hashableColumns = []

    const columns = [
        {
            title: t('document.name'),
            dataIndex: 'name',
        },
        {
            title: t('document.type'),
            dataIndex: 'type',
        },
        {
            title: t('common.action'),
            dataIndex: 'action',
        },
    ]

    const filterableColumns = [
        {
            key: "type",
            value: t('document.type'),
        }
    ];

    return {
        url,
        columns,
        initData,
        filterableColumns,
        addEditUrl,
        hashableColumns,
    }
}

export default fields;