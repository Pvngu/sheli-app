import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "accidents?fields=id,xid,x_injured_person,x_reporting_user,x_area_id,days_absent,description,status,date_of_accident,injuredPerson{name},reportingUser{name},area{name}";
    const addEditUrl = "accidents";
    const { t } = useI18n();
    const allUsers = ref([]);
    const allAreas = ref([]);

    const getPrefetchData = () => {
        const usersPromise = axiosAdmin.get("/all-users");
        const areasPromise = axiosAdmin.get('areas?fields=id,xid,name&limit=1000');

        return Promise.all([usersPromise, areasPromise]).then(([usersResponse, areasResponse]) => {
            allUsers.value = usersResponse.data.users;
            allAreas.value = areasResponse.data;
        }); 
    }

    const initData = {
        date_of_accident: "",
        injured_person: undefined,
        reporting_user: undefined,
        area_id: undefined,
        days_absent: undefined,
        description: "",
        status: undefined,
    }

    const hashableColumns = [
        "injured_person",
        "reporting_user",
        "area_id",
    ]

    const columns = [
        {
            title: t('accident.date_of_accident'),
            dataIndex: 'date_of_accident',
        },
        {
            title: t('accident.injured_person'),
            dataIndex: 'injured_person',
        },
        {
            title: t('accident.reporting_user'),
            dataIndex: 'reporting_user',
        },
        {
            title: t('accident.area'),
            dataIndex: 'area',
        },
        {
            title: t('accident.days_absent'),
            dataIndex: 'days_absent',
        },
        {
            title: t('accident.description'),
            dataIndex: 'description',
        },
        {
            title: t('accident.status'),
            dataIndex: 'status',
        },
        {
            title: t('accident.action'),
            dataIndex: 'action',
        },
    ]

    const filterableColumns = [
        {
            key: "reporting_user",
            value: t('accident.reporting_user'),
        },
        {
            key: "area",
            value: t('accident.area'),
        },
        {
            key: "status",
            value: t('accident.status'),
        },
    ];

    return {
        url,
        columns,
        initData,
        filterableColumns,
        getPrefetchData,
        allUsers,
        allAreas,
        addEditUrl,
        hashableColumns,
    }
}

export default fields;