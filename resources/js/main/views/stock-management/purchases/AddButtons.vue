<template>
	<SubscriptionModuleVisibility moduleName="product">
		<div v-if="permsArray.includes('products_create') || permsArray.includes('admin')">
			<template v-if="customType == 'menu'">
				<a-menu-item @click="showAdd" key="products">
					<template #icon>
						<slot name="icon"></slot>
					</template>
					<slot></slot>
				</a-menu-item>
			</template>
			<template v-else>
				<a-tooltip v-if="tooltip" placement="topLeft" :title="$t('product.ledger_settings')" arrow-point-at-center>
					<a-button :size="size" @click="showAdd" class="ml-5 no-border-radius" :type="btnType" style="border:none;">
						<template #icon>
							<SettingOutlined />
						</template>
						<slot></slot>
					</a-button>
				</a-tooltip>
				<a-button v-else :size="size" @click="showAdd" class="ml-5 no-border-radius" :type="btnType" style="border:none;">

					<template #icon>
						<SettingOutlined />
					</template>

					<slot></slot>
				</a-button>
			</template>

			<LedgerSetting :addEditType="addEditType" :visible="visible" :url="addEditUrl"
				@addEditSuccess="addEditSuccess" @closed="onClose" :formData="formData" :data="formData"
				:successMessage="$t('product.created')" />
		</div>
	</SubscriptionModuleVisibility>
</template>
<script>
import { defineComponent, ref } from "vue";
import { PlusOutlined, SettingOutlined } from "@ant-design/icons-vue";
import fields from "../../../../main/views/product-manager/products/fields"
import AddEdit from "./AddEdit.vue";
import LedgerSetting from "./LedgerSetting.vue"
import common from "../../../../common/composable/common";
import SubscriptionModuleVisibility from "../../../../common/components/common/visibility/SubscriptionModuleVisibility.vue";

export default defineComponent({
	props: {
		size: {
			default: "middle",
		},
		btnType: {
			default: "default",
		},
		tooltip: {
			default: true,
		},
		customType: {
			default: "button",
		},
	},
	emits: ["onAddSuccess"],
	components: {
		PlusOutlined,
		SettingOutlined,
		AddEdit,
		LedgerSetting,
		SubscriptionModuleVisibility,
	},
	setup(props, { emit }) {
		const { permsArray } = common();
		const { initData, addEditUrl } = fields();
		const visible = ref(false);
		const addEditType = ref("add");
		const formData = ref({ ...initData });

		const showAdd = () => {
			visible.value = true;
		};

		const addEditSuccess = () => {
			visible.value = false;
			formData.value = { ...initData };
			emit("onAddSuccess");
		};

		const onClose = () => {
			visible.value = false;
		};

		return {
			permsArray,
			visible,
			addEditType,
			addEditUrl,
			formData,
			addEditSuccess,
			onClose,
			showAdd,
		};
	},
});
</script>
