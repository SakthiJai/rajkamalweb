<template>
    <a-col :span="24">
        <a-button type="primary" @click="printTable">
            <PrinterOutlined />
            {{ $t("common.print") }}
        </a-button>
    </a-col>
</template>

<script>
import { PrinterOutlined } from "@ant-design/icons-vue";

export default {
    props: ["tableName"],
    components: {
        PrinterOutlined,
    },
    setup(props) {
        const getPageTitle = () => {
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

            return headerTitle || document.title || "Report";
        };

        const getCellText = (cell) => {
            return cell?.textContent?.replace(/\s+/g, " ").trim() || "";
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

        const escapeHtml = (value) => {
            return (value || "")
                .toString()
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#39;");
        };

        const getTableContent = ({ headerTable, bodyTable }) => {
            if (!headerTable && !bodyTable) {
                return {
                    head: [],
                    body: [],
                };
            }

            const headSource = headerTable || bodyTable;
            const bodySource = bodyTable || headerTable;
            const headerRows = Array.from(headSource.querySelectorAll("thead tr"));
            const leafHeaderRow = headerRows[headerRows.length - 1];

            if (!leafHeaderRow) {
                return {
                    head: [],
                    body: [],
                };
            }

            const rawHead = Array.from(leafHeaderRow.querySelectorAll("th")).map(
                (cell) => getCellText(cell)
            );

            const excludedColumnIndexes = new Set();

            rawHead.forEach((cellText, index) => {
                if (cellText === "" || isActionColumn(cellText)) {
                    excludedColumnIndexes.add(index);
                }
            });

            const head = rawHead.filter(
                (cellText, index) => !excludedColumnIndexes.has(index)
            );

            const body = Array.from(bodySource.querySelectorAll("tbody tr"))
                .map((row) =>
                    Array.from(row.querySelectorAll("td"))
                        .map((cell) => getCellText(cell))
                        .filter(
                            (cellText, index) =>
                                !excludedColumnIndexes.has(index)
                        )
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
            const descriptionTables = Array.from(
                section.querySelectorAll(".ant-descriptions table")
            );

            const descriptionRows = descriptionTables.flatMap((table) =>
                Array.from(table.querySelectorAll("tbody tr"))
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
                head: ["Particulars", "Amount"],
                body,
            };
        };

        const buildPrintableTable = ({ head, body }) => {
            if (head.length === 0 && body.length === 0) {
                return "";
            }

            const thead =
                head.length > 0
                    ? `<thead><tr>${head
                          .map((cell) => `<th>${escapeHtml(cell)}</th>`)
                          .join("")}</tr></thead>`
                    : "";

            const tbody = `<tbody>${body
                .map(
                    (row) =>
                        `<tr>${row
                            .map((cell) => `<td>${escapeHtml(cell)}</td>`)
                            .join("")}</tr>`
                )
                .join("")}</tbody>`;

            return `<table>${thead}${tbody}</table>`;
        };

        const printTable = () => {
            const tableContainer = document.getElementById(props.tableName);

            if (!tableContainer) {
                return;
            }

            const title = getPageTitle();
            let printableTable = "";

            if (isProfitLossReport()) {
                const exportSections = getProfitLossSections(tableContainer);

                printableTable = exportSections
                    .map((section) =>
                        buildPrintableTable(getProfitLossContent(section))
                    )
                    .filter(Boolean)
                    .join('<div class="print-section-break"></div>');
            } else {
                const tableElements = getTableElements(tableContainer);
                const tableContent = getTableContent(tableElements);
                printableTable = buildPrintableTable(tableContent);
            }

            if (!printableTable) {
                return;
            }

            const printWindow = window.open("", "_blank", "width=1000,height=700");

            if (!printWindow) {
                return;
            }

            printWindow.document.write(`
                <html>
                    <head>
                        <title>${escapeHtml(title)}</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                padding: 24px;
                                color: #000;
                            }
                            h2 {
                                margin: 0 0 16px;
                                font-size: 18px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                table-layout: auto;
                            }
                            th,
                            td {
                                border: 1px solid #d9d9d9;
                                padding: 8px;
                                text-align: left;
                                vertical-align: top;
                                word-break: break-word;
                            }
                            th {
                                background: #f5f5f5;
                                font-weight: 600;
                            }
                            .print-section-break {
                                height: 24px;
                            }
                        </style>
                    </head>
                    <body>
                        <h2>${escapeHtml(title)}</h2>
                        ${printableTable}
                    </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };

        return {
            printTable,
        };
    },
};
</script>
