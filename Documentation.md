# Snippets API Documentation

## Introduction
This documentation provides a complete guide for using the Snippets API. All requests should be sent as JSON and responses will be in JSON format.

## Base URL
`https://yourdomain.com/api`

## Authentication
- All endpoints require JWT authentication except `/auth/register` and `/auth/login`
- Include token in header: `Authorization: Bearer {your_token}`

---

## Authentication Endpoints

### Register New User
**Endpoint:** `POST /auth/register`
**Request:**
```json
{
  "username": "string",
  "password": "string"
}
```

**Success Response (201):**
```json
{
  "user": {
    "id": "integer",
    "username": "string"
  },
  "token": "string"
}
```

### Login
**Endpoint:** `POST /auth/login`
**Request:**
```json
{
  "username": "string",
  "password": "string"
}
```

**Success Response (200):**
```json
{
  "token": "string"
}
```

### Get Current User
**Endpoint:** `GET /auth/me`

**Success Response (200):**
```json
{
  "id": "integer",
  "username": "string"
}
```

---

## Snippets Endpoints

### Get All Snippets
**Endpoint:** `GET /snippets`

**Query Parameters:**
- language (optional): Filter by language
- perpage (optional): Items per page (default: 5)

**Success Response (200):**
```json
{
  "data": [
    {
      "id": "integer",
      "title": "string",
      "code": "string",
      "language": "string",
      "user": {
        "id": "integer",
        "username": "string"
      },
      "comments_count": "integer",
      "likes_count": "integer",
      "tags": ["string"],
      "created_at": "timestamp"
    }
  ],
  "links": {
    "first": "string",
    "last": "string",
    "prev": "string|null",
    "next": "string|null"
  },
  "meta": {
    "current_page": "integer",
    "total": "integer"
  }
}
```

### Create Snippet
**Endpoint:** `POST /snippets`

**Request:**
```json
{
  "title": "string",
  "code": "string",
  "language": "string",
  "tags": "string" // comma-separated
}
```

**Success Response (201):**
```json
{
  "id": "integer",
  "title": "string",
  "code": "string",
  "language": "string",
  "tags": ["string"],
  "created_at": "timestamp"
}
```

---

## Comment Endpoints

### Add Comment
**Endpoint:** `POST /snippets/{id}/comments`

**Request:**
```json
{
  "comment": "string"
}
```

**Success Response (201):**
```json
{
  "message": "string",
  "comment": {
    "id": "integer",
    "comment": "string",
    "created_at": "timestamp"
  }
}
```

---

## Like Endpoints

### Like Snippet
**Endpoint:** `POST /snippets/{id}/like`

**Success Response (201):**
```json
{
  "message": "string"
}
```

**Error Response (400):**
```json
{
  "message": "You have already liked this snippet."
}
```

---

### Enums

**Supported Languages**
- php
- javascript
- python
- java
- csharp
- ruby
- go

---

### Error Codes
| Code | Description |
|---|---|
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |