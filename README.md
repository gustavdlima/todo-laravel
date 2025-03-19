# Todo-Cubo

Todo-Cubo is a comprehensive task management API built to demonstrate proficiency in Laravel and Docker.  This project enables users to create, edit, view, and delete tasks, with additional features for task status management, filtering, and commenting.

## Technology Stack

- **Backend Framework**: Laravel (latest version)
- **Programming Language**: PHP 8.x
- **Database**: PostgreSQL
- **Containerization**: Docker and Docker Compose
- **Authentication**: Laravel Sanctum

## Requirements

To run this project, you'll need:

- Docker and Docker Compose installed on your system
- Composer (for local development)
- Git (for version control)

## Installation and Setup

Follow these steps to get Todo-Cubo up and running on your local machine:

### 1. Clone the repository

```bash
git clone https://github.com/gustavdlima/todo-laravel.git
cd todo-cubo
```

### 2. Environment setup

Copy the example environment file and modify it according to your needs:

```bash
cp .env.example .env
```

Update the database connection details in the `.env` file:

```
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### 3. Start Docker containers

Build and start the Docker containers:

```bash
docker-compose up -d
```

### 4. Install dependencies

Enter the app container and install PHP dependencies:

```bash
docker-compose exec todo-cubo-app bash
cd todo-cubo
composer install
```

### 5. Generate application key

```bash
php artisan key:generate
```

### 6. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register a new user |
| POST | `/api/login` | Login user and get token |
| POST | `/api/logout` | Logout user (requires authentication) |
| GET | `/api/user` | Get authenticated user details |

### Tasks

All task endpoints require authentication (Sanctum token).

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/tasks` | Create a new task |
| PUT | `/api/tasks/{taskId}` | Update an existing task |
| GET | `/api/tasks/creation-date/{userId}` | Filter tasks by creation date |
| GET | `/api/tasks/status/{userId}` | Filter tasks by status |
| POST | `/api/tasks/{taskId}/comments` | Add a comment to a task |
| DELETE | `/api/tasks/{taskId}` | Delete a task |

## Request Examples

### Register a new user

**Request:**
```
POST /api/register
Content-Type: application/json

{
    "name": "Admin",
    "email": "admin@admin.com",
    "password": "admin123"
}
```

### Create a new task

Log in before use it!

**Request:**
```
POST /api/tasks
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz123456 ()

{
  "title": "Complete project ",
  "description": "Write documentation",
  "due_date": "2025-03-19",
  "user_id": 1
}
```

## Database Structure

### Main Tables

#### Users
- id (primary key)
- name
- email
- password
- created_at
- updated_at

#### Tasks
- id (primary key)
- title
- description
- status (pending, in_progress, completed)
- user_id (foreign key referencing users.id)
- created_at
- updated_at

#### Comments
- id (primary key)
- content
- task_id (foreign key referencing tasks.id)
- user_id (foreign key referencing users.id)
- created_at
- updated_at

### Relationships
- A user can have many tasks (one-to-many)
- A task belongs to a user (many-to-one)
- A task can have many comments (one-to-many)
- A comment belongs to a task (many-to-one)
- A comment belongs to a user (many-to-one)

## Authentication Details

Todo-Cubo uses Laravel Sanctum for API authentication.

- Tokens are created when a user logs in
- Tokens are stored in the `personal_access_tokens` table
- All API endpoints (except login and register) require authentication
- Authentication is performed via the `Authorization` header with a Bearer token

## Development Workflow (GitFlow)

This project follows the GitFlow workflow:

### Main Branches
- `main`: Production-ready code
- `develop`: Integration branch for features

## Testing

To run the tests for Todo-Cubo:

```bash
php artisan test
```

The project includes:
- Unit tests for entitie, services and repository

## License

This project is licensed under the MIT License - see the LICENSE file for details.
