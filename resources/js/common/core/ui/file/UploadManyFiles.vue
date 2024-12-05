<template>
	<!-- {{ fileList }} -->
	<a-upload
		accept="image/*"
		v-model:file-list="fileList"
		name="image"
		list-type="picture-card"
		:customRequest="customRequest"
		@remove="onRemove"
	>
		<div v-if="fileList.length < 8">
			<plus-outlined />
			<div style="margin-top: 8px">Upload</div>
		</div>
	</a-upload>
</template>
<script>
import { PlusOutlined, LoadingOutlined } from "@ant-design/icons-vue";
import { message } from "ant-design-vue";
import { defineComponent, ref, watch, onMounted } from "vue";
import { useI18n } from "vue-i18n";

export default defineComponent({
	props: {
		formData: null,
		folder: String,
		imageField: {
			default: "image",
			type: String,
		},
		url: {
			default: "upload-file",
			type: String,
		},
		fileL: {
			default: [],
			type: Array,
		}
	},
	components: {
		LoadingOutlined,
		PlusOutlined,
	},

	setup(props, { emit }) {
		const fileList = ref([]);
		const loading = ref(false);
		const { t } = useI18n();

		const customRequest = (info) => {
			const formData = new FormData();
			formData.append("image", info.file);
			formData.append("folder", props.folder);

			// loading.value = true;

			axiosAdmin
				.post(props.url, formData, {
					headers: {
						"Content-Type": "multipart/form-data",
					},
				})
				.then((response) => {
					// fileList.value = [];
					// loading.value = false;
					emit("onFileUploaded", {
						file: response.data.file,
						url: response.data.file_url,
						uid: info.file.uid,
						status: "done",
					});
				})
				.catch(() => {
					// loading.value = false;
					message.error(t("messages.uploading_failed"));
				});
		};

		const onRemove = (item) => {
			fileList.value = fileList.value.filter((file) => file.uid !== item.uid);
			axiosAdmin.delete(`audit-images/${item.xid}`);
			emit("onFileRemoved", item);
		}

		onMounted(() => {
			fileList.value = props.fileL;
		});

		watch(() => props.fileL, (value) => {
			fileList.value = value;
		});

		return {
			fileList,
			loading,
			customRequest,
			onRemove
		};
	},
});
</script>
<style>
.avatar-uploader > .ant-upload {
	width: 128px;
	height: 128px;
}
.ant-upload-select-picture-card i {
	font-size: 32px;
	color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
	margin-top: 8px;
	color: #666;
}
</style>
