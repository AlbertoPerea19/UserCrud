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
            <v-btn
              v-if="isAdmin"
              color="primary"
              dark
              v-bind="props"
              @click="isNewUser = true"
            >
              New User
            </v-btn>
            <v-btn color="error" @click="logout">Logout</v-btn>
          </template>
          <v-card
            class="mx-auto pa-12 pb-8"
            elevation="8"
            max-width="448"
            rounded="lg"
            cols="12"
          >
            <div class="text-subtitle-1 text-lg-emphasis pa-2">User</div>

            <v-text-field
              v-model="editUser.first_name"
              label="First name"
            ></v-text-field>
            <v-text-field
              v-model="editUser.last_name"
              label="Last name"
            ></v-text-field>
            <v-text-field
              v-model="editUser.username"
              label="Username"
              :rules="usernameRules"
            ></v-text-field>
            <v-text-field
              :rules="passwordRules"
              v-if="isNewUser"
              v-model="editUser.password"
              label="password"
              :append-inner-icon="'mdi-refresh'"
              @click:append-inner="generatePassword()"
            ></v-text-field>
            <v-text-field :rules="emailRules" v-model="editUser.email" label="Email"></v-text-field>
            <v-select
              v-model="editUser.role"
              :items="roles"
              label="Select Role"
              outlined
            ></v-select>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="close">
                Cancel
              </v-btn>
              <v-btn color="blue-darken-1" variant="text" @click="save">
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5"
              >Are you sure you want to delete this item?</v-card-title
            >
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue-darken-1" variant="text" @click="closeDelete"
                >Cancel</v-btn
              >
              <v-btn
                color="blue-darken-1"
                variant="text"
                @click="deleteItemConfirm"
                >OK</v-btn
              >
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon size="small" class="me-2" v-if="isAdmin" @click="editItem(item)">
        mdi-pencil
      </v-icon>
      <v-icon size="small" v-if="isAdmin" @click="deleteItem(item)">
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn color="primary" @click="initialize"> Reset </v-btn>
    </template>
  </v-data-table>
  <v-snackbar v-model="snackbar" :color="snackbarColor" multi-line>
      {{ snackbarMessage }}
      <v-btn color="white" text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
</template>

<script>
import axios from "axios";

const axiosInstance = axios.create({
  baseURL: "http://localhost:3000",
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
  data: () => ({
    roles: ["Admin", "User"],
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
      { title: "Username", key: "username" },
      { title: "Email", key: "email" },
      { title: "Role", key: "role" },
    ],
    users: [],
    editedIndex: -1,
    editUser: {
      id: 0,
      first_name: "",
      last_name: "",
      username: "",
      email: "",
      role: "",
      password: "",
    },
    defaultItem: {
      id: 0,
      first_name: "",
      last_name: "",
      username: "",
      email: "",
      role: "",
    },
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
    this.isAdmin();
  },

  computed: {
    usernameRules() {
      return [
        v => !!v || "Username is required",
        v => (v && v.length >= 6) || "Username must be at least 6 characters",
        v => !this.users.find(u => u.username === v ) || 'Username already exists'
      ];
    },
    emailRules() {
      return [
        v => !!v || "E-mail is required",
        v => /.+@.+\..+/.test(v) || "E-mail must be valid",
        v => !this.users.find(u => u.email === v) || 'Email already exists'
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

  methods: {
    initialize() {
      axiosInstance
        .get("/user")
        .then((response) => {
          this.users = response.data;
          console.log(this.users);
        })
        .catch((error) => {
          console.error("Error fetching data:", error);
        });
    },

    isAdmin() {
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

    deleteItem(item) {
      this.editedIndex = this.users.indexOf(item);
      this.editUser = Object.assign({}, item);
      this.dialogDelete = true;
    },

    deleteItemConfirm() {
      axiosInstance
        .delete(`/user/${this.editUser.id}`)
        .then((response) => {
          console.log("Element deleted successfully:", response.data);
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

    save() {
      if (this.isNewUser) {
        axiosInstance
          .post("/user", this.editUser)
          .then((response) => {
            console.log("Element created successfully:", response.data);
            this.close();
            this.initialize();
          })
          .catch((error) => {
            console.error("Error creating element:", error);
            this.verifyBadRequest(error);
          });
      } else {
        if (!this.isNewUser && !this.editUser.password) {
          delete this.editUser.password;
        }
        axiosInstance
          .put(`/user/${this.editUser.id}`, this.editUser)
          .then((response) => {
            console.log("Element updated successfully:", response.data);
            this.close();
            this.initialize();
          })
          .catch((error) => {
            console.error("Error updating element:", error);
            this.verifyBadRequest(error);
          });
      }
    },

    verifyBadRequest(error){
      if (error.response.status === 500) {
        this.snackbarMessage = "Username or email already exists";
        this.snackbarColor = "error";
        this.snackbar = true;
      }
    },

    generatePassword() {
      let length = 12,
        charset =
          "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
        retVal = "";
      for (let i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
      }
      this.editUser.password = retVal;
    },
  },
};
</script>
