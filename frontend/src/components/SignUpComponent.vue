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
      <v-form fast-fail @submit.prevent="signup">
        <v-text-field
          variant="underlined"
          v-model="newUser.username"
          label="Username"
        ></v-text-field>
        <v-text-field
          variant="underlined"
          v-model="newUser.email"
          label="Email"
        ></v-text-field>
        <v-text-field
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          label="Password"
          prepend-inner-icon="mdi-lock-outline"
          @click:append-inner="visible = !visible"
          v-model="newUser.password"
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

    };
  },
  methods: {
    signup() {
        console.log(this.newUser)
      axios
        .post("http://localhost:8000/api/auth/signup", this.newUser)
        .then((response) => {
          console.log(response);
          this.$router.push("/login");
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
