<template>
  <card :title="$t('home')">
    <index-crud></index-crud>
  </card>
</template>

<script>
import indexCrud from "./test-crud/indexCrud";
export default {
  middleware: "auth",

  metaInfo() {
    return { title: this.$t("home") };
  },

  components: {
    indexCrud
  }
};
</script>
