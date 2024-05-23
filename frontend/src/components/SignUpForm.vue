<template>
    <!-- Tarjeta a la derecha, centrada -->
    <div class="mx-auto">
      <v-card class="pa-12 pb-8 rounded-xl" elevation="12" width="500" rounded="lg">
        <div class="text-center mb-4"> <!-- Agregado mb-4 para agregar espacio -->
        
        <v-text v-if="pageTitle" class="text-h4 text-center font-weight-bold" :style="{ color: '#142862' }">
          {{ pageTitle }}
        </v-text>

        <!-- Aquí puedes poner otros elementos de tu página -->
      </div>

        
        <v-form ref="form" lazy-validation @submit.prevent="signup">
          <v-text-field v-if="SignUp" v-model="newUser.first_name" label="First Name" outlined dense :rules="usernameRules"
            class="mb-2"></v-text-field>
          <v-text-field v-if="SignUp" v-model="newUser.last_name" label="Last Name" outlined dense :rules="usernameRules"
            class="mb-2"></v-text-field>
          <v-text-field v-model="newUser.email" label="Email" outlined dense :rules="emailRules"
            class="mb-2"></v-text-field>
          <v-text-field v-model="newUser.password" label="Password" outlined dense
            :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'"
            prepend-inner-icon="mdi-lock-outline" @click:append-inner="visible = !visible" :rules="passwordRules"
            class="mb-2"></v-text-field>
          <v-select v-if="isSignupPage" v-model="newUser.role" :items="roles" label="Select Role" outlined dense class="mb-2"></v-select>
          <v-btn type="submit" color="#142862" block>Sign up</v-btn>
        </v-form>

        <div class="mt-2">
          <p v-if="SignUp" class="text-body-2">
            Already have an account?
            <router-link to="/login" class="text-blue text-decoration-none">Log in</router-link>
          </p>
          <p v-if="LogIn" class="text-body-2">
            Don't have an account?
            <router-link to="/signup" class="text-blue text-decoration-none">Sign up</router-link>
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
  props: {
    pageTitle: {
      type: String,
      required: true,
    },
    SignUp: {
      type: Boolean,
      required: true,
    },
    LogIn: {
      type: Boolean,
      required: true,
    },
  },
  data() {
    return {
      isSignupPage: window.location.pathname === '/signup',
      isLoginPage: window.location.pathname === '/login',
      roles: ["Admin", "Developer", "Marketing"],
      newUser: {
        first_name: "",
        last_name: "",
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
            .post(`${process.env.VUE_APP_API_URL}/auth/register`, this.newUser)
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
