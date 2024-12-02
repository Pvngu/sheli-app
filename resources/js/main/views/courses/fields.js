import { useI18n } from "vue-i18n";
import { ref } from "vue";

const fields = () => {
    const url = "courses?fields=id,xid,course_name,description,trainer_id,x_trainer_id,trainer{id,xid,name},start_date,end_date,status,enrollments{id,xid,user_id,x_user_id},enrollments:user{id,xid,name,profile_image,profile_image_url}";
    const addEditUrl = "courses";
    const { t } = useI18n();
    const allUsers = ref([]);

    const initData = {
        course_name: "",
        description: "",
        trainer_id: undefined,
        start_date: "",
        end_date: "",
        user_id: undefined,
        status: "",
    }

    const hashableColumns = [
        "trainer_id",
    ]

    const getPrefetchData = () => {
        return axiosAdmin.get("/all-users?role=trainer").then((response) => {
            allUsers.value = response.data.users;
        });
    };

    const columns = [
        {
            title: t('course.name'),
            dataIndex: 'course_name',
        },
        {
            title: t('course.description'),
            dataIndex: 'description',
        },
        {
            title: t('course.trainer'),
            dataIndex: 'trainer',
        },
        {
            title: t('course.enrolled_members'),
            dataIndex: 'enrolled_members',
        },
        {
            title: t('course.start_date'),
            dataIndex: 'start_date',
        },
        {
            title: t('course.end_date'),
            dataIndex: 'end_date',
        },
        {
            title: t('course.status'),
            dataIndex: 'status',
        },
        {
            title: t('common.action'),
            dataIndex: 'action',
        },
    ]

    return {
        url,
        columns,
        initData,
        addEditUrl,
        hashableColumns,
        getPrefetchData,
        allUsers,
    }
}

export default fields;