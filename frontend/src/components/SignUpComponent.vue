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
      <v-form ref="form" lazy-validation @submit.prevent="signup">
        <v-text-field
          v-model="newUser.username"
          label="Username"
          :rules="usernameRules"
        ></v-text-field>
        <v-text-field
          v-model="newUser.email"
          label="Email"
          :rules="emailRules"
        ></v-text-field>
        <v-text-field
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          label="Password"
          prepend-inner-icon="mdi-lock-outline"
          @click:append-inner="visible = !visible"
          v-model="newUser.password"
          :rules="passwordRules"
        ></v-text-field>
        <v-select
          v-model="newUser.role"
          :items="roles"
          label="Select Role"
          outlined
        ></v-select>

        <v-btn type="submit" color="primary" block class="mt-2">Sign up</v-btn>
      </v-form>
      <div class="mt-2">
        <p class="text-body-2">
          Already have an account?
          <router-link to="/login" class="text-blue text-decoration-none"
            >Sign in</router-link
          >
        </p>
      </div>
    </v-card>
    <v-snackbar v-model="snackbar" :color="snackbarColor" multi-line>
      {{ snackbarMessage }}
      <v-btn color="white" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      roles: ["Admin", "User"],
      newUser: {
        username: "",
        email: "",
        password: "",
        role: null,
      },
      visible: false,
      snackbar: false,
      snackbarMessage: "",
      snackbarColor: "",
    };
  },
  methods: {
    signup() {
      this.$refs.form.validate().then(valid => {
        if (valid) {
          axios
            .post("http://localhost:3000/auth/register", this.newUser)
            .then((response) => {
              console.log(response);
              this.$router.push("/login");
            })
            .catch((error) => {
              if (error.response.status === 400) {
                this.snackbarMessage = "Username or email already exists";
                this.snackbarColor = "error";
                this.snackbar = true;
              }
              console.log(error);
            });
        }
      });
    },
  },
  computed: {
    usernameRules() {
      return [
        v => !!v || "Username is required",
        v => (v && v.length >= 6) || "Username must be at least 6 characters",
      ];
    },
    emailRules() {
      return [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ];
    },
    passwordRules() {
      return [
        v => !!v || "Password is required",
        v => (v && v.length >= 6) || "Password must be at least 6 characters",
        v => /^(?=.*[a-zA-Z])(?=.*[0-9])/.test(v) || "Password must be alphanumeric",
      ];
    },
  },
};
</script>
