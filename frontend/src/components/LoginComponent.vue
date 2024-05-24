<template>
  <div class="d-flex align-center">
    <div style="position: relative; width: 65%; height: 950px;">
      <v-img src="../assets/cool-background.svg" alt="Imagen de nosotros" cover width="100%" height="100%"></v-img>
      <v-text class="text-h2 text-center text-white font-weight-bold" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">UserSphere</v-text>
    </div>
    <div class="mx-auto">

        <SignUpForm 
        :pageTitle="'Welcome Back'"
        :formTitle="'Log In'"
        :LogIn="true"
        @formSubmitted="handleLogin"
        ></SignUpForm>
    
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
      snackbar: false,
      snackbarMessage: "",
      snackbarColor: "",
    };
  },
  methods: {
    handleLogin(formData) {
      axios
        .post(`${process.env.VUE_APP_API_URL}/auth/login `, formData)
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
};
</script>
