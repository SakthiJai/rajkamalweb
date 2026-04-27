<template>
    <a-col :span="24">
        <a-button type="primary" @click="exportExcel" class="excelcolor">
            <FileExcelOutlined />
            {{ $t("common.excel") }}
        </a-button>
    </a-col>
</template>

<script>
import { ref } from "vue";
import { useStore } from "vuex";
import { find } from "lodash-es";
import { FileExcelOutlined } from "@ant-design/icons-vue";
import * as XLSX from "xlsx";
import allFields from "../../../common/composable/allFields";

export default {
    props: {
        exportType: {
            type: String,
            default: "",
        },
        tableName: {
            type: String,
            default: "",
        },
        title: {
            type: String,
            default: "",
        },
    },
    components: {
        FileExcelOutlined,
    },
    setup(props) {
        const store = useStore();
        const { getColumns } = allFields();
        const fileName = ref(props.exportType + ".xlsx");
        const sheetName = ref(props.exportType || "Sheet1");

        const getPageTitle = () => {
            if (props.title && props.title.toString().trim()) {
                return props.title.toString().trim();
            }

            const breadcrumbItems = Array.from(
                document.querySelectorAll(".ant-breadcrumb .ant-breadcrumb-item")
            )
                .map((item) => item.textContent?.replace(/\s+/g, " ").trim())
                .filter(Boolean);

            if (breadcrumbItems.length > 0) {
                return breadcrumbItems[breadcrumbItems.length - 1];
            }

            const headerTitle =
                document
                    .querySelector(
                        ".admin-page-header h1, .admin-page-header h2, .admin-page-header h3"
                    )
                    ?.textContent?.replace(/\s+/g, " ")
                    .trim() || "";

            return headerTitle || document.title || sheetName.value;
        };

        const sanitizeWorksheetName = (value) => {
            return (value || "Sheet1")
                .toString()
                .replace(/[\\/*?:[\]]/g, " ")
                .trim()
                .substring(0, 31) || "Sheet1";
        };

        const sanitizeFileName = (value) => {
            return (value || props.exportType || "report")
                .toString()
                .trim()
                .replace(/[\\/:*?"<>|]+/g, " ")
                .replace(/\s+/g, " ");
        };

        const normalizeCurrencyText = (value) => {
            if (!value) {
                return "";
            }

            return value
                .replace(/Ã¢â€šÂ¹|â‚¹|Â¹/g, "Rs.")
                .replace(/Rs\.\s*(?=\d)/g, "Rs. ")
                .replace(/(\d)\s+(?=[\d,.\-])/g, "$1")
                .replace(/([,.\-])\s+(?=\d)/g, "$1");
        };

        const getCellText = (cell) => {
            const rawText = cell?.textContent || "";

            return normalizeCurrencyText(
                rawText.replace(/\s+/g, " ").trim()
            );
        };

        const isActionColumn = (value) => {
            const normalizedValue = (value || "")
                .toString()
                .trim()
                .toLowerCase();

            return normalizedValue === "action" || normalizedValue === "actions";
        };

        const getTableElements = (container) => {
            const headerTable =
                container.querySelector(".ant-table-header table") ||
                container.querySelector("table thead")?.closest("table") ||
                null;

            const bodyTable =
                container.querySelector(".ant-table-body table") ||
                Array.from(container.querySelectorAll("table")).sort(
                    (firstTable, secondTable) =>
                        secondTable.querySelectorAll("tbody tr").length -
                        firstTable.querySelectorAll("tbody tr").length
                )[0] ||
                null;

            return {
                headerTable,
                bodyTable,
            };
        };

        const getDomTableContent = ({ headerTable, bodyTable }) => {
            if (!headerTable && !bodyTable) {
                return {
                    head: [],
                    body: [],
                };
            }

            const headSource = headerTable || bodyTable;
            const bodySource = bodyTable || headerTable;
            const headRows = Array.from(headSource.querySelectorAll("thead tr")).map(
                (row) =>
                    Array.from(row.querySelectorAll("th")).map((cell) =>
                        getCellText(cell)
                    )
            );

            const actionColumnIndexes = new Set();
            const excludedColumnIndexes = new Set();

            headRows.forEach((row) => {
                row.forEach((cellText, index) => {
                    if (isActionColumn(cellText)) {
                        actionColumnIndexes.add(index);
                    }

                    if (cellText === "") {
                        excludedColumnIndexes.add(index);
                    }
                });
            });

            const head = headRows
                .map((row) =>
                    row.filter((cellText, index) => {
                        return (
                            !actionColumnIndexes.has(index) &&
                            !excludedColumnIndexes.has(index)
                        );
                    })
                )
                .filter((row) => row.length > 0);

            const body = Array.from(bodySource.querySelectorAll("tbody tr"))
                .map((row) =>
                    Array.from(row.querySelectorAll("td"))
                        .map((cell) => getCellText(cell))
                        .filter((cellText, index, cells) => {
                            return (
                                !actionColumnIndexes.has(index) &&
                                !excludedColumnIndexes.has(index) &&
                                (cells.length > 1 || cellText !== "")
                            );
                        })
                )
                .filter((row) => row.length > 0);

            return {
                head,
                body,
            };
        };

        const isProfitLossReport = () => {
            return (
                props.tableName === "profit-loss-reports-table" ||
                props.tableName === "profit-loss-reports-by-dates-table"
            );
        };

        const getProfitLossSections = (tableContainer) => {
            const resultSections = Array.from(
                tableContainer.querySelectorAll("[class*='tbl_result_']")
            );

            return resultSections.length > 0 ? resultSections : [tableContainer];
        };

        const getProfitLossContent = (section) => {
            const descriptionRows = Array.from(
                section.querySelectorAll(".ant-descriptions table tbody tr")
            );

            if (descriptionRows.length === 0) {
                return {
                    head: [],
                    body: [],
                };
            }

            const body = [];

            descriptionRows.forEach((row) => {
                const labels = Array.from(
                    row.querySelectorAll(".ant-descriptions-item-label")
                ).map((cell) => getCellText(cell));

                const contents = Array.from(
                    row.querySelectorAll(".ant-descriptions-item-content")
                ).map((cell) => getCellText(cell));

                if (labels.length === 0 && contents.length === 0) {
                    return;
                }

                if (labels.length === 0 && contents.length === 1) {
                    body.push([contents[0], ""]);
                    return;
                }

                const rowCount = Math.max(labels.length, contents.length);

                for (let index = 0; index < rowCount; index += 1) {
                    const label = labels[index] || "";
                    const value = contents[index] || "";

                    if (label !== "" || value !== "") {
                        body.push([label, value]);
                    }
                }
            });

            return {
                head: [["Particulars", "Amount"]],
                body,
            };
        };

        const getCurrentPageData = () => {
            const tableContainer = document.getElementById(props.tableName);

            if (!tableContainer) {
                return {
                    head: [],
                    body: [],
                };
            }

            if (isProfitLossReport()) {
                const sections = getProfitLossSections(tableContainer);
                const results = sections
                    .map((section) => getProfitLossContent(section))
                    .filter((section) => section.head.length || section.body.length);

                if (results.length === 0) {
                    return {
                        head: [],
                        body: [],
                    };
                }

                const mergedHead = results[0].head;
                const mergedBody = results.flatMap((section) => section.body);

                return {
                    head: mergedHead,
                    body: mergedBody,
                };
            }

            const groupedTables = Array.from(
                tableContainer.querySelectorAll("[class*='tbl_result_']")
            );

            const exportSections =
                groupedTables.length > 0 ? groupedTables : [tableContainer];

            const results = exportSections
                .map((section) => {
                    const tableElements = getTableElements(section);
                    return getDomTableContent(tableElements);
                })
                .filter((section) => section.head.length || section.body.length);

            if (results.length === 0) {
                return {
                    head: [],
                    body: [],
                };
            }

            return {
                head: results[0].head,
                body: results.flatMap((section) => section.body),
            };
        };

        const getStoreExportData = () => {
            var allExportDatas = store.state.auth.allExportData || [];
            const currentRoutePath = window.location.pathname;
            var storeExportData = find(
                allExportDatas,
                (allExportData) =>
                    allExportData.export_type == props.exportType &&
                    allExportData.route_path == currentRoutePath
            );

            if (!storeExportData) {
                storeExportData = find(
                    allExportDatas,
                    (allExportData) =>
                        allExportData.export_type == props.exportType
                );
            }

            return storeExportData;
        };

        const shouldUseDomFirst = () => {
            return [
                "payment_reports",
                "sales_summary_reports",
                "stock_summary_reports",
                "rate_list_reports",
                "product_sales_summary_reports",
            ].includes(props.exportType);
        };

        const exportUsingDom = (pageTitle) => {
            const { head, body } = getCurrentPageData();

            if (head.length === 0 && body.length === 0) {
                return false;
            }

            const rows = [];

            if (head.length > 0) {
                head.forEach((row) => rows.push(row));
            }

            body.forEach((row) => rows.push(row));

            const workbook = XLSX.utils.book_new();
            const worksheet = XLSX.utils.aoa_to_sheet(rows);

            worksheet["!cols"] = formatExcelCols(rows);

            XLSX.utils.book_append_sheet(
                workbook,
                worksheet,
                sanitizeWorksheetName(pageTitle)
            );
            XLSX.writeFile(workbook, `${sanitizeFileName(pageTitle)}.xlsx`);

            return true;
        };

        const exportUsingStore = (pageTitle) => {
            const storeExportData = getStoreExportData();
            const tablecolumn = getColumns(props.exportType) || [];
            const tableData = storeExportData?.data || [];

            let createXLSLFormatObj = [];
            let newXlsHeader = [];

            if (tablecolumn.length === 0 || tableData.length === 0) {
                return false;
            }

            tablecolumn.map((column) => {
                newXlsHeader.push(column.title);
            });
            createXLSLFormatObj.push(newXlsHeader);

            tableData.map((value) => {
                let innerRowData = [];
                tablecolumn.map((val) => {
                    if (val.dataFormat && typeof val.dataFormat === "function") {
                        innerRowData.push(val.dataFormat(value));
                    } else {
                        let fieldValue = value[val.dbKey] ? value[val.dbKey] : "";

                        if (val.dbKey.split(".").length > 1) {
                            fieldValue = getNestedValue(value, val.dbKey);
                        }

                        innerRowData.push(fieldValue);
                    }
                });

                createXLSLFormatObj.push(innerRowData);
            });

            let workbook = XLSX.utils.book_new(),
                worksheet = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);

            worksheet["!cols"] = formatExcelCols(createXLSLFormatObj);

            XLSX.utils.book_append_sheet(
                workbook,
                worksheet,
                sanitizeWorksheetName(pageTitle)
            );
            XLSX.writeFile(workbook, `${sanitizeFileName(pageTitle)}.xlsx`);

            return true;
        };

        const exportExcel = () => {
            const pageTitle = getPageTitle();

            if (shouldUseDomFirst()) {
                if (exportUsingDom(pageTitle)) {
                    return;
                }

                exportUsingStore(pageTitle);
                return;
            }

            if (exportUsingStore(pageTitle)) {
                return;
            }

            if (props.tableName) {
                exportUsingDom(pageTitle);
            }
        };

        const formatExcelCols = (rows) => {
            if (!rows || rows.length === 0) {
                return [];
            }

            const maxColumns = Math.max(...rows.map((row) => row.length));
            const widthArr = Array.from({ length: maxColumns }, () => ({
                width: 12,
            }));

            for (let i = 0; i < rows.length; i += 1) {
                const value = rows[i];

                for (let j = 0; j < value.length; j += 1) {
                    const cellValue =
                        value[j] === null || value[j] === undefined
                            ? ""
                            : value[j].toString();

                    if (cellValue.length > widthArr[j].width) {
                        widthArr[j].width = cellValue.length + 3;
                    }
                }
            }

            return widthArr;
        };

        const getNestedValue = (object, string) => {
            string = string.replace(/\[(\w+)\]/g, ".$1");
            string = string.replace(/^\./, "");
            let a = string.split(".");
            for (let i = 0, n = a.length; i < n; ++i) {
                let k = a[i];
                if (k in object) {
                    object = object[k];
                } else {
                    return;
                }
            }

            return object;
        };

        return {
            exportExcel,
        };
    },
};
</script>
<style>
</style>
