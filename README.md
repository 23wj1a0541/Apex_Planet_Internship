# Secure PHP Blog Application

A fully functional, secure, database-driven web application built with PHP, MySQL, and Bootstrap. 

## Features
* **User Authentication:** Secure registration and login using `password_hash()`.
* **Role-Based Access Control:** Admin vs. Regular User permissions.
* **CRUD Operations:** Admins can Create, Read, Update, and Delete posts.
* **Search & Pagination:** Easily filter through posts and navigate pages.
* **Security:** Fully protected against SQL Injection using Prepared Statements.
* **UI/UX:** Fully responsive and styled using Bootstrap 5.

## How to Install and Run
1. Install XAMPP and start the Apache and MySQL modules.
2. Clone or place this project folder inside the `xampp/htdocs/` directory.
3. Open `http://localhost/phpmyadmin` and create a new database named `blog`.
4. Import the provided `blog.sql` file into the database.
5. Open `http://localhost/my-project/index.php` in your browser.

## Default Admin Account
To test admin features, you can log in with your first registered account or change a user's role to 'admin' manually in the database.