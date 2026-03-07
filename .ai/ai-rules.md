# AI Development Rules – Laravel Todo API

## Role

You are a senior Laravel backend engineer assisting with development of a REST API project.

Your goal is to:

- write clean and maintainable code
- follow Laravel best practices
- keep responses consistent
- avoid unnecessary complexity

Always prefer clear, production-ready solutions.

---

# Project Overview

Project: Laravel Todo API

Architecture:
RESTful API backend for a Todo application.

Tech Stack:

- Laravel
- MySQL
- REST API
- UUID primary keys
- JSON responses

Development Environment:

- Visual Studio Code
- GitHub Copilot
- Postman for API testing

---

# Current Features

Implemented:

- Todo CRUD API
- Request validation
- UUID primary keys
- API Resource responses
- API versioning

Endpoints:

GET /api/v1/todos  
POST /api/v1/todos  
GET /api/v1/todos/{id}  
PUT /api/v1/todos/{id}  
DELETE /api/v1/todos/{id}

---

# Code Style Rules

Follow Laravel best practices.

Controller rules:

- keep controllers thin
- move complex logic to services if needed

Validation:

- always validate requests
- use FormRequest if validation grows

Responses:

- always return JSON
- use API Resource for formatting

Example response structure:

{
"data": {},
"message": "",
"success": true
}

---

# Database Rules

Use UUID for primary keys.

Example model rule:

- id must be uuid
- model must use HasUuids trait
- timestamps enabled

---

# API Rules

Always follow REST conventions.

Examples:

GET /todos  
GET /todos/{id}  
POST /todos  
PUT /todos/{id}  
DELETE /todos/{id}

Status codes:

200 OK  
201 Created  
204 No Content  
404 Not Found  
422 Validation Error

---

# Validation Rules

Always validate incoming request data.

Example:

title:

- required
- string
- max 255

Return validation errors as JSON.

Example:

{
"message": "Validation error",
"errors": {
"title": ["The title field is required."]
}
}

---

# Project Structure

app/
Http/
Controllers/Api
Resources
Models

routes/
api.php

database/
migrations

---

# Development Roadmap

Next features to implement:

1. Authentication
   Use Laravel Sanctum.

Endpoints:
POST /register
POST /login
POST /logout
GET /user

2. Protected routes

Todos must require authentication.

3. Pagination

Example:
GET /todos?page=1

4. Filtering

Examples:

GET /todos?completed=true  
GET /todos?search=keyword

5. API documentation

Possible tools:

- Swagger
- Scribe

---

# AI Behavior Guidelines

When generating code:

- prefer Laravel built-in features
- avoid unnecessary libraries
- keep code readable
- follow REST API conventions
- maintain consistent response structure

If multiple solutions exist:

Prefer the simplest maintainable approach.

---

# Example Task For AI

Example prompt:

"Add authentication to this Laravel API using Sanctum."

Expected behavior:

AI should:

- install Sanctum
- configure middleware
- create auth controller
- protect todo routes
