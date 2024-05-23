<template>
  <div class="d-flex align-center">
    <!-- Imagen a la izquierda -->
    <div style="position: relative; width: 55%; height: 800px;">
      <v-img src="../assets/cool-background.svg" alt="Imagen de nosotros" cover width="100%" height="100%"></v-img>
      <v-text class="text-h2 text-center text-white font-weight-bold" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">UserSphere</v-text>
    </div>
    
    <SignUpForm :pageTitle="'Join Us'"></SignUpForm>
    
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
