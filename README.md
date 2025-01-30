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
Install dependencies:

bash
Copy
Edit
composer install
Copy the .env file:

bash
Copy
Edit
cp .env.example .env
Generate an application key:

bash
Copy
Edit
php artisan key:generate
Configure your .env file (Database credentials).

Run migrations to create the required database tables:

bash
Copy
Edit
php artisan migrate
Start the development server:

bash
Copy
Edit
php artisan serve
How to Use
Open the application in your browser at http://127.0.0.1:8000
Use the form to add new users.
Edit or delete users using the action buttons.
Export user data to CSV using the provided option.
Project Structure
bash
Copy
Edit
/project-folder
│── app/
│── database/
│── public/uploads/ (stores profile pictures)
│── routes/web.php (handles routing)
│── resources/views/ (contains frontend templates)
│── .env (environment configurations)
│── README.md (this file)
License
This project is open-source and available for modification and distribution. is all this things i want to write in readme file
