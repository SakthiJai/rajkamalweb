import { notification, Modal } from "ant-design-vue";
import { createRouter, createWebHistory } from "vue-router";
import axios from "axios";
import { find, includes, remove, replace } from "lodash-es";
import store from "../store";

import AuthRoutes from "./auth";
import DashboardRoutes from "./dashboard";
import ProfileRoutes from "./profiles";
import ProductRoutes from "./products";
import StockRoutes from "./stocks";
import ExpensesRoutes from "./expenses";

// import ProductCategoryRoutes from "./product-category";



import StateRoutes from "./state";
// import CategoryRoutes from "./category";
import StationRoutes from "./station";
import ReceiptRoutes from "./receipt";
import JournalRoutes from "./journal";
import ContraRoutes from "./contra";
import UserRoutes from "./users";
import SettingRoutes from "./settings";
import ReportsRoutes from "./reports";
import SetupAppRoutes from "./setupApp";
import StaffRoutes from "./hrm/staff";
import LeaveRoutes from "./hrm/leaves";
import HolidayRoutes from "./hrm/holiday";
import AttendanceRoutes from "./hrm/attendance";
import PayrollRoutes from "./hrm/payroll";
import AppreciationRoutes from "./hrm/appreciations";
import HrmDashboardRoutes from "./hrm/hrmDashboard";
import HrmSettingsRoutes from "./hrm/hrmSettings";
import { checkUserPermission } from "../../common/scripts/functions";
import CashBankRoutes from "./cashbank";
import ProductsRoutes from "./products";

import FrontRoutes from "./front";
import WebsiteSetupRoutes from "./websiteSetup";

const appType = window.config.app_type;
const allActiveModules = window.config.modules;

const isAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (appSetting.x_currency_id == null || appSetting.x_warehouse_id == null) {
        return false;
    }

    return true;
};

const isSuperAdminCompanySetupCorrect = () => {
    var appSetting = store.state.auth.appSetting;

    if (
        appSetting.x_currency_id == null ||
        appSetting.white_label_completed == false
    ) {
        return false;
    }
    return true;
};
const router = createRouter({
    history: createWebHistory(),
    routes: [
        ...FrontRoutes,
        {
            path: "",
            redirect: "/admin/login",
        },
        ...WebsiteSetupRoutes,
        ...ProductRoutes,
        ...StockRoutes,
        ...ExpensesRoutes,
        ...AuthRoutes,
        ...DashboardRoutes,
        ...ProfileRoutes,
        ...UserRoutes,
        ...ReportsRoutes,
        ...SettingRoutes,
        ...StaffRoutes,
        ...LeaveRoutes,
        ...HolidayRoutes,
        ...AttendanceRoutes,
        ...PayrollRoutes,
        ...AppreciationRoutes,
        ...HrmDashboardRoutes,
        ...HrmSettingsRoutes,
        ...StateRoutes,
        ...StationRoutes,
        ...ReceiptRoutes,
        // ...ProductCategoryRoutes,
        ...ContraRoutes,
        ...JournalRoutes,
        ...CashBankRoutes,
        // ...CategoryRoutes,
        ...ProductsRoutes,
    ],
    scrollBehavior: () => ({ left: 0, top: 0 }),
});

// Including SuperAdmin Routes
const superadminRouteFilePath = appType == "saas" ? "superadmin" : "";
if (appType == "saas") {
    const newSuperAdminRoutePromise = import(
        `../../${superadminRouteFilePath}/router/index.js`
    );
    const newsubscriptionRoutePromise = import(
        `../../${superadminRouteFilePath}/router/admin/index.js`
    );

    Promise.all([newSuperAdminRoutePromise, newsubscriptionRoutePromise]).then(
        ([newSuperAdminRoute, newsubscriptionRoute]) => {
            newSuperAdminRoute.default.forEach((route) =>
                router.addRoute(route)
            );
            newsubscriptionRoute.default.forEach((route) =>
                router.addRoute(route)
            );
            SetupAppRoutes.forEach((route) => router.addRoute(route));
        }
    );
} else {
    SetupAppRoutes.forEach((route) => router.addRoute(route));
}

const checkLogFog = (to, from, next) => {
    console.log("checkLogFog", to, from, next);

    const prefix = window.config.app_type == "non-saas" ? "admin" : "superadmin";
    const nameParts = to.name.split(".");

    if (nameParts.length > 0 && nameParts[0] == "superadmin") {
        if (
            to.meta.requireAuth &&
            store.getters["auth/isLoggedIn"] &&
            store.state.auth.user &&
            !store.state.auth.user.is_superadmin
        ) {
            store.dispatch("auth/logout");
            next({ name: "admin.login" });
        } else {
            if (
                to.meta.requireAuth &&
                isSuperAdminCompanySetupCorrect() == false &&
                nameParts[1] != "setup_app"
            ) {
                next({ name: "superadmin.setup_app.index" });
            } else {
                if (to.meta.requireAuth && !store.getters["auth/isLoggedIn"]) { console.log("need login");
                    next({ name: "admin.login" });
                } else {
                    to.meta.requireUnauth && store.getters["auth/isLoggedIn"]
                        ? next({ name: "superadmin.dashboard.index" })
                        : next();
                }
            }
        }
    } else {
        console.log("else part calling");
        if (
            nameParts.length > 0 &&
            nameParts[0] == "admin" &&
            store.state.auth &&
            store.state.auth.user &&
            store.state.auth.user.is_superadmin
        ) {
             console.log("else part calling 11");
            next({ name: "superadmin.dashboard.index" });
        } else {
            if (nameParts.length > 0 && nameParts[0] == "admin") {  console.log("else part calling 12");
                if (to.meta.requireAuth && !store.getters["auth/isLoggedIn"]) {  console.log("else part calling 13");
                    store.dispatch("auth/logout");
                    next({ name: "admin.login" });
                } else {
                     console.log("else part calling 14");
                    if (
                        to.meta.requireAuth &&
                        isAdminCompanySetupCorrect() == false &&
                        nameParts[1] != "setup_app"
                    ) {
                         console.log("else part calling 15");
                        next({ name: "admin.setup_app.index" });
                    } else {  console.log("else part calling 16");
                        if (to.meta.requireUnauth && store.getters["auth/isLoggedIn"]) {
                            next({ name: "admin.dashboard.index" });
                        } else {
                            if (to.name == prefix + ".settings.modules.index") { console.log("else part calling 17");

                                store.commit("auth/updateAppChecking", false);
                                next();
                            } else {
                                 console.log("else part calling 18");
                                var permission = to.meta.permission;
                                if (nameParts[1] == "stock") {
                                    permission = replace(permission, "-", "_");
                                }
                                !to.meta.permission || checkUserPermission(permission, store.state.auth.user)
                                    ? next()
                                    : next({ name: "admin.dashboard.index" });
                            }
                        }
                    }
                }
            } else {
                if (nameParts.length > 0 && nameParts[0] == "front") {
                    if (to.meta.requireAuth && !store.getters["front/isLoggedIn"]) {
                        store.dispatch("front/logout");
                        next({ name: "front.homepage" });
                    } else {
                        next();
                    }
                } else {
                    next();
                }
            }
        }
    }
};
var mainProductName = "Stockifly";
if (window.config.app_type == "saas") {
    mainProductName += "Saas";
}
var modArray = [{ verified_name: mainProductName, value: false }];
allActiveModules.forEach((module) => {
    modArray.push({ verified_name: module, value: false });
});

const isAnyModuleNotVerified = () => {
        return find(modArray, ["value", ![]]);
    },
     isCheckUrlValid = (check, codeifly, envato) => {
    if (
        check.length !== 5 ||
        codeifly.length !== 8 ||
        envato.length !== 6
    ) {
        return false;
    } else {
        if (
            check.charAt(0) !== "c" ||
            check.charAt(1) !== "h" ||
            check.charAt(2) !== "e" ||
            check.charAt(3) !== "c" ||
            check.charAt(4) !== "k"
        ) {
            return false;
        } else {
            if (
                codeifly.charAt(0) !== "c" ||
                codeifly.charAt(1) !== "o" ||
                codeifly.charAt(2) !== "d" ||
                codeifly.charAt(3) !== "e" ||
                codeifly.charAt(4) !== "i" ||
                codeifly.charAt(5) !== "f" ||
                codeifly.charAt(6) !== "l" ||
                codeifly.charAt(7) !== "y"
            ) {
                return false;
            } else {
                return (
                    envato.charAt(0) === "e" &&
                    envato.charAt(1) === "n" &&
                    envato.charAt(2) === "v" &&
                    envato.charAt(3) === "a" &&
                    envato.charAt(4) === "t" &&
                    envato.charAt(5) === "o"
                );
            }
        }
    }
};
    const  isAxiosResponseUrlValid = (url) => {
    return url.charAt(8) === "e" &&
        url.charAt(9) === "n" &&
        url.charAt(10) === "v" &&
        url.charAt(11) === "a" &&
        url.charAt(12) === "t" &&
        url.charAt(13) === "o" &&
        url.charAt(15) === "c" &&
        url.charAt(16) === "o" &&
        url.charAt(17) === "d" &&
        url.charAt(18) === "e" &&
        url.charAt(20) === "f" &&
        url.charAt(21) === "l" &&
        url.charAt(22) === "y" &&
        url.charAt(24) === "c" &&
        url.charAt(25) === "o" &&
        url.charAt(26) === "m";
};
router.beforeEach((to, from, next) => {
    var envato = "envato",
        codeifly = "codeifly",
        check = "check",
        modules = { modules: window.config.modules };
    to.meta &&
        to.meta.module &&
        ((modules.module = to.meta.module),
        !includes(allActiveModules, to.meta.module) &&
            next({ name: "modules_not_registered" }));
    if (!isCheckUrlValid(check, codeifly, envato))
        Modal.error({
            title: "Error",
            content: "Don't try to null it... otherwise it may cause error on your server.",
        });
    else {
        var prefix =
            window.config.app_type == "non-saas" ? "admin" : "superadmin";
        if (
            isAnyModuleNotVerified() !== undefined &&
            to.name &&
            to.name != "admin.setup_app.index" &&
            to.name != prefix + ".settings.modules.index"
        ) {
            var url =
                "https://" + envato + "." + codeifly + ".com/" + check;
            console.log("post", "url", url, {
                verified_name: mainProductName,
                ...modules,
                domain: window.location.host,
            });
            axios({
                method: "post",
                url: url,
                data: {
                    verified_name: mainProductName,
                    ...modules,
                    domain: "localhost",
                },
                timeout: 4000,
            })
                .then((response) => {
                    if (!isAxiosResponseUrlValid(response.config.url)) {
                        console.log("11");
                        Modal.error({
                            title: "Error",
                            content: "Don't try to null it... otherwise it may cause error on your server.",
                        });
                    } else {
                        console.log("22");
                        store.commit("auth/updateAppChecking", false);
                        const data = response.data;
                        data.is_main_product_valid &&
                            (modArray.forEach((item) => {
                                item.verified_name == mainProductName &&
                                    (item.value = true);
                            }),
                            modArray.forEach((item) => {
                                if (
                                    includes(
                                        data.modules_not_registered,
                                        item.verified_name
                                    ) ||
                                    includes(
                                        data.multiple_registration_modules,
                                        item.verified_name
                                    )
                                ) {
                                    if (item.verified_name != mainProductName) {
                                        var activeModules = [
                                                ...window.config.modules,
                                            ],
                                            filtered = remove(
                                                activeModules,
                                                function (mod) {
                                                    return mod != item.verified_name;
                                                }
                                            );
                                        store.commit(
                                            "auth/updateActiveModules",
                                            filtered
                                        );
                                        window.config.modules = filtered;
                                    }
                                    item.value = false;
                                } else item.value = true;
                            }));
                        if (!data.is_main_product_valid) {
                        } else {
                            if (
                                !data.main_product_registered ||
                                data.multiple_registration
                            )
                                next({ name: "admin.setup_app.index" });
                            else {
                                if (
                                    to.meta &&
                                    to.meta.module &&
                                    find(modArray, {
                                        verified_name: to.meta.module,
                                        value: false,
                                    }) !== undefined
                                ) {
                                    notification.error({
                                        placement: "bottomRight",
                                        message: "Modules Not Verified",
                                        description: "permission",
                                    });
                                    const redirect =
                                        appType == "saas"
                                            ? "superadmin"
                                            : "admin";
                                    next({
                                        name: redirect + ".settings.modules.index",
                                    });
                                } else checkLogFog(to, from, next);
                            }
                        }
                    }
                })
                .catch((error) => {
                    !isAxiosResponseUrlValid(error.toJSON().config.url)
                        ? Modal.error({
                              title: "Error!",
                              content: "Don't try to null it... otherwise it may cause error on your server.",
                          })
                        : (modArray.forEach((item) => {
                              item.value = true;
                          }),
                          store.commit("auth/updateAppChecking", false),
                          next());
                });
        } else checkLogFog(to, from, next);
    }
});

export default router;
