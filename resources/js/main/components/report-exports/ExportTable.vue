<template>
  <a-space v-if="willSubscriptionModuleVisible('reports_download')">
    <PdfTable :tableName="tableName" :title="title" />
    <PrintTable :tableName="tableName" />
    <ExportExcel :exportType="exportType" />
    <a-button type="primary">
      <span>
        <svg
          height="15"
          id="svg2"
          style=""
          version="1.1"
          width="15"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:cc="http://creativecommons.org/ns#"
          xmlns:dc="http://purl.org/dc/elements/1.1/"
          xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
          xmlns:svg="http://www.w3.org/2000/svg"
        >
          <defs id="defs4" />
          <g id="layer1" transform="translate(0,-1036.3622)">
            <path
              d="m -22.410713,-3.3303571 a 2.3660715,2.3660715 0 1 1 -4.732143,0 2.3660715,2.3660715 0 1 1 4.732143,0 z"
              id="path2985"
              style="fill: #fff; fill-opacity: 1; stroke: none"
              transform="matrix(0.84528301,0,0,0.84528301,33.943395,1042.1773)"
            />
            <path
              d="m -22.410713,-3.3303571 a 2.3660715,2.3660715 0 1 1 -4.732143,0 2.3660715,2.3660715 0 1 1 4.732143,0 z"
              id="path2985-1"
              style="fill: #fff; fill-opacity: 1; stroke: none"
              transform="matrix(0.84528301,0,0,0.84528301,33.943395,1052.1773)"
            />
            <path
              d="m -22.410713,-3.3303571 a 2.3660715,2.3660715 0 1 1 -4.732143,0 2.3660715,2.3660715 0 1 1 4.732143,0 z"
              id="path2985-1-7"
              style="fill: #fff; fill-opacity: 1; stroke: none"
              transform="matrix(0.84528301,0,0,0.84528301,23.943395,1047.1773)"
            />
            <path
              d="M 13,3 3,8 13,13"
              id="path3791"
              style="
                fill: none;
                stroke: #fff;
                stroke-width: 1px;
                stroke-linecap: butt;
                stroke-linejoin: miter;
                stroke-opacity: 1;
              "
              transform="translate(0,1036.3622)"
            />
          </g>
        </svg>
        Share
      </span>
    </a-button>
  </a-space>
</template>

<script>
import common from "../../../common/composable/common";
import PdfTable from "./PdfTable.vue";
import PrintTable from "./PrintTable.vue";
import ExportExcel from "./ExportExcel.vue";

export default {
  props: {
    tableName: {
      type: String,
      default: "reports",
    },
    title: {
      type: String,
      default: "Data",
    },
    exportType: {
      type: String,
      default: "",
    },
  },
  components: {
    PdfTable,
    PrintTable,
    ExportExcel,
  },
  setup(props, { emit }) {
    const { willSubscriptionModuleVisible } = common();

    return {
      willSubscriptionModuleVisible,
    };
  },
};
</script>
