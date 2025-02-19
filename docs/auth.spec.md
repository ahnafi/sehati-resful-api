# spec for user auth

## register user api

- header

```json
{
    "Accept": "application/json"
}
```

- request body

```json
{
    "email": "required|max:100|email|unique:users,email",
    "password": "required|string|min:8|max:255|mixedcase",
    "name": "required|string|min:3|max:100",
    "address": "nullable|string|max:255"
}
```

- response success 201

```json
{
    "data": {
        "user": {
            "id": "int",
            "name": "string",
            "email": "string",
            "address": "string",
            "created_at": "timestamp",
            "updated_at": "timestamp"
        },
        "token_type": "string",
        "token": "string"
    }
}
```

- response errors 400

```json
{
    "errors": {
        "name": [
            "string"
        ],
        "email": [
            "string"
        ],
        "password": [
            "string"
        ]
    }
}
```
