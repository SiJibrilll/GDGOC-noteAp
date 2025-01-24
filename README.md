## API Endpoints

The application provides the following API endpoints:

### Authentication

- **Register a new user**

  - **Endpoint:** `POST /register`
  - **Description:** Registers a new user with the application.
  - **Request Body:**
    ```json
    {
      "name": "string",
      "email": "string",
      "password": "string", // min 8 characters
      "password_confirmation": "string"
    }
    ```
  - **Response:**
    - Response Body:
        ```json
        {
        "user" : {
            "name" : "username",
            "email" : "email@mail.com",
            "updated_at" : "2025-01-08T01:10:43.000000Z",
            "created_at" : "2025-01-08T01:10:43.000000Z",
            "id" : "1"
            },
        "token" : "1|e5RxFaJh2fyyNuBr4iIak2nHLgBnbzVJMWC4NE3Aaef577a7"
        }
        ```
    - **Error:** Returns validation errors.

- **Login**

  - **Endpoint:** `POST /login`
  - **Description:** Authenticates a user and returns an access token.
  - **Request Body:**
    ```json
    {
      "email": "string",
      "password": "string"
    }
    ```
  - **Response:**
    - Response Body:
        ```json
        {
        "user" : {
            "name" : "username",
            "email" : "email@mail.com",
            "email_verified_at" : null,
            "updated_at" : "2025-01-08T01:10:43.000000Z",
            "created_at" : "2025-01-08T01:10:43.000000Z",
            "id" : "1"
            },
        "token" : "1|e5RxFaJh2fyyNuBr4iIak2nHLgBnbzVJMWC4NE3Aaef577a7"
        }
        ```

    - **Error:** Returns authentication errors.

- **Logout**

  - **Endpoint:** `POST /logout`
  - **Description:** Logs out the authenticated user and invalidates the token.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Response:**
    - **Success:** Returns a logout confirmation message.
    - **Error:** Returns authentication errors.

### Notes

- **Get all notes**

  - **Endpoint:** `GET /notes`
  - **Description:** Retrieves a list of all notes for the authenticated user.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Response:**
    - Response Body:
        ```json
        {
        "notes": [
            {
              "note_id": 1,
              "user_id": 1,
              "title": "Hello world",
              "content": "This is my first note",
              "tags": ["work", "project"],
              "folder": "First Project",
              "is_pinned": 0,
              "created_at": "2025-01-08T01:11:07.000000Z",
              "updated_at": "2025-01-08T01:20:44.000000Z"
            }
          ]
        }
        ```
    - **Error:** Returns authentication errors.

- **Create a new note**

  - **Endpoint:** `POST /notes`
  - **Description:** Creates a new note for the authenticated user.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Request Body:**
    ```json
    {
      "title": "string",
      "content": "string",
      "tags": ["work", "project"], // nullable
      "folder": "First Project", // nullable
      "is_pinned" : "0" // Also accepts other boolean values
    }
    ```
    **Form Data Parameters Example**
    | Parameter    | Type           | Description                          |
    |--------------|----------------|--------------------------------------|
    | `title`      | string         | The title of the note.              |
    | `content`    | string         | The main content of the note.       |
    | `tags`       | array or null   | (Optional) Tags for the note.        |
    | `folder`     | string or null | (Optional) Folder name.               |
    | `is_pinned`  | boolean        |  Pin status of the note.  |
    | `files`      | file           | (Optional) File(s) to attach.        |

  - **Response:**
    - Response Body:
        ```json
        {
          "message": "Note created successfully",
          "note": {
            "title": "Hello world",
            "content": "This is my first note",
            "is_pinned": "0",
            "tags": ["work", "project"],
            "folder": "First Project",
            "user_id": 1,
            "updated_at": "2025-01-08T01:11:07.000000Z",
            "created_at": "2025-01-08T01:11:07.000000Z",
            "note_id": 1
          },
          "files": [
            {
              "id": 1,
              "path": "http://localhost:8000/storage/dASApMnTGzpjm2u1F698V1R2X1fvZClw.jpg",
              "note_id": 1,
              "created_at": "2025-01-22T02:44:48.000000Z",
              "updated_at": "2025-01-22T02:44:48.000000Z"
            }
          ]
        }

        ```
    - **Error:** Returns validation errors.

- **Get a specific note**

  - **Endpoint:** `GET /notes/{id}`
  - **Description:** Retrieves the details of a specific note by its ID. User's who have a shared access with at least view permission can also use this endpoint
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Response:**
    - Response Body:
        ```json
        {
          "note": {
            "note_id": 1,
            "user_id": 1,
            "title": "Hello world",
            "content": "This is my first note updated by jane",
            "tags": ["work", "project"],
            "folder": "First Project",
            "is_pinned": 0,
            "created_at": "2025-01-08T01:11:07.000000Z",
            "updated_at": "2025-01-08T01:20:44.000000Z"
          },
          "shared_with": [
            {
              "id": 2,
              "name": "jane",
              "email": "jane@gmail.com",
              "email_verified_at": null,
              "created_at": "2025-01-08T01:16:14.000000Z",
              "updated_at": "2025-01-08T01:16:14.000000Z",
              "pivot": {
                "note_id": 1,
                "shared_with_id": 2,
                "share_id": 2,
                "shared_at": "2025-01-08 01:19:19",
                "permission": "edit"
              }
            }
          ],
          "files": [
            {
              "id": 1,
              "path": "http://localhost:8000/storage/dASApMnTGzpjm2u1F698V1R2X1fvZClw.jpg",
              "note_id": 1,
              "created_at": "2025-01-22T02:44:48.000000Z",
              "updated_at": "2025-01-22T02:44:48.000000Z"
            }
          ]
        }

        ```
    - **Error:** Returns errors if the note is not found or access is unauthorized.

- **Update a note**

  - **Endpoint:** 
    - For raw json without images: `PUT /notes/{id}`
    - For form data submission with images: `POST /notes/{id}` **IMPORTANT!** send with the `_method=PUT` in the form data submission.
  - **Description:** Updates the specified note. User's who have a shared access with edit permission to this note can also use this endpoint to update a note.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Request Body:**
    ```json
    {
      "title": "string",
      "content": "string",
      "tags": ["work", "project"], // nullable
      "folder": "First Project", // nullable
      "is_pinned" : "0" // Also accepts other boolean values
    }
    ```
    **Form Data Parameters Example**
    | Parameter    | Type           | Description                          |
    |--------------|----------------|--------------------------------------|
    | `_method`    | string         | Must be PUT.                        |
    | `title`      | string         | The title of the note.              |
    | `content`    | string         | The main content of the note.       |
    | `tags`       | JSON or null   | (Optional) Tags for the note.        |
    | `folder`     | string or null | (Optional) Folder name.               |
    | `is_pinned`  | boolean        | Pin status of the note.  |
    | `files`      | file           | (Optional) File(s) to attach.        |

  - **Response:**
    - Response Body :
        ```json
        {
          "message": "Note updated successfully",
          "note": {
            "note_id": 1,
            "user_id": 1,
            "title": "Hello world",
            "content": "This is my first note updated",
            "tags": ["work", "project"],
            "folder": "First Project",
            "is_pinned": "0",
            "created_at": "2025-01-08T01:11:07.000000Z",
            "updated_at": "2025-01-08T01:15:34.000000Z"
          },
          "files": [
            {
              "id": 2,
              "path": "http://localhost:8000/storage/dASApMnTGzpjm2u1F698V1R2X1fvZClw.jpg",
              "note_id": 1,
              "created_at": "2025-01-22T02:44:48.000000Z",
              "updated_at": "2025-01-22T02:44:48.000000Z"
            }
          ]
        }

        ```
    - **Error:** Returns validation errors or unauthorized access errors.

- **Delete a note**

  - **Endpoint:** `DELETE /notes/{id}`
  - **Description:** Deletes the specified note.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Response:**
    - **Success:** Returns a deletion confirmation message.
    - **Error:** Returns errors if the note is not found or access is unauthorized.

### Sharing

- **Share a note**

  - **Endpoint:** `POST /notes/{note_id}/share`
  - **Description:** Shares a note with another user.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Request Body:**
    ```json
    {
      "shared_with": "string",
      "permission" : "view" // view/edit is available
    }
    ```
  - **Response:**
    - Response Body:
    ```json
    {
      "message": "Note shared successfully",
      "shared_note": {
        "shared_by_user_id": 1,
        "shared_with_id": 2,
        "note_id": 1,
        "permission": "edit",
        "shared_at": "2025-01-08T01:19:19.031759Z",
        "updated_at": "2025-01-08T01:19:19.000000Z",
        "created_at": "2025-01-08T01:19:19.000000Z",
        "share_id": 2
      }
    }

    ```
    - **Error:** Returns validation errors or unauthorized access errors.

- **View shared notes**
  - **Endpoint:** `GET /notes/shared`
  - **Description:** Retrieves a list of notes shared with the authenticated user.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Response:**
    - Response Body:
    ```json
    {
      "shared_notes": [
        {
          "note_id": 1,
          "user_id": 1,
          "title": "Hello world",
          "content": "This is my first note updated",
          "tags": ["work", "project"],
          "folder": "First Project",
          "is_pinned": 0,
          "created_at": "2025-01-08T01:11:07.000000Z",
          "updated_at": "2025-01-08T01:15:34.000000Z",
          "pivot": {
            "shared_with_id": 2,
            "note_id": 1,
            "shared_at": "2025-01-08 01:17:29",
            "permission": "view"
          }
        }
      ]
    }

    ```
    - **Error:** Returns authentication errors.

- **Revoke access to a shared note**

  - **Endpoint:** `DELETE /notes/{note_id}/share/{share_id}`
  - **Description:** Revokes a user's access to a shared note.
  - **Headers:**
    - `Authorization: Bearer {token}`
  - **Response:**
    - Response Body:
    ```json
    {
      "message": "Revoked successfully",
      "shared_with": [] // returns the remaining user still having access to the shared note
    }

    ```
    - **Error:** Returns errors if the note or user is not found, or access is unauthorized.

**Note:** Ensure that all requests requiring authentication include the `Authorization` header with a valid token.

