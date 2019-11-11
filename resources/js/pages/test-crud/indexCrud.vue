<template >
  <div>
    <div class="row">
      <div class="col-lg-10 mb-3">
        <form>
          <div class="input-group index-search">
            <input
              type="text"
              class="form-control"
              placeholder="Search records"
              v-model="moreParams.search"
            />
            <div class="input-group-append">
              <button class="btn btn-warning" @click.prevent="doFilter">
                <i class="fa fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-lg-2 mb-3">
        <router-link
          class="btn btn-warning float-right btn-animated from-left fa fa-plus"
          :to="'create'"
          append
        >
          <span>Add</span>
        </router-link>
      </div>
    </div>

    <div class="col-lg-12 mb-5">
      <vuetable
        ref="vuetable"
        :api-url="'/api/tests'"
        :http-fetch="fetchData"
        :fields="fields"
        pagination-path
        :css="css.table"
        :sort-order="sortOrder"
        :multi-sort="true"
        :append-params="filterParams"
        @vuetable:pagination-data="onPaginationData"
      >
        <template slot="actions" slot-scope="props">
          <div class="btn-group btn-group-xs">
            <router-link :to="`edit/${props.rowData.id}`" class="btn btn-warning" append>
              <i class="fa fa-pencil"></i>&nbsp;Edit
            </router-link>
            <button class="btn btn-danger" @click="deleteNode(props.rowData.id)">
              <i class="fa fa-trash"></i>Delete
            </button>
          </div>
        </template>
      </vuetable>
      <div class="vuetable-pagination">
        <vuetable-pagination-info ref="paginationInfo" info-class="pagination-info"></vuetable-pagination-info>
        <vuetable-pagination
          ref="pagination"
          :css="css.pagination"
          @vuetable-pagination:change-page="onChangePage"
        ></vuetable-pagination>
      </div>
    </div>
  </div>
</template>

<script>
// import * as alerts from "sweet-alerts.js";
import axios from "axios";
import Vuetable from "vuetable-2/src/components/Vuetable";
import VuetablePagination from "vuetable-2/src/components/VuetablePagination";
import VuetablePaginationInfo from "vuetable-2/src/components/VuetablePaginationInfo";

export default {
  components: {
    Vuetable,
    VuetablePagination,
    VuetablePaginationInfo
  },

  data() {
    return {
      fields: [
        {
          name: "__sequence",
          title: "#",
          titleClass: "text-right",
          dataClass: "text-right v-align-middle"
        },
        /*   {
          name: "created_at",
          title: "Date",
          sortField: "created_at",
          dataClass: "v-align-middle"
          // callback: "formatDate"
        }, */
        {
          name: "name",
          sortField: "name",
          dataClass: "v-align-middle"
        },
        {
          name: "description",
          sortField: "description",
          dataClass: "v-align-middle"
        },
        {
          name: "created_by.name",
          title: "Created by",
          // sortField: "created",
          dataClass: "v-align-middle"
        },
        {
          name: "updated_by.name",
          title: "Updated by",
          // sortField: "updated",
          dataClass: "v-align-middle"
        },

        {
          name: "__slot:actions",
          title: "Actions",
          titleClass: "text-center",
          dataClass: "text-center v-align-middle",
          callback: "renderActions"
        }
      ],
      css: {
        table: {
          tableWrapper: "",
          tableHeaderClass: "mb-0",
          tableBodyClass: "mb-0",
          tableClass: "table table-responsive-block table-hover",
          loadingClass: "loading",
          ascendingIcon: "fa fa-chevron-up",
          descendingIcon: "fa fa-chevron-down",
          ascendingClass: "sorted-asc",
          descendingClass: "sorted-desc",
          sortableIcon: "fa fa-sort",
          detailRowClass: "vuetable-detail-row",
          handleIcon: "fa fa-bars text-secondary",
          renderIcon(classes, options) {
            return `<i class="${classes.join(" ")}"></span>`;
          }
        },
        pagination: {
          wrapperClass: "pagination float-right",
          activeClass: "active",
          disabledClass: "disabled",
          pageClass: "page-item",
          linkClass: "page-link",
          paginationClass: "pagination",
          paginationInfoClass: "float-left",
          dropdownClass: "form-control",
          icons: {
            first: "fa fa-chevron-left",
            prev: "fa fa-chevron-left",
            next: "fa fa-chevron-right",
            last: "fa fa-chevron-right"
          }
        }
      },
      sortOrder: [],
      moreParams: {
        search: ""
      }
    };
  },
  computed: {
    filterParams: function() {
      return {
        ...this.moreParams
      };
    }
  },
  methods: {
    fetchData(apiUrl, httpOptions) {
      //   const params = $.param(httpOptions.params);
      return axios.get("/api/tests", {
        params: httpOptions.params
      });
    },
    allcap(value) {
      return value.toUpperCase();
    },
    onPaginationData(paginationData) {
      this.$refs.pagination.setPaginationData(paginationData);
      this.$refs.paginationInfo.setPaginationData(paginationData);
    },
    onChangePage(page) {
      this.$refs.vuetable.changePage(page);
    },
    async deleteNode(id) {
      await axios.delete(`/api/tests/${id}`);

      this.$nextTick(() => this.$refs.vuetable.refresh());
    },

    doFilter() {
      this.$nextTick(() => this.$refs.vuetable.refresh());
    }
  }
};
</script>