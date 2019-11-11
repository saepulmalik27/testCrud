<template>
  <div>
    <card :title="$t(`${mode} test`)">
      <form @submit.prevent="processForm" @keydown="form.onKeydown($event)">
        <!-- Email -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('name') }}</label>
          <div class="col-md-7">
            <input
              v-model="form.name"
              :class="{ 'is-invalid': form.errors.has('name') }"
              class="form-control"
              type="text"
              name="name"
            />
            <has-error :form="form" field="name" />
          </div>
        </div>

        <!-- Password -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">{{ $t('description') }}</label>
          <div class="col-md-7">
            <!--       <input
              v-model="form.description"
              :class="{ 'is-invalid': form.errors.has('description') }"
              class="form-control"
              type="text"
              name="description"
            />-->
            <textarea
              name="description"
              cols="30"
              rows="10"
              v-model="form.description"
              :class="{ 'is-invalid': form.errors.has('description') }"
              class="form-control"
            ></textarea>
            <has-error :form="form" field="description" />
          </div>
        </div>

        <!-- Remember Me -->

        <div class="form-group row">
          <div class="col-md-7 offset-md-3 d-flex">
            <!-- Submit Button -->
            <v-button :loading="form.busy">{{ $t('submit') }}</v-button>

            <!-- GitHub Login Button -->
          </div>
        </div>
      </form>
    </card>
  </div>
</template>

<script>
import axios from "axios";
import Form from "vform";

export default {
  props: {
    mode: { type: String, default: null }
  },

  mounted() {
    if (this.$route.params.id) {
      this.fetchData();
    }
  },
  data: () => ({
    form: new Form({
      name: "",
      description: ""
    }),
    record: {}
  }),

  methods: {
    async fetchData() {
      this.record = {
        ...(await axios.get(`/api/tests/${this.$route.params.id}`)).data
      };

      this.setForm(this.record);
    },
    setForm(record) {
      this.$set(this.form, "name", record.name);
      this.$set(this.form, "description", record.description);
    },
    async processForm() {
      // Submit the form.
      if (this.$route.params.id) {
        const { data } = await this.form.patch(
          `/api/tests/${this.$route.params.id}`
        );
      } else {
        const { data } = await this.form.post("/api/tests");
      }
      // Save the token.
      // this.$store.dispatch("auth/saveToken", {
      //   token: data.token,
      //   remember: this.remember
      // });

      // // Fetch the user.
      // await this.$store.dispatch("auth/fetchUser");

      // Redirect home.
      this.$router.push({ name: "home" });
    }
  }
};
</script>