# Yuki Technical Test

## Description

This is a technical test for Yuki, where we implement a REST API endpoint that can create Post and Find a Post.

## Execute project

### Requirement

- [docker](https://docs.docker.com/engine/install/)
- Port 8080 available if not available change in `.env` file with `NGINX_HOST_HTTP_PORT=8081`

### Execute

```
make onboard
```

### Tests

```
make phpunit
```

### Docker Stop

`make docker-stop` Docker stop all the containers

`make docker-down` Docker stop and remove all the containers

## Decisions

- Framework: Symfony 7.4
- Database: PostgreSQL

## API

For creating API Symfony provides flexibility to create a Domain Driven Design (DDD) structure.
We have created a simple API with the following endpoints:

- `POST /posts` - Create a new post
- `GET /posts/:id` - Find a post by ID
- `POST /author` - Create a new author
- `PUT /author/:id` - Update an author by ID

### API Design

For had a functional API we have used the following design principles:

- **RESTful**: The API follows REST principles, using standard HTTP methods (GET, POST, PUT) and status codes.
- **Resource-Oriented**: The API is designed around resources (posts, authors) and their representations.

How to Interact with the API:

* Before to create a Post, you need to create an Author, because a Post must have an Author.
* When you update an Author, all the Posts of that Author will be updated with the new Author data.

**Why had author name on post and not do a relation?**
Because we want to have the author name on the post for easy access and to avoid additional database queries when
fetching posts.
This simplifies the data retrieval process and improves performance, especially when displaying posts with their
authors.

### API Documentation and Examples

Method: `POST /author`
**Payload:**

```json
{
    "name": "John",
    "email": "Doe"
}
```

Response: 201

```json
{
    "id": "0197e952-9dd7-72bb-b6bb-46f88fe4f4bd",
    "name": "John",
    "email": "Doe"
}
```

Method: `POST /posts`

Payload:

```json
{
    "title": "My First Post",
    "description": "This is a description of my first post.",
    "content": "This is the content of my first post.",
    "authorId": "0197e952-9dd7-72bb-b6bb-46f88fe4f4bd"
}
```
Response: 201

```json
{
    "id": "f1c8b2d3-4e5f-4a6b-8c9d-0e1f2g3h4i5j",
    "title": "My First Post",
    "description": "This is a description of my first post.",
    "content": "This is the content of my first post.",
    "authorId": "0197e952-9dd7-72bb-b6bb-46f88fe4f4bd",
    "authorName": "John Doe"
}
```

Method: `GET /posts/:id`
**Response:** 

```json
{
    "id": "f1c8b2d3-4e5f-4a6b-8c9d-0e1f2g3h4i5j",
    "title": "My First Post",
    "description": "This is a description of my first post.",
    "content": "This is the content of my first post.",
    "authorId": "0197e952-9dd7-72bb-b6bb-46f88fe4f4bd",
    "authorName": "John Doe"
}
```

Method: `PUT /author/:id`
**Payload:**

```json
{
    "name": "John",
    "surname": "Does"
}
```
Response: 204

## How the Author Update Works

When you update an Author, all the Posts of that Author will be updated with the new Author data.
We have Two Domain Events that trigger the update of the posts:
- `AuthorNameUpdatedDomainEvent`
- `AuthorSurnameUpdatedDomainEvent`

This handling is done by:
- `UpdatePostAuthorNameOnAuthorNameChange`
- `UpdatePostAuthorNameOnAuthorSurnameChange`

We search all the posts of the author and update the author name and surname on the post.
