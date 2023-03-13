# Introduction

It is a "Kanban Board" application where you can manage your tasks and projects
more effectively. This application should provide a Kanban board where users can continuously update their tasks and projects and show the processes of the tasks (priority, completed, in progress,etc.). Users should be able to drag and drop tasks from this dashboard to change their processes.

## Main features of the application:

A homepage where users can see the Kanban dashboard. This page contain a Kanban board with a list of tasks created or assigned by the user and their progress (priority, completed, in progress, etc.). Users able to change their processes by dragging and dropping tasks on this board.

## Installation

Clone this repo

```bash
cd kanban-board/kanban-react
npm install
npm run dev

cd kanban-board/kanban-app
php artisan key:generate
composer install
php artisan passport:install
php artisan serve

NOTE: Please update DB configuration in .env (SQL FIle : kanban-board/kanban-app/storage/kanban.sql)
```

## Authorization

All API requests require the use of a generated Access token. You can find your Access token, or generate a new one, by navigating to the /login endpoint

To authenticate an API request, you should provide your API key in the `Authorization` header.


```http
POST /api/auth/login
```

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `email` | `string` | **Required**. Your registered Email |
| `password` | `string` | **Required**. Your password |

```http
POST /api/auth/register
```

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `name` | `string` | **Required**. Your Name |
| `email` | `string` | **Required**. Your Email |
| `password` | `string` | **Required**. Your password |

```http
GET /api/task
```

```http
POST /api/task/create
```

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `name` | `string` | **Required**. Task Name |
| `description` | `text` | **Required**. Task Description |
| `status` | `string` | **Required**. Task Status["todo","inprogress","done"] |

```http
POST /api/task/update
```

| Parameter | Type | Description |
| :--- | :--- | :--- |
| `task_id` | `string` | **Required**. Task ID |
| `status` | `string` | **Required**. Task's New Status["todo","inprogress","done"] |

## Responses

All API endpoints return the JSON representation of the resources created or edited, returns a JSON response in the following format:

```javascript
{
  "success" : bool,
  "message" : string,
  "data"    : array
}
```

The `message` attribute contains a message commonly used to indicate errors or, in the case of deleting a resource, success that the resource was properly deleted.

The `success` attribute describes if the transaction was successful or not.

The `data` attribute contains any other metadata associated with the response. This will be an escaped string containing JSON data.

## Status Codes

It returns the following status codes in its API:

| Status Code | Description |
| :--- | :--- |
| 200 | `OK` |
| 400 | `BAD REQUEST` |
| 404 | `NOT FOUND` |
| 500 | `INTERNAL SERVER ERROR` |

