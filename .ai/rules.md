# Development Rules

Follow Laravel best practices.

## Controller
- Keep controllers thin
- Business logic should be minimal in controllers

## Model
- Use Laravel features when possible
- Prefer framework-native features over custom implementations

## API Response Format

All API responses must follow this structure:

{
  "success": true,
  "message": "optional message",
  "data": {}
}

For error responses:

{
  "success": false,
  "message": "error description",
  "errors": {}
}

## HTTP Status Codes

Use consistent status codes:

200 OK -> successful fetch/update  
201 Created -> successful create  
204 No Content -> successful delete (no body)  
401 Unauthorized -> authentication required  
403 Forbidden -> not allowed  
404 Not Found -> resource not found  
422 Unprocessable Entity -> validation errors

## Validation
- Use Form Request when validation grows
- Return 422 for validation errors

## UUID
- Use Laravel HasUuids trait
- Avoid custom UUID boot logic