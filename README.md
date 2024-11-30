# Movie & TV Series Rating Application

This is a comprehensive web application for rating movies and TV series, with unique functionalities for both admins and users. The system provides a user-friendly interface and allows admins to manage content, while users can interact with movies, series, reviews, and actors.

## Features

### Admin Features:
- Full management of movies, series, actors, users, and admin roles.
- Ability to view, add, edit, and delete movies, series, actors, and reviews.

### User Features:
- **Home Page:** Displays the top-rated movies and series along with the most liked reviews.
- **Movies & Series Pages:** Browse all works, view details, add reviews and ratings, and interact with others’ reviews.
- **Review Pages (For Movies & Series):** Showcases all works with reviews and allows user interaction.
- **Actors Page:** Displays all actors with their associated works.
- Search and filtering are available on all pages, with the option to search by year for movies and series.

## Technologies Used:
- **Frontend:** HTML, CSS, JavaScript, Bootstrap, TailwindCSS
- **Backend:** PHP, Laravel
- **Database:** MySQL
- **Relationships:** Many-to-Many, Morph

## Installation

To run this project locally, follow these steps:

1. Clone this repository:
    ```bash
    git clone https://github.com/yourusername/yourrepository.git
    ```

2. Navigate to the project directory:
    ```bash
    cd yourrepository
    ```

3. Install dependencies:
    ```bash
    composer install
    npm install
    ```

4. Set up your `.env` file and configure your database.

5. Run migrations:
    ```bash
    php artisan migrate
    ```

6. **Run Seeder to create users, including an admin:**
    After running the migrations, run the following command to seed users, including an admin user with a default email and password:
    ```bash
    php artisan db:seed
    ```

7. **Admin Login:**
    After seeding, the admin user is created with the following credentials:
    - **Email:** admin@gmail.com
    - **Password:** password

    Use these credentials to log in as an admin and manage the application.

8. Start the local development server:
    ```bash
    php artisan serve
    ```

## License

This project is open-source and available under the [MIT License](LICENSE).

## Acknowledgements

- Laravel for the backend framework.
- Bootstrap & TailwindCSS for the front-end design.
- MySQL for the database management system.
