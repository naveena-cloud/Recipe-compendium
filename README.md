# Culinarium: Centralized Recipe Management Platform

## Overview
Culinarium is a centralized web-based recipe management platform that enables users to store, view, and manage recipes efficiently. The system supports role-based access, allowing administrators to add, update, and delete recipes while users can browse and view recipe details through a clean and interactive interface.

## Key Features
- Centralized recipe storage and management  
- Role-based access (Admin & User)  
- Add, update, view, and delete recipes  
- Secure login system  
- User-friendly and responsive UI  

## Technologies Used
- PHP  
- MySQL  
- HTML, CSS  
- JavaScript  
- XAMPP / Localhost Environment  


## How to Run the Project
1. Install **XAMPP** or any local server supporting PHP & MySQL  
2. Clone or download this repository  
3. Import the database from `database/recipe_manager.sql` into MySQL  
4. Place the project folder inside the `htdocs` directory  
5. Start Apache and MySQL services  
6. Open a browser and navigate to:
  http://localhost/Culinarium/

## Database
- **Database Name:** `recipe_manager`
- **Table:** `recipes`
- **Fields:** Recipe name, ingredients, instructions, cooking time

## Future Enhancements
- Password hashing and encryption  
- Search and filter functionality  
- Recipe categories and image support  
- REST API integration  

## Authentication Note
For security reasons, login credentials are not publicly shared.  
After setting up the database, users can create or modify credentials directly within the database as required.

