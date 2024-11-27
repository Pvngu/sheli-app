import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "areas?fields=id,xid,name,description&count=users";
    const addEditUrl = "areas";
    const { t } = useI18n();

    const initData = {
        name: "",
        description: "",
    }

    const columns = [
        {
            title: t('area.name'),
            dataIndex: 'name',
        },
        {
            title: t('area.description'),
            dataIndex: 'description',
        },
        {
            title: t('accident.action'),
            dataIndex: 'action',
        },
    ]

    return {
        url,
        columns,
        initData,
        addEditUrl,
    }
}

export default fields;