<template>
  <div class="d-flex align-center">
    <!-- Imagen a la izquierda -->
    <div style="position: relative; width: 55%; height: 800px;">
      <v-img src="../assets/cool-background.svg" alt="Imagen de nosotros" cover width="100%" height="100%"></v-img>
      <v-text class="text-h2 text-center text-white font-weight-bold" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">UserSphere</v-text>
    </div>

    <EmployeeForm 
    :pageTitle="'Join Us'"
    :formTitle="'Sign Up'"
    :SignUp="true"
    :textSignUp="true"
    @formSubmitted="handleSignup"
    ></EmployeeForm>
    
  </div>
</template>

<script>
import axios from "axios";
import EmployeeForm from "./EmployeeForm.vue";

export default {
  data() {
    return {
      snackbar: false,
      snackbarMessage: "",
      snackbarColor: "",
    };
  },
  methods: {
    handleSignup(formData) {
          axios
            .post(`${process.env.VUE_APP_API_URL}/auth/register `, formData)
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
    },
    components: { EmployeeForm }
};
</script>
