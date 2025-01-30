# User Management System

This project is a simple User Management System built using Laravel. It allows users to add, edit, view, delete, and export user data to a CSV file.

## Features

- **User Management:** Create, read, update, and delete user records.
- **Form Validation:** Real-time validation for Name, Email, Mobile Number, Profile Picture, and Password.
- **Profile Picture Upload:** Stores the profile picture in the public/uploads directory.
- **Data Display:** Lists all users in a table with edit and delete options.
- **CSV Export:** Allows exporting user data to a CSV file.

## Technologies Used

- Laravel (PHP Framework)
- MySQL (Database)
- Bootstrap (Frontend)
- JavaScript (Validation)

## Installation

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd <project-folder>
2. Install dependencies:


composer install

3. Copy the .env file:


cp .env.example .env

4. Generate an application key:

php artisan key:generate

5. Configure your .env file (Database credentials).

6. Run migrations to create the required database tables:


php artisan migrate

7. Start the development server:

php artisan serve
How to Use
1. Open the application in your browser at http://127.0.0.1:8000
2. Use the form to add new users.
3. Edit or delete users using the action buttons.
4. Export user data to CSV using the provided option.





