<template>
  <div class="text-center">
    <v-card class="mx-auto pa-16 pb-12" elevation="8" max-width="450" rounded="lg" cols="12">
      <div class="text-subtitle-1 text-lg-emphasis pa-2">User</div>
      
      <v-text-field v-model="user.first_name" label="First name" class="mb-4 display-1" width="400"></v-text-field>
      <v-text-field v-model="user.last_name" label="Last name" class="mb-4 display-1" width="400"></v-text-field>
      <v-text-field v-if="isNewUser" v-model="user.password" label="Password" :rules="passwordRules" :append-inner-icon="'mdi-refresh'" @click:append-inner="generatePassword()" class="mb-4 display-1" width="400"></v-text-field>
      <v-text-field v-model="user.email" label="Email" :rules="emailRules" class="mb-4 display-1" width="400"></v-text-field>
      <v-select v-model="user.role" :items="roles" label="Select Role" outlined class="mb-4 display-1" width="400"></v-select>
      
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue-darken-1" variant="text" @click="$emit('cancel')">Cancel</v-btn>
        <v-btn color="blue-darken-1" variant="text" @click="save">Save</v-btn>
        
      </v-card-actions>
    </v-card>
  </div>
</template>

  
  <script>
  export default {
    props: {
      user: Object,
      roles: Array,
      isNewUser: Boolean,
    },
    data: () => ({
      emailRules: [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid",
      ],
      passwordRules: [
        v => !!v || "Password is required",
        v => (v && v.length >= 6) || "Password must be at least 6 characters",
        v => /^(?=.*[a-zA-Z])(?=.*[0-9])/.test(v) || "Password must be alphanumeric",
      ],
    }),
    methods: {
      generatePassword() {
        let length = 12,
          charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
          retVal = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
          retVal += charset.charAt(Math.floor(Math.random() * n));
        }
        this.user.password = retVal;
      },
      save() {
        this.$emit('save', this.user);
      }
    }
  };
  </script>
  