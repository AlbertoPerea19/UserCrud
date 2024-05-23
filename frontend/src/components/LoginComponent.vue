<template>
  <div class="d-flex align-center">
    <!-- Imagen a la izquierda -->
    <div style="position: relative; width: 65%; height: 950px;">
      <v-img src="../assets/cool-background.svg" alt="Imagen de nosotros" cover width="100%" height="100%"></v-img>
      <v-text class="text-h2 text-center text-white font-weight-bold" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">UserSphere</v-text>
    </div>

    <!-- Tarjeta a la derecha, centrada -->
    <div class="mx-auto">
      <v-card class="pa-12 pb-8 rounded-xl" elevation="12" width="500" rounded="lg">
        <div class="text-center mb-4">
          <v-text class="text-h4 text-center font-weight-bold" style="color: #142862">Welcome Back!</v-text>
        </div>
        <v-form ref="form" lazy-validation @submit.prevent="login">
          <v-text-field v-model="email" label="Email" outlined dense :rules="emailRules" class="mb-2" prepend-inner-icon="mdi-email-outline"></v-text-field>
          <v-text-field v-model="password" label="Password" outlined dense :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'" prepend-inner-icon="mdi-lock-outline" @click:append-inner="visible = !visible" :rules="passwordRules" class="mb-2"></v-text-field>
          <v-btn type="submit" color="#142862" block>Log In</v-btn>
        </v-form>

        <div class="mt-2">
          <p class="text-body-2">
            Don't have an account?
            <router-link to="/signup" class="text-blue text-decoration-none">Sign up now</router-link>
          </p>
        </div>
      </v-card>
      
      <v-snackbar v-model="snackbar" :color="snackbarColor" multi-line>
        {{ snackbarMessage }}
        <v-btn color="white" text @click="snackbar = false">Close</v-btn>
      </v-snackbar>
    </div>
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
      snackbar: false,
      snackbarMessage: "",
      snackbarColor: "",
    };
  },
  methods: {
    login() {
      axios
        .post(`${process.env.VUE_APP_API_URL}/auth/login `, {
          email: this.email,
          password: this.password,
        })
        .then((response) => {
          const token = response.data.token
          const role = response.data.role
          localStorage.setItem("token", token);
          localStorage.setItem("role", role);
          this.$router.push("/");
        })
        .catch((error) => {
          if (error.response.status === 404) {
                this.snackbarMessage = "User not found";
                this.snackbarColor = "error";
                this.snackbar = true;
              }
              console.log(error);
            });
    },
  },
  computed: {
    emailRules() {
      return [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ];
    },
    passwordRules() {
      return [
        v => !!v || "Password is required",
      ];
    },
  },
};
</script>
