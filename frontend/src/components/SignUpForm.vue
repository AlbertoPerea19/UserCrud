<template>
  <!-- Tarjeta a la derecha, centrada -->
  <div class="mx-auto">
    <v-card class="pa-12 pb-8 rounded-xl" elevation="12" width="500" rounded="lg">
      <div class="text-center mb-4">
        <!-- Agregado mb-4 para agregar espacio -->
        <v-text v-if="pageTitle" class="text-h4 text-center font-weight-bold" :style="{ color: '#142862' }">
          {{ pageTitle }}
        </v-text>
        <!-- Aquí puedes poner otros elementos de tu página -->
      </div>

      <v-form ref="form" lazy-validation @submit.prevent="handleSubmit">
        <v-text-field
          v-if="SignUp"
          v-model="formData.first_name"
          label="First Name"
          outlined
          dense
          :rules="usernameRules"
          class="mb-2"
        ></v-text-field>
        <v-text-field
          v-if="SignUp"
          v-model="formData.last_name"
          label="Last Name"
          outlined
          dense
          :rules="usernameRules"
          class="mb-2"
        ></v-text-field>
        <v-text-field
          v-model="formData.email"
          label="Email"
          outlined
          dense
          :rules="emailRules"
          class="mb-2"
        ></v-text-field>
        <v-text-field
          v-model="formData.password"
          label="Password"
          outlined
          dense
          :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
          :type="visible ? 'text' : 'password'"
          prepend-inner-icon="mdi-lock-outline"
          @click:append-inner="visible = !visible"
          :rules="passwordRules"
          class="mb-2"
        ></v-text-field>
        <v-select
          v-if="SignUp"
          v-model="formData.role"
          :items="roles"
          label="Select Role"
          outlined
          dense
          class="mb-2"
        ></v-select>
        <v-btn type="submit" color="#142862" block>{{ formTitle }}</v-btn>
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
    formTitle: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      roles: ["Admin", "Developer", "Marketing"],
      formData: {
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

    handleSubmit() {
      this.$refs.form.validate().then((valid) => {
        if (valid) {
          // Emitir el evento con los datos del formulario
          this.$emit("formSubmitted", this.formData);
        }
      });
    },
  },
  computed: {
    emailRules() {
      return [
        (v) => !!v || "E-mail is required",
        (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ];
    },
    passwordRules() {
      return [
        (v) => !!v || "Password is required",
        (v) => (v && v.length >= 6) || "Password must be at least 6 characters",
        (v) => /^(?=.*[a-zA-Z])(?=.*[0-9])/.test(v) || "Password must be alphanumeric",
      ];
    },
  },
};
</script>