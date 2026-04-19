# Software Projects Web Application

## Overview
This is a database-driven web application developed using PHP and MySQL. It allows users to browse, search, and manage software projects securely.

## Features

### Public Users
- View all projects
- Search projects by title or start date
- View project details
- Register a new account

### Registered Users
- Log in and log out
- Access a personal dashboard
- Add new projects
- Edit their own projects
- Delete their own projects

## Security Features
This application includes several security measures:
- Authentication using PHP sessions
- Authorisation (users can only edit their own projects)
- Password hashing using `password_hash()` and `password_verify()`
- SQL injection protection using prepared statements (PDO)
- Cross-Site Scripting (XSS) protection using `htmlspecialchars()`
- Cross-Site Request Forgery (CSRF) protection using tokens
- Client-side and server-side form validation

## Technologies Used
- PHP (PDO)
- MySQL
- HTML, CSS, JavaScript

## Setup Instructions
1. Import the database using `sql/aproject.sql`
2. Place the project folder inside `htdocs`
3. Start Apache and MySQL (XAMPP)
4. Access the system via:
   http://localhost/software-projects-app/

## Test User
Username: testuser  
Password: test123
