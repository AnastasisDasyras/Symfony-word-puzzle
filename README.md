# Symfony Word Puzzle Game

A web-based word puzzle game built with Symfony 7.3, PHP 8.2, and Docker. Players create words from a given set of letters and earn points based on word length.

## Project Overview

This project is a word puzzle game where users:

- Register and login to play
- Receive a pool of letters to create words from
- Submit words and earn points (1 point per letter)
- View their scores on a leaderboard

## Technology Stack

- **Backend**: Symfony 7.3 with PHP 8.2
- **Database**: MariaDB with Doctrine ORM
- **Frontend**: Twig templates with vanilla JavaScript
- **Containerization**: Docker with Nginx and PHP-FPM
- **Authentication**: Symfony Security Bundle

## Project Structure

The project follows the MVC (Model-View-Controller) pattern:

- **Models**: User, Game, Puzzle entities
- **Views**: Twig templates for all pages
- **Controllers**: Handle game logic, authentication, and routing
- **Repository**: Custom repository for game validation logic

## Setup Instructions

### Prerequisites

- Docker and Docker Compose installed
- Make utility (for using the Makefile)

### Installation Steps

1. Clone the repository:

   ```bash
   git clone <repository-url>
   cd Symfony-word-puzzle
   ```

2. Build images:

   ```bash
   make build
   make start
   ```

3. Install Dependencies:

   ```bash
   make composer-install
   ```

4. Setup DB

   ```bash
   make exec-php-fpm
    php bin/console doctrine:migrations:migrate --no-interaction
   ```

This command will:

- Build Docker containers (Nginx, PHP-FPM, MariaDB)

3. Access the application:
   - URL: http://127.0.0.1:8080/
   - Database: localhost:3306 (user: root, password: rootpassword)

## Database Schema

The application uses three main entities:

1. **User**: Stores user authentication and profile data
2. **Puzzle**: Contains letter pools for word creation
3. **Game**: Tracks individual game sessions and scores

## Game Logic

1. Users must register/login to play
2. Each game is associated with a specific puzzle (letter pool)
3. Players submit words via AJAX requests
4. Words are validated to ensure they only use available letters
5. Points are awarded based on word length
6. Final scores are saved when the game is completed

## Assumptions

### Assumptions Made

1. **Word Validation**: Assumed that any word using only the provided letters is valid (no dictionary validation)
2. **Scoring System**: Assumed 1 point per letter in the word
3. **Game Flow**: Assumed one game per puzzle per user session
4. **User Experience**: Assumed real-time feedback via AJAX for word submission
5. **Data Persistence**: Assumed all games and scores should be permanently stored
6. **Authentication**: Assumed username/password registration is sufficient

## Future Enhancements

- Dictionary API integration for word validation
- Multiple difficulty levels
- Time-based challenges

## Troubleshooting

If you encounter issues:

1. Check Docker containers are running: `docker ps`
2. View logs: `make logs`
3. Clear cache: `make cc`
4. Rebuild containers: `make rebuild`

## License

This project uses the Symfony Docker Boilerplate as its foundation.
https://medium.com/@bordage.mickael/boilerplate-symfony-with-docker-and-make-simplify-your-life-developer-c55ce8e9d521

https://github.com/mb2dev/Symfony-Docker-Boilerplate
