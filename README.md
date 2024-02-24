# User Management System

Este es un sistema de gestión de usuarios desarrollado con Laravel en el backend y Vue.js en el frontend.

## Descripción del Proyecto

El sistema de gestión de usuarios permite a los administradores crear, editar, eliminar y listar usuarios. También proporciona funcionalidades de inicio de sesión y registro para los usuarios.

## Características Principales

- **Autenticación:** Los usuarios pueden iniciar sesión utilizando sus credenciales y los administradores pueden gestionar los roles de usuario.
- **CRUD de Usuarios:** Los administradores pueden realizar operaciones CRUD (Crear, Leer, Actualizar, Eliminar) sobre los usuarios, incluyendo la asignación de roles.
- **Validación de Datos:** Se realizan validaciones en el frontend y el backend para garantizar la integridad de los datos.
- **Interfaz de Usuario Intuitiva:** La interfaz de usuario proporciona una experiencia intuitiva para la gestión de usuarios.

## Tecnologías Utilizadas

- **Backend:** Laravel PHP Framework
- **Frontend:** Vue.js Framework
- **Base de Datos:** MySQL
- **Enrutamiento:** Vue Router (Frontend) y Laravel Routing (Backend)
- **Gestión de Estado:** Vuex (Frontend)
- **Autenticación:** JWT (JSON Web Tokens)

## Instalación y Uso

1. Clona este repositorio en tu máquina local.
2. Instala las dependencias del backend utilizando Composer:

   ```bash
   composer install
   ```

3. Copia el archivo `.env.example` y renómbralo como `.env`. Configura tu base de datos y otros ajustes según sea necesario.
4. Genera una nueva clave de aplicación:

   ```bash
   php artisan key:generate
   ```

5. Ejecuta las migraciones para crear las tablas de la base de datos:

   ```bash
   php artisan migrate
   ```

6. Instala las dependencias del frontend utilizando npm:

   ```bash
   npm install
   ```

7. Inicia el servidor de desarrollo:

   ```bash
   npm run dev
   ```

8. Abre tu navegador y visita la dirección proporcionada por el servidor de desarrollo para acceder al sistema de gestión de usuarios.

## Contribución

Si deseas contribuir a este proyecto, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una nueva rama para tu función o corrección de errores (`git checkout -b feature/nueva-funcion`).
3. Realiza tus cambios y haz commit de ellos (`git commit -am 'Añade una nueva función'`).
4. Haz push de tu rama al repositorio (`git push origin feature/nueva-funcion`).
5. Abre un pull request y describe tus cambios detalladamente.

## Licencia

Este proyecto está licenciado bajo la [Licencia MIT](LICENSE).
