import './common/plugins';
import '../less/custom.less';
import '../less/pos_invoice.css';
import 'ant-design-vue/dist/reset.css';
import { createApp } from 'vue';
import Antd from 'ant-design-vue';
import PerfectScrollbar from 'vue3-perfect-scrollbar';
import App from './main/views/App.vue';
import routes from './main/router';
import store from './main/store';
import { setupI18n, loadLocaleMessages } from './common/i18n';

import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css';
import print from 'vue3-print-nb';

import AdminPageFilter from "./common/layouts/AdminPageFilters.vue";
import AdminPageTableContent from "./common/layouts/AdminPageTableContent.vue";

async function bootstrap() {
    if (store.getters["auth/isLoggedIn"]) {
        store.dispatch("auth/updateUser");
    }

    store.dispatch("auth/updateGlobalSetting");
    store.dispatch("auth/updateApp");
    store.dispatch("auth/updateAllLangs");
    store.dispatch("auth/updateAllWarehouses");
    store.commit("auth/updateActiveModules", window.config.modules);
    store.dispatch("auth/updateVisibleSubscriptionModules");

    const app = createApp(App);  //&&    console.warn('[Vue warn]: '.concat(msg).concat(trace)
    app.config.warnHandler = (msg, instance, trace) =>
        ![
          'built-in or reserved HTML elements as component id: component',
          '"class" is a reserved attribute and cannot be used as component prop',
          'Cannot find element: #__nuxt'
        ].some((warning) => msg.includes(warning))
    const i18n = setupI18n({ legacy: false, globalInjection: true, locale: store.state.auth.lang, warnHtmlMessage: false });
    await loadLocaleMessages(i18n, store.state.auth.lang);

    app.use(i18n);
    app.use(Antd);
    app.use(PerfectScrollbar);
    app.use(store);
    app.use(print);
    app.use(routes);

    // Global Components
    app.component('admin-page-filters', AdminPageFilter);
    app.component('admin-page-table-content', AdminPageTableContent);

    window.i18n = i18n;

    routes.isReady().then(() => {
        if (routes.currentRoute && routes.currentRoute.value && routes.currentRoute.value.meta && routes.currentRoute.value.meta.isFrontStore) {
            store.commit("front/updateWarehouseSlug", routes.currentRoute.value.params.warehouse);
            store.commit("front/updateWarehouseCurrency", window.config.warehouseCurrency);
            store.commit("front/updateApp", window.config.frontStoreSettings);
        }
        app.mount("#app");
    });

    // Set up keypress event listener
    const handleKeyPress = (event) => {
    const router = app.config.globalProperties.$router; // Access Vue Router instance
    let targetURL = ''; // Initialize targetURL
    const baseURL = window.config?.baseUrl || 'defaultBaseURL';
    //const key = event.key; // Convert key to uppercase for case-insensitive comparison

    if (event.altKey) {
        switch (event.key.toUpperCase()) {
            case 'N':
                if (router) {
                    router.push({ name: 'admin.settings.profile.index' });
                } else {
                    console.error('Vue router is not available.');
                }
                break;
            case 'M':
                if (router) {
                    router.push({ name: 'admin.brands.index' });
                } else {
                    console.error('Vue router is not available.');
                }
                break;
            case 'P':
                if (router) {
                    router.push({ name: 'admin.customers.index' });
                } else {
                    console.error('Vue router is not available.');
                }
                break;
            case 'I':
                if (router) {
                    router.push({ name: 'admin.suppliers.index' });
                } else {
                    console.error('Vue router is not available.');
                }
                break;
            case 'H':
                if (router) {
                    router.push({ name: 'admin.categories.index' });
                } else {
                    console.error('Vue router is not available.');
                }
                break;
            case 'B':
                if (router) {
                    router.push({ name: 'admin.variations.index' });
                } else {
                    console.error('Vue router is not available.');
                }
                break;
            default:
                targetURL = '';
        }
    } else if (event.ctrlKey && event.key === 'F2') {
        targetURL = `${baseURL}/admin/payment/in`;
    } 
    // else if (event.key === 'Backspace') {
    //     if (router) {
    //         router.go(-1); // Navigate to the previous page in history
    //     } else {
    //         console.error('Vue router is not available.');
    //     }
    // }

    if (targetURL) {
        redirect(targetURL);
    }
};

    
    const redirect = (targetURL) => {
        if (targetURL) {
            window.location.href = targetURL;
        }
    };

    const openNewModal = () => {
        // Logic to open a new modal
        console.log('Opening new modal...');
        // Example: Use your existing modal library or code
    };

    // Keypress event listener setup
    window.addEventListener("keydown", handleKeyPress);

    // Cleanup function to remove event listener
    window.addEventListener("beforeunload", () => {
        window.removeEventListener("keydown", handleKeyPress);
    });
}

bootstrap();
