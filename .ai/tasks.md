# Development Roadmap

## Phase 1 — API Consistency ✅ DONE

1. ✅ Standardize API response format
   Make all endpoints consistently return:

- success
- message
- data

2. ✅ Fix HTTP status code behavior
   Use:

- 201 for create
- 204 for delete
- 200 for fetch/update

3. ✅ Update UUID implementation
   Use Laravel HasUuids trait in Todo model.

---

## Phase 2 — Authentication ✅ DONE

4. ✅ Add authentication with Sanctum
   Implement endpoints:

- register
- login
- logout
- user

Verify token flow in Postman.

5. ✅ Protect Todo routes
   Require authentication for all Todo CRUD endpoints.

---

## Phase 3 — API Improvements ✅ DONE

6. ✅ Add pagination and filtering to GET /todos

Support query parameters:

- page
- completed
- search

Example:

GET /todos?page=1
GET /todos?completed=true
GET /todos?search=meeting

---

## Phase 4 — Quality

7. Add API tests

Create tests for:

- CRUD success
- validation errors (422)
- authentication protection (401)
- pagination and filters

---

## Phase 5 — Documentation

8. Add API documentation

Use tools like:

- Scribe
- Swagger

Generate documentation once endpoints are stable.
