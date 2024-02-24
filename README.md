# User Management System

This is a user management system developed with Laravel on the backend and Vue.js on the frontend.

## Project Description

The user management system allows administrators to create, edit, delete, and list users. It also provides login and registration functionalities for users.

## Key Features

- **Authentication:** Users can log in using their credentials, and administrators can manage user roles.
- **User CRUD:** Administrators can perform CRUD (Create, Read, Update, Delete) operations on users, including role assignment.
- **Data Validation:** Frontend and backend validations ensure data integrity.
- **Intuitive User Interface:** The user interface provides an intuitive experience for managing users.

## Technologies Used

- **Backend:** Laravel PHP Framework
- **Frontend:** Vue.js Framework
- **Database:** MySQL
- **Routing:** Vue Router (Frontend) and Laravel Routing (Backend)
- **State Management:** Vuex (Frontend)
- **Authentication:** JWT (JSON Web Tokens)

## Installation and Usage

1. Clone this repository to your local machine.
2. Install backend dependencies using Composer:

   ```bash
   composer install
   ```

3. Copy the `.env.example` file and rename it to `.env`. Configure your database and other settings as needed.
4. Generate a new application key:

   ```bash
   php artisan key:generate
   ```

5. Run migrations to create the database tables:

   ```bash
   php artisan migrate
   ```

6. Install frontend dependencies using npm:

   ```bash
   npm install
   ```

7. Start the development server:

   ```bash
   npm run dev
   ```

8. Open your browser and visit the address provided by the development server to access the user management system.

## Contribution

If you would like to contribute to this project, follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bug fix (`git checkout -b feature/new-feature`).
3. Make your changes and commit them (`git commit -am 'Add a new feature'`).
4. Push your branch to the repository (`git push origin feature/new-feature`).
5. Open a pull request and describe your changes in detail.
