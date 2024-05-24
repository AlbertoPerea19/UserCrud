<template>
  <v-container>
    <v-row>
      <v-col cols="12" class="text-center">
        <v-avatar size="150">
          <v-img src="../assets/photoProfile.jpg" alt="profile" ></v-img>
        </v-avatar>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title>
            {{ user.first_name }} {{ user.last_name }}
          </v-card-title>
          <v-card-subtitle>{{ user.email }}</v-card-subtitle>
          <v-card-text>
            <div><strong>Role:</strong> {{ user.role }}</div>
            <!-- Additional generic information -->
            <div v-if="user.joined"><strong>Joined:</strong> {{ user.joined }}</div>
            <div v-if="user.last_login"><strong>Last Login:</strong> {{ user.last_login }}</div>
          </v-card-text>
          <v-card-actions>
            <v-btn @click="goBack">Back</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
 data() {
   return {
     user: {
       avatar: '../assets/cool-background.svg',
       first_name: 'John',
       last_name: 'Doe',
       email: 'john.doe@example.com',
       role: 'User',
       joined: '2023-01-01',
       last_login: '2024-05-22'
     }
   };
 },
 created() {
   this.fetchUserDetails();
 },
 methods: {
   fetchUserDetails() {
     const userId = this.$route.params.id;
     const token = localStorage.getItem("token");
     axios.get(`${process.env.VUE_APP_API_URL}/user/${userId}`,{
      headers: {
        Authorization: `Bearer ${token}`
      }
     })
       .then(response => {
         this.user = response.data;
       })
       .catch(error => {
         console.error("Error fetching user details:", error);
       });
   },
   goBack() {
     this.$router.push('/');
   }
 }
};
</script>

<style scoped>
.v-avatar img {
 border-radius: 50%;
}
</style>
