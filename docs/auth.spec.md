# spec for user auth 

## register user api

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
