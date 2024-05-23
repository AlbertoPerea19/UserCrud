<template>
  <div>
    <v-img
      class="mx-auto my-6"
      max-width="228"
      src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
    ></v-img>
    <SignUpForm></SignUpForm>
  </div>
</template>

<script>
import axios from "axios";
import SignUpForm from "./SignUpForm.vue";

export default {
  components: {
    SignUpForm
  }
};
</script>
