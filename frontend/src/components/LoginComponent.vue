<template>
  <div>
    <v-img
      class="mx-auto my-6"
      max-width="228"
      src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"
    ></v-img>

    <v-card
      class="mx-auto pa-12 pb-8"
      elevation="8"
      max-width="448"
      rounded="lg"
    >
      <div class="text-subtitle-1 text-medium-emphasis">Email</div>

      <v-text-field
        density="compact"
        placeholder="Email"
        prepend-inner-icon="mdi-email-outline"
        variant="outlined"
        v-model="email"
      ></v-text-field>

      <div
        class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between"
      >
        Password
      </div>

      <v-text-field
        :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
        :type="visible ? 'text' : 'password'"
        density="compact"
        placeholder="Enter your password"
        prepend-inner-icon="mdi-lock-outline"
        variant="outlined"
        @click:append-inner="visible = !visible"
        v-model="password"
      ></v-text-field>

      <v-btn
        block
        class="mb-8"
        color="blue"
        size="large"
        variant="tonal"
        @click="login"
      >
        Log In
      </v-btn>

      <v-card-text class="text-center">
        <router-link to="/signup" class="text-blue text-decoration-none">
          Sign up now <v-icon icon="mdi-chevron-right"></v-icon>
        </router-link>
      </v-card-text>
    </v-card>
  </div>
</template>
<script>
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
      visible: false,
    };
  },
  methods: {
    login() {
      axios
        .post("http://localhost:8000/api/auth/login", {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          const token = response.data.token
          localStorage.setItem("token", token);
          this.$router.push("/");
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
