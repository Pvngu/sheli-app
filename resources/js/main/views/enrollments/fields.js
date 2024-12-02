import { useI18n } from "vue-i18n";

const fields = () => {
    const url = "enrollments?fields=id,xid,user_id,course_id,x_user_id,x_course_id,enrollment_date,status,completion_date,user{id,xid,name},course{id,xid,course_name}";
    const addEditUrl = "enrollments";
    const { t } = useI18n();

    const initData = {
        user_id: "",
        course_id: "",
        enrollment_date: "",
        completion_date: "",
        status: "",
    }

    const extraFilters = {
        'status': 'active'
    }

    const hashableColumns = [
        "user_id",
        "course_id",
    ]

    const columns = [
        {
            title: t('training.course'),
            dataIndex: 'course',
        },
        {
            title: t('training.enrolled_member'),
            dataIndex: 'user',
        },
        {
            title: t('training.enrollment_date'),
            dataIndex: 'enrollment_date',
        },
        {
            title: t('training.status'),
            dataIndex: 'status',
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
        extraFilters,
        hashableColumns,
    }
}

export default fields;
