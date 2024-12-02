Login API

This repository provides a Login API for user authentication. The API allows users to securely log in and access protected resources by generating a JSON Web Token (JWT).


---

Endpoint

URL: /api/login

Method: POST

Description: Authenticates a user and generates an access token.



---

Request Format

Headers

Content-Type: application/json


Request Body

The following fields are required in the request body:

{
  "username": "your_username",
  "password": "your_password"
}


---

Response Format

Success Response

If the login is successful, the API returns a JSON response with a token:

{
  "success": true,
  "message": "Login successful.",
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}

Error Response

If the login fails, the API returns an error message:

Invalid Credentials:

{
  "success": false,
  "message": "Invalid username or password."
}

Missing Fields:

{
  "success": false,
  "message": "Username and password are required."
}



---

Error Codes

400 Bad Request: Missing or invalid fields in the request.

401 Unauthorized: Authentication failed due to incorrect credentials.

500 Internal Server Error: An error occurred on the server.



---

Usage Notes

Always use HTTPS for secure communication.

The token returned is a JSON Web Token (JWT). Use it to access protected resources by including it in the Authorization header as Bearer <token>.

Ensure the password is hashed securely on the server side.



---

Contributing

Feel free to submit issues or pull requests for improvements.


---

License

This project is licensed under the MIT License.

