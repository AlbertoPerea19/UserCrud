<template>
  <div class="d-flex align-center">
    <!-- Imagen a la izquierda -->
    <div style="position: relative; width: 55%; height: 800px;">
  <v-img src="../assets/cool-background.svg" alt="Imagen de nosotros" cover width="100%" height="100%"></v-img>
  <v-text class="text-h2 text-center text-white font-weight-bold" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">UserSphere</v-text>
</div>


    <!-- Tarjeta a la derecha, centrada -->
    <div class="mx-auto">
      <v-card class="pa-12 pb-8 rounded-xl" elevation="12" width="500" rounded="lg">
        <div class="text-center mb-4"> <!-- Agregado mb-4 para agregar espacio -->
          <v-text class="text-h4 text-center font-weight-bold" style="color: #142862">Join us!</v-text>
        </div>
        <v-form ref="form" lazy-validation @submit.prevent="signup">
          <v-text-field v-model="newUser.first_name" label="First Name" outlined dense :rules="usernameRules"
            class="mb-2"></v-text-field>
          <v-text-field v-model="newUser.last_name" label="Last Name" outlined dense :rules="usernameRules"
            class="mb-2"></v-text-field>
          <v-text-field v-model="newUser.email" label="Email" outlined dense :rules="emailRules"
            class="mb-2"></v-text-field>
          <v-text-field v-model="newUser.password" label="Password" outlined dense
            :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'"
            prepend-inner-icon="mdi-lock-outline" @click:append-inner="visible = !visible" :rules="passwordRules"
            class="mb-2"></v-text-field>
          <v-select v-model="newUser.role" :items="roles" label="Select Role" outlined dense class="mb-2"></v-select>
          <v-btn type="submit" color="#142862" block>Sign up</v-btn>
        </v-form>

        <div class="mt-2">
          <p class="text-body-2">
            Already have an account?
            <router-link to="/login" class="text-blue text-decoration-none">Sign in</router-link>
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
      roles: ["Admin", "User"],
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
        v => (v && v.length >= 3) || "Username must be at least 6 characters",
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
