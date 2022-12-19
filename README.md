# PHP / JS Code Exercise

## Overview

This is a 2 part offline exercise to demonstrate general knowledge of PHP and JS.

- Create a new personal GitHub repo called `cdc-code-exercise`
- You can **[download this repo](https://github.com/PennantConsulting/php-js-code-exercise/archive/refs/heads/main.zip)** as a template, but don't fork it.
- There's no time limit, but expectation is work should not extend past 2 hours.

## Part 1: PHP Backend exercise

For this PHP backend exercise:

- PHP code should lint but doesn't need to execute on a server
- No working database expected, but credit for a `CREATE TABLE` sql file

An existing app needs to support simple messages, and a REST API to create, read, update, and delete message records in an underlying MySQL database.

### Message API

API routes that are needed:

| HTTP Method | Path | Description |
| ----------- | ---- | ----------- |
| `GET`       | `/api/message` | Get a single message |
| `POST`      | `/api/message` | Create a single message |
| `PUT`       | `/api/message` | Update a single message |
| `DELETE`    | `/api/message` | Delete a single message |

### Message Schema

Each message has the following fields:

| Field       | Description     |
|-------------| ----------------|
| **title**   | a string up to 80 |
| **body**    | a string up to 50,000 |
| **from**    | a large integer matching the user that created the message (*the author*) |
| **to**      | a large integer matching the user that should receive the message |
| **created** | timestamp of when the message was created |
| **updated** | timestamp of when the message was last updated |

Business requirements for messages:

1. Every message **title** must be unique; no 2 messages can have the same title
2. Message **body** must be changed when a message is updated
3. Message **from** (*author*) can never be updated
4. Every message must have a **from**
5. Messages must support creation in any timezone; messages will be create in 3+ timezones

## Part 2: Frontend page / JS

For this JS frontend exercise:

- Goal is to have a simple page with JS
- Included here is a simple web server:
  ```bash
  npm i
  npm start
  ```
  Docroot is `/public`

Create a simple HTML page with JS that requests a mock response from your API, and displays a sample message on the page. You can use this simple JSON below for the mock message, or create your own. Save as a flat file and request it via an AJAX GET request, as if it were an API request.

[message.json](public/message.json)

```js
{
    "id": 9236103,
    "title": "Staging outage planned",
    "from": 345,
    "to": 312,
    "body": "Staging servers will be down for 30 minutes to apply patches.",
    "created": "2022-09-19T17:53:49-04:00",
    "updated": "2022-09-19T17:53:49-04:00"
}
```

Business requirements for page:

1. Message **title** should be 24px
2. Dates should be formatted into a friendly format for the end user's local times
