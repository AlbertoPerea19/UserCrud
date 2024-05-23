<template>
  <div class="d-flex align-center">
    <!-- Imagen a la izquierda -->
    <div style="position: relative; width: 55%; height: 800px;">
      <v-img src="../assets/cool-background.svg" alt="Imagen de nosotros" cover width="100%" height="100%"></v-img>
      <v-text class="text-h2 text-center text-white font-weight-bold" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">UserSphere</v-text>
    </div>
    
    <SignUpForm></SignUpForm>
  </div>
</template>

<script>
import axios from "axios";
import SignUpForm from "./SignUpForm.vue";

export default {
  components: {
    SignUpForm
  }
};
</script>
