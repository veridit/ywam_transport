# YWAM Transport System

A Django-based vehicle reservation system for YWAM, converted from a 2010 PHP project.

## Features

- Vehicle reservation management
- Driver management
- Department cross-charging
- Service scheduling
- Trip tracking
- Email notifications

## Development Setup

### Prerequisites

- Docker
- Docker Compose
- Git

### Getting Started

1. Clone the repository:
```bash
git clone <repository-url>
cd transport
```

2. Generate initial environment file:
```bash
./devops/generate-initial-env-with-secrets.sh
```

3. Edit `.env` file and set:
- `OLD_MYSQL_PASSWORD` for data migration
- Any other environment variables if needed

4. Start development environment from previous production setup:
```bash
./devops/start-from-scratch-again.sh
```

This script will:
- Download latest MySQL data (if needed)
- Reset Docker volumes
- Start database services
- Migrate data from MySQL to PostgreSQL
- Run Django migrations
- Create superuser
- Start development server

### Development Workflow

- Django admin interface: http://localhost:8000/admin/
- Main application: http://localhost:8000/

### Technology Stack

- Django 3.2
- PostgreSQL
- Docker
- HTMX
- Bootstrap 5
- Unfold Admin

### Project Structure

- `transport/` - Main Django app
- `old/` - Original PHP codebase
- `devops/` - Development and deployment tools
- `static/` - Static files
- `templates/` - HTML templates

### Contributing

1. Create a feature branch
2. Make changes
3. Run tests
4. Submit pull request

### Running Tests

```bash
docker compose exec web python manage.py test
```

### Database Migrations

Generate migrations:
```bash
docker compose exec web python manage.py makemigrations
```

Apply migrations:
```bash
docker compose exec web python manage.py migrate
```

### Conventions

- Use Django best practices
- Follow PEP 8 style guide
- Write tests for new features
- Document code changes
