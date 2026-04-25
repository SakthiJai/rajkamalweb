<template>
    <a-col :span="24">
        <a-button type="primary" @click="downloadPdf" :loading="loading" class="pdfcolor" >
            <FilePdfOutlined />
            {{ $t("common.pdf") }}
        </a-button>
    </a-col>
</template>

<script>
import { ref } from "vue";
import { FilePdfOutlined } from "@ant-design/icons-vue";
import jsPDF from "jspdf";
import "jspdf-autotable";

export default {
    props: ["tableName", "title"],
    components: {
        FilePdfOutlined,
    },
    setup(props) {
        const loading = ref(false);

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

            if (headerTitle) {
                return headerTitle;
            }

            return (
                document.title?.replace(/\s+/g, " ").trim() ||
                props.tableName ||
                "Report"
            );
        };

        const sanitizeFileName = (value) => {
            return (value || props.tableName || "report")
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
                .replace(/â‚¹|₹|¹/g, "Rs.")
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

        const getTableContent = ({ headerTable, bodyTable }) => {
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

            const body = Array.from(
                bodySource.querySelectorAll("tbody tr")
            )
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

        const downloadPdf = () => {
            loading.value = true;

            const tableContainer = document.getElementById(props.tableName);

            if (!tableContainer) {
                loading.value = false;
                return;
            }

            const pdf = new jsPDF();
            const reportTitle = getPageTitle();
            const fileName = sanitizeFileName(reportTitle);

            pdf.setProperties({
                title: reportTitle,
            });

            if (isProfitLossReport()) {
                const exportSections = getProfitLossSections(tableContainer);

                exportSections.forEach((section, index) => {
                    const { head, body } = getProfitLossContent(section);

                    if (head.length === 0 && body.length === 0) {
                        return;
                    }

                    if (index > 0) {
                        pdf.addPage();
                    }

                    pdf.setFontSize(12);
                    pdf.text(reportTitle, 14, 15);
                    pdf.autoTable({
                        head,
                        body,
                        startY: 22,
                        styles: {
                            fontSize: 8,
                            cellPadding: 2,
                        },
                        headStyles: {
                            fillColor: [31, 109, 112],
                        },
                        columnStyles: {
                            0: { cellWidth: 110 },
                            1: { cellWidth: 60, halign: "right" },
                        },
                    });
                });

                pdf.save(`${fileName}.pdf`);
                loading.value = false;
                return;
            }

            const groupedTables = Array.from(
                tableContainer.querySelectorAll("[class*='tbl_result_']")
            );

            const exportSections =
                groupedTables.length > 0 ? groupedTables : [tableContainer];

            exportSections.forEach((section, index) => {
                const tableElements = getTableElements(section);
                const { head, body } = getTableContent(tableElements);

                if (head.length === 0 && body.length === 0) {
                    return;
                }

                if (index > 0) {
                    pdf.addPage();
                }

                pdf.setFontSize(12);
                pdf.text(reportTitle, 14, 15);
                pdf.autoTable({
                    head,
                    body,
                    startY: 22,
                    styles: {
                        fontSize: 8,
                        cellPadding: 2,
                    },
                    headStyles: {
                        fillColor: [31, 109, 112],
                    },
                });
            });

            pdf.save(`${fileName}.pdf`);

            loading.value = false;
        };

        return {
            downloadPdf,
            loading,
        };
    },
};
</script>


