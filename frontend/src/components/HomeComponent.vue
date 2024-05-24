<template>
  <v-data-table
    :headers="headers"
    :items="users"
    :sort-by="[{ key: 'id', order: 'asc' }]"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>USER CRUD</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="500px">
          <template v-slot:activator="{ props }">
            <v-btn v-if="isAdmin" color="primary" dark v-bind="props" @click="newUser">New User</v-btn>
            <v-btn color="error" @click="logout">Logout</v-btn>
          </template>
          <EmployeeForm
          :pageTitle="'Employees'"
          :Home="true"
          :formTitle="'Save'"
          :formTitle2="'Cancel'"
          :SignUp="true"
          @formSubmitted="handleSignup"
          />
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon size="small" class="me-2" v-if="isAdmin" @click="editItem(item)">mdi-pencil</v-icon>
      <v-icon size="small" v-if="isAdmin" @click="deleteItem(item)">mdi-delete</v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize">Reset</v-btn>
    </template>
  </v-data-table>
  <v-snackbar v-model="snackbar" :color="snackbarColor" multi-line>
    {{ snackbarMessage }}
    <v-btn color="white" text @click="snackbar = false">Close</v-btn>
  </v-snackbar>
</template>

<script>
import axios from "axios";
import EmployeeForm from "./EmployeeForm.vue";

const axiosInstance = axios.create({
  baseURL: `${process.env.VUE_APP_API_URL}`,
});

axiosInstance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem("token");
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default {
  components: {
    EmployeeForm
},
  data: () => ({
    roles: ["Admin", "Developer", "Marketing"],
    dialog: false,
    dialogDelete: false,
    snackbar: false,
    snackbarMessage: "",
    snackbarColor: "",
    headers: [
      {
        title: "Id",
        align: "start",
        key: "id",
      },
      { title: "First name", key: "first_name" },
      { title: "Last name", key: "last_name" },
      { title: "Email", key: "email" },
      { title: "Role", key: "role" },
    ],
    users: [],
    editedIndex: -1,
    editUser: {
      id: 0,
      first_name: "",
      last_name: "",
      email: "",
      role: "",
      password: "",
    },
    defaultItem: {
      id: 0,
      first_name: "",
      last_name: "",
      email: "",
      role: "",
    },
    isNewUser: false,
  }),

  watch: {
    dialog(val) {
      val || this.close();
    },
    dialogDelete(val) {
      val || this.closeDelete();
    },
  },

  created() {
    this.initialize();
    const token = localStorage.getItem("token");
    if (!token) {
      this.$router.push("/login");
    }
    this.checkAdmin();
  },

  methods: {
    initialize() {
      axiosInstance
        .get("/user")
        .then((response) => {
          this.users = response.data;
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },

    checkAdmin() {
      const role = localStorage.getItem("role");
      if (role === "Admin") {
        this.isAdmin = true;
        this.headers.push({
          title: "Actions",
          key: "actions",
          sortable: false,
        });
      } else {
        this.isAdmin = false;
      }
    },

    editItem(item) {
      this.editedIndex = this.users.indexOf(item);
      this.editUser = Object.assign({}, item);
      this.dialog = true;
      this.isNewUser = false;
    },

    newUser() {
      this.editUser = Object.assign({}, this.defaultItem);
      this.dialog = true;
      this.isNewUser = true;
    },

    deleteItem(item) {
      this.editedIndex = this.users.indexOf(item);
      this.editUser = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      axiosInstance
        .delete(`/user/${this.editUser.id}`)
        .then((response) => {
          this.users.splice(this.editedIndex, 1);
          this.closeDelete();
        })
        .catch((error) => {
          console.error("Error deleting element:", error);
        });
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editUser = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editUser = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    logout() {
      localStorage.removeItem("token");
      this.$router.push("/login");
    },

    saveUser(user) {
      if (!this.validateUser(user)) {
        this.snackbarMessage = "Please fill in all fields.";
        this.snackbarColor = "error";
        this.snackbar = true;
        return;
      }

      if (this.isNewUser) {
        axiosInstance
          .post("/user", user)
          .then((response) => {
            this.users.push(response.data);
            this.close();
          })
          .catch((error) => {
            console.error("Error creating element:", error);
            this.verifyBadRequest(error);
          });
      } else {
        axiosInstance
          .put(`/user/${user.id}`, user)
          .then((response) => {
            Object.assign(this.users[this.editedIndex], response.data);
            this.close();
          })
          .catch((error) => {
            console.error("Error updating element:", error);
            this.verifyBadRequest(error);
          });
      }
    },

    validateUser(user) {
      return (
        user.first_name.trim() !== "" &&
        user.last_name.trim() !== "" &&
        user.username.trim() !== "" &&
        user.email.trim() !== "" &&
        user.role.trim() !== ""
      );
    },


    verifyBadRequest(error) {
      if (error.response.status === 500) {
        this.snackbarMessage = "Username or email already exists";
        this.snackbarColor = "error";
        this.snackbar = true;
      }
    },
  },
};
</script>
