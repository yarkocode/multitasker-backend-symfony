# multitasker-backend-symfony

## Start up with docker

To start application in docker use

Build a fresh image `docker compose build --pull`

Start with docker compose `docker compose up`

Stop this when end watching `docker compose down --remove-orphans`

## Address after running

After running an application can be accessed by address http://localhost/api/

----

## API Routes

### Base URL

All API requests should be prefixed with the base URL:

```
http://localhost:PORT/api
```

*(Replace `PORT` with your actual server port)*

### Authentication

Most endpoints require authentication. Include the access token in the request header:

```http
Authorization: Bearer <your_access_token>
```

### Endpoints

#### Authentication

| Method | Endpoint         | Description                        |
|:-------|:-----------------|:-----------------------------------|
| `POST` | `/auth/register` | Register a new user                |
| `POST` | `/auth/login`    | Log in and receive an access token |

#### Projects

| Method  | Endpoint                | Description                                        |
|:--------|:------------------------|:---------------------------------------------------|
| `GET`   | `/projects`             | Get all projects where the user is a member        |
| `GET`   | `/projects/my`          | Get all projects created by the authenticated user |
| `POST`  | `/projects`             | Create a new project                               |
| `GET`   | `/projects/{projectId}` | Get details of a specific project                  |
| `PATCH` | `/projects/{projectId}` | Update a specific project                          |

#### Tasks (Project Specific)

Project-related task management endpoints.

| Method  | Endpoint                               | Description                             |
|:--------|:---------------------------------------|:----------------------------------------|
| `GET`   | `/projects/{projectId}/tasks`          | Get all tasks for a specific project    |
| `POST`  | `/projects/{projectId}/tasks`          | Create a new task within a project      |
| `PATCH` | `/projects/{projectId}/tasks/{taskId}` | Update a specific task within a project |

#### Tasks (Personal)

Personal task management endpoints.

| Method  | Endpoint          | Description                    |
|:--------|:------------------|:-------------------------------|
| `GET`   | `/tasks`          | Get all available tasks        |
| `POST`  | `/tasks`          | Create a new global task       |
| `GET`   | `/tasks/{taskId}` | Get details of a specific task |
| `PATCH` | `/tasks/{taskId}` | Update a specific task         |

---

### Path Parameters

| Parameter     | Description                          |
|:--------------|:-------------------------------------|
| `{projectId}` | The unique identifier of the project |
| `{taskId}`    | The unique identifier of the task    |

### Example Request (cURL)

Creating a new task within a project:

```bash
curl -X POST http://localhost:PORT/api/projects/123/tasks \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title": "New Task", "description": "Make something magnificent"}'
```
