# Laravel Community Boilerplate — AI Agent Build Specification

## 1. Project Vision

Build a **production-ready, community-focused Laravel boilerplate** that provides a clean foundation for SaaS applications, admin panels, CRMs, ERPs, internal tools, and API-driven applications.

The boilerplate should prioritize:

- Modern Laravel conventions
- Simple and maintainable architecture
- Clear separation of responsibilities
- Reusable business logic
- Developer experience
- API-first compatibility
- Livewire-based web UI
- Strong testing and code quality
- Security by default
- Avoiding unnecessary abstractions

### Core architectural principle

> **Use the simplest abstraction that solves the problem.**

Do not force Service Classes, Repositories, DTOs, Query Object or other patterns into every feature merely for architectural consistency.

---

# 2. Technology Direction

Use the current stable Laravel ecosystem available when implementation begins.

Target stack:

- Laravel
- PHP
- MySQL
- Livewire for the web/admin interface
- Tailwind CSS or the currently preferred Laravel-compatible UI stack
- Laravel Sanctum for API authentication
- Pest for testing
- Laravel Pint for code formatting
- PHPStan/Larastan for static analysis
- Vite for frontend assets
- OpenAPI/Swagger-compatible API documentation

The implementation should follow official Laravel conventions wherever possible instead of recreating framework functionality.

---

# 3. Architecture Philosophy

The application should have multiple entry points into the same application/business logic.

```text
                         ┌──────────────┐
                         │   Livewire   │
                         └──────┬───────┘
                                │
                         ┌──────▼───────┐
                         │    Actions   │
                         └──────┬───────┘
                                │
                    ┌───────────┼───────────┐
                    ▼           ▼           ▼
                 Models      Services      Jobs
                    │           │
                    │           ▼
                    │      External APIs
                    │
                    ▼
                 Database


API
 │
 ▼
Controllers
 │
 ▼
Actions
 │
 ▼
Models
```

DTOs should be introduced at meaningful application boundaries.

Query object should only be introduced when they provide meaningful abstraction or handle complex query logic.

Repositories should only be introduced when they provide meaningful abstraction or handle genuinely complex data-access logic.

---

# 4. Architectural Rules

## 4.1 Actions — Primary Business Operation Layer

Actions are the default abstraction for a meaningful business operation.

Examples:

```text
CreateUser
UpdateUser
DeleteUser
CreateRole
AssignRole
LoginUser
RegisterUser
ChangePassword
SendPasswordReset
```

An Action should generally:

- Represent one meaningful operation
- Have one clear responsibility
- Be reusable from Livewire, API controllers, jobs, commands, etc.
- Avoid HTTP-specific concerns
- Avoid returning HTTP responses
- Be easy to test independently

Example:

```php
final class CreateUser
{
    public function execute(CreateUserData $data): User
    {
        return User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
    }
}
```

---

# 5. Service Classes

Services are allowed but should not be mandatory.

Use a Service when it is responsible for orchestrating a complex workflow involving multiple operations.

Example:

```text
CheckoutService
    ├── CreateOrder
    ├── ChargePayment
    ├── UpdateInventory
    └── SendOrderConfirmation
```

Do not create services such as:

```text
UserService
RoleService
PermissionService
```

only because those models exist.

Avoid:

```text
Controller
    ↓
Service
    ↓
Action
    ↓
Repository
    ↓
Model
```

when the Service and Repository add no meaningful value.

---

# 6. Repository Pattern

Repositories are optional.

Do NOT create a repository for every model by default.

Avoid unnecessary wrappers such as:

```php
$userRepository->find($id);
```

when this adds no value over:

```php
User::find($id);
```

Repositories should be used for cases such as:

- Complex reporting queries
- Complex data retrieval
- Multiple data sources
- External persistence abstractions
- A genuine need to isolate data-access complexity

Example:

```text
Repositories/
└── Reports/
    └── RevenueReportRepository.php
```

The project documentation must explicitly explain why repositories are optional.

---

# 7. DTOs

Use DTOs for meaningful input/output boundaries.

DTOs should:

- Be immutable where appropriate
- Use typed properties
- Represent meaningful application data
- Avoid simply duplicating every Eloquent model
- Keep business operations independent from HTTP requests

Example:

```php
final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?string $phone = null,
    ) {}
}
```

Typical flow:

```text
HTTP Request
    ↓
Validation
    ↓
DTO
    ↓
Action
    ↓
Model
```

---

# 8. Web Application

Use Livewire for the primary web/admin interface.

The boilerplate should include:

```text
Authentication
Dashboard
Users
Roles
Permissions
Profile
Settings
Notifications
```

Livewire components should remain focused.

Do not put large business workflows directly inside Livewire components.

Instead:

```text
Livewire Component
       ↓
Form Validation
       ↓
DTO
       ↓
Action
```

---

# 9. Authentication

Provide a production-ready authentication foundation.

Include:

- Login
- Logout
- Registration
- Email verification
- Forgot password
- Reset password
- Remember me
- Profile management
- Password change
- Session/security management where appropriate

Use official Laravel authentication tooling where possible.

Do not reinvent framework authentication functionality unnecessarily.

---

# 10. Authorization / RBAC

Provide a complete role and permission system.

Core relationship:

```text
User
 │
 ├── Roles
 │     │
 │     └── Permissions
 │
 └── Optional direct permissions
```

Authorization should use Laravel's authorization mechanisms.

Examples:

```php
$user->can('users.create');
```

and policies such as:

```text
UserPolicy
RolePolicy
PermissionPolicy
```

Avoid hard-coded checks such as:

```php
if ($user->role === 'admin')
```

throughout the application.

---

# 11. User Management

Provide a complete user-management module.

Features:

- User listing
- Search
- Filtering
- Sorting
- Pagination
- Create user
- View user
- Update user
- Delete user
- Restore user if soft deletes are enabled
- Assign roles
- Manage permissions
- Activate/deactivate user
- Profile management

Use:

```text
Form Request
    ↓
DTO
    ↓
Action
    ↓
Model
```

where appropriate.

---

# 12. Role Management

Provide:

- Role listing
- Create role
- Update role
- Delete role
- Permission assignment
- Role permissions display
- Role-based authorization

Example permissions:

```text
users.view
users.create
users.update
users.delete

roles.view
roles.create
roles.update
roles.delete

permissions.view
permissions.create
permissions.update
permissions.delete
```

Permission naming should follow a consistent convention.

---

# 13. Dashboard

Provide a simple but useful dashboard.

Include examples such as:

- Total users
- Active users
- Roles
- Recent users
- Recent activity
- Basic system statistics

Keep the dashboard generic and easy to customize.

Do not build business-specific analytics into the core boilerplate.

---

# 14. Settings

Provide a basic settings architecture.

Potential categories:

```text
General
Application
Localization
Notification
Security
```

Settings should be designed so future applications can extend them.

---

# 15. Notifications

Provide a basic notification foundation.

Support Laravel's notification system.

Include an example notification and demonstrate:

- Database notifications
- Notification listing
- Mark as read
- Mark all as read

Email notification support should be easy to add.

---

# 16. REST API

Provide a complete sample API using versioning.

Base path:

```text
/api/v1
```

Structure:

```text
app/
└── Http/
    ├── Controllers/
    │   └── Api/
    │       └── V1/
    ├── Requests/
    └── Resources/
```

Example endpoints:

```text
POST   /api/v1/auth/login
POST   /api/v1/auth/register
POST   /api/v1/auth/logout
GET    /api/v1/auth/me

GET    /api/v1/users
POST   /api/v1/users
GET    /api/v1/users/{user}
PUT    /api/v1/users/{user}
DELETE /api/v1/users/{user}

GET    /api/v1/roles
POST   /api/v1/roles
GET    /api/v1/roles/{role}
PUT    /api/v1/roles/{role}
DELETE /api/v1/roles/{role}

GET    /api/v1/permissions
```

The API must reuse the same application Actions used by the web interface where appropriate.

---

# 17. API Response Standard

Use a consistent response format.

Success:

```json
{
    "success": true,
    "message": "User created successfully.",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

Validation error:

```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

The exact implementation should remain compatible with Laravel's standard HTTP semantics.

Use API Resources rather than returning Eloquent models directly.

---

# 18. API Features

The sample API should demonstrate:

## Pagination

```text
?page=1&per_page=20
```

## Search

```text
?search=john
```

## Sorting

```text
?sort=-created_at
```

## Filtering

```text
?status=active
```

## Relationships / Includes

Where appropriate:

```text
?include=roles
```

Do not build an unnecessarily complex generic query-builder abstraction.

Keep the implementation understandable and extensible.

---

# 19. API Authentication

Use Laravel Sanctum unless a future requirement specifically requires OAuth2.

Provide:

- Token creation
- Token revocation
- Authenticated API requests
- Current-user endpoint
- Protected API routes
- Rate limiting

Never expose secrets or tokens in logs or API responses.

---

# 20. API Documentation

Provide OpenAPI/Swagger-compatible API documentation.

Document:

- Authentication
- Users
- Roles
- Permissions
- Request parameters
- Request bodies
- Response formats
- Validation errors
- HTTP status codes

The documentation should be accessible from a clear route such as:

```text
/docs/api
```

if the selected documentation package supports this approach.

---

# 21. Validation

Use Laravel Form Requests for HTTP validation.

Avoid validation logic inside controllers.

Example:

```text
CreateUserRequest
UpdateUserRequest
LoginRequest
CreateRoleRequest
UpdateRoleRequest
```

Validation should be reusable and clearly organized.

---

# 22. Policies

Use Laravel Policies for authorization.

Example:

```text
UserPolicy
RolePolicy
PermissionPolicy
```

Controllers and Livewire components should not contain duplicated authorization rules.

---

# 23. Jobs

Provide examples of queued jobs.

Potential examples:

```text
SendWelcomeEmail
SendUserNotification
ProcessReport
```

Jobs should be used for slow/background work.

---

# 24. Events and Listeners

Provide a small example demonstrating Laravel events.

Example:

```text
UserRegistered
    ↓
SendWelcomeNotification
```

Do not overuse events where a direct Action call is clearer.

---

# 25. Database

Use:

- Migrations
- Factories
- Seeders
- Foreign keys
- Appropriate indexes
- Soft deletes where justified
- Timestamps
- UUID/ULID strategy if selected for the project

The database structure should be production-oriented but easy to modify.

---

# 26. Testing

Use Pest.

Test at multiple levels.

## Feature tests

```text
Authentication
Users
Roles
Permissions
API
Authorization
```

## Unit tests

Focus on:

```text
Actions
Services
Complex business logic
```

Important Actions must have tests.

Example:

```php
it('creates a user', function () {
    $user = app(CreateUser::class)->execute(
        new CreateUserData(
            name: 'John Doe',
            email: 'john@example.com',
            password: 'password',
        )
    );

    expect($user)->toBeInstanceOf(User::class);
});
```

---

# 27. Code Quality

Include:

- Laravel Pint
- PHPStan/Larastan
- Pest
- Consistent naming
- Strict typing where appropriate
- PHPDoc only where it adds value
- Clean dependency injection
- Small focused classes
- No unnecessary abstraction

Recommended CI pipeline:

```text
Install dependencies
    ↓
Lint
    ↓
Static analysis
    ↓
Tests
```

---

# 28. Security

Security must be a first-class concern.

Include:

- CSRF protection
- Authentication throttling
- API rate limiting
- Authorization policies
- Secure password hashing
- Mass-assignment protection
- Validation
- Secure file handling where applicable
- No secrets committed to Git
- Safe error responses
- Secure session configuration
- Proper CORS configuration
- Production environment guidance

---

# 29. Project Structure

Recommended structure:

```text
app/
├── Actions/
├── DTOs/
├── Events/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── V1/
│   ├── Requests/
│   └── Resources/
├── Jobs/
├── Livewire/
├── Models/
├── Notifications/
├── Policies/
├── Repositories/
├── Services/
└── Support/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── views/
└── ...

routes/
├── web.php
├── api.php
└── ...

tests/
├── Feature/
└── Unit/
```

The `Repositories` and `Services` directories should not be populated artificially.

---

# 30. What NOT to Do

The AI agent must avoid these architectural mistakes.

## Do not create unnecessary layers

Bad:

```text
Controller
 ↓
Service
 ↓
Repository
 ↓
Model
```

for simple CRUD.

## Do not create a repository for every model.

## Do not put business logic inside controllers.

## Do not put large business workflows inside Livewire components.

## Do not duplicate business logic between API and Livewire.

## Do not return raw Eloquent models from API endpoints.

## Do not create generic abstractions before they are needed.

## Do not over-engineer the boilerplate.

## Do not replace Laravel-native functionality without a strong reason.

---

# 31. Preferred Request Flow

## Web / Livewire

```text
User
 ↓
Livewire Component
 ↓
Validation
 ↓
DTO
 ↓
Action
 ↓
Model / Service
 ↓
Database
```

## API

```text
Client
 ↓
API Route
 ↓
Controller
 ↓
Form Request
 ↓
DTO
 ↓
Action
 ↓
Model / Service
 ↓
API Resource
 ↓
JSON Response
```

## Background job

```text
Action / Event
 ↓
Job
 ↓
Service / External API
 ↓
Database
```

---

# 32. Example Feature Flow

For user creation:

```text
Create User

Livewire
    ↓
CreateUserRequest/validation equivalent
    ↓
CreateUserData
    ↓
CreateUser Action
    ↓
User Model
    ↓
Database
```

API:

```text
POST /api/v1/users
    ↓
CreateUserRequest
    ↓
CreateUserData
    ↓
CreateUser Action
    ↓
User Model
    ↓
UserResource
    ↓
JSON
```

The business operation must not be duplicated.

---

# 33. AI Agent Implementation Rules

The AI coding agent implementing this project must follow these rules.

## Before coding

1. Inspect the existing project.
2. Identify the Laravel version.
3. Identify PHP version.
4. Check installed dependencies.
5. Check existing authentication.
6. Check existing frontend stack.
7. Check database configuration.
8. Avoid replacing existing functionality unless required.

## During coding

1. Follow Laravel conventions first.
2. Prefer simple solutions.
3. Reuse existing framework features.
4. Create Actions for meaningful business operations.
5. Use DTOs at meaningful boundaries.
6. Use Services only for orchestration/complex workflows.
7. Use Repositories only when justified.
8. Keep controllers thin.
9. Keep Livewire components thin.
10. Keep API and web logic reusable.
11. Add tests for important behavior.
12. Do not introduce dependencies without justification.

## After coding

1. Run formatting.
2. Run static analysis.
3. Run tests.
4. Fix failures.
5. Verify migrations.
6. Verify seeders.
7. Verify API endpoints.
8. Verify authentication.
9. Verify authorization.
10. Update documentation.

---

# 34. Documentation

The repository must include a strong README.

README sections:

```text
Project Overview
Features
Requirements
Installation
Environment Configuration
Database Setup
Authentication
Roles & Permissions
Livewire
API
API Authentication
API Documentation
Architecture
Actions
DTOs
Services
Repositories
Testing
Code Quality
Deployment
Contributing
License
```

Also include:

```text
CONTRIBUTING.md
CHANGELOG.md
LICENSE
```

where appropriate.

---

# 35. Community-Friendly Principles

The boilerplate should be:

- Easy to understand
- Easy to install
- Easy to customize
- Easy to extend
- Well documented
- Tested
- Secure
- Opinionated but not restrictive
- Close to Laravel conventions

The project should teach developers good architecture rather than hide everything behind abstractions.

---

# 36. Final Architecture Decision

The final architectural hierarchy is:

```text
DEFAULT
───────
Laravel + Eloquent
    ↓
Actions
    ↓
DTOs where useful


WHEN NEEDED
───────────
Services
    ↓
Complex workflow orchestration


WHEN JUSTIFIED
──────────────
Repositories
    ↓
Complex data-access abstraction
```

### Priority

```text
1. Laravel-native conventions
2. Actions
3. DTOs
4. Policies / Requests / Resources
5. Jobs / Events
6. Services when workflows become complex
7. Repositories only when they provide real value
```

---

# 37. Definition of Done

The boilerplate is considered complete when it provides:

- [ ] Authentication
- [ ] User management
- [ ] Role management
- [ ] Permission management
- [ ] Authorization policies
- [ ] Dashboard
- [ ] Profile management
- [ ] Settings foundation
- [ ] Notifications foundation
- [ ] Livewire admin interface
- [ ] Versioned REST API
- [ ] Sanctum authentication
- [ ] API Resources
- [ ] Standard API responses
- [ ] Pagination/search/filter/sorting examples
- [ ] DTO examples
- [ ] Action examples
- [ ] Service example for a complex workflow
- [ ] Repository example only where justified
- [ ] Jobs
- [ ] Events/listeners
- [ ] Pest tests
- [ ] Pint
- [ ] PHPStan/Larastan
- [ ] CI pipeline
- [ ] API documentation
- [ ] Security configuration
- [ ] Database factories
- [ ] Database seeders
- [ ] Production deployment guidance
- [ ] Comprehensive README

---

# 38. Final Goal

The goal is **not** to create the most architecturally complex Laravel boilerplate.

The goal is to create a boilerplate where a developer can clone the repository and immediately have a strong foundation for a real-world application.

The architecture should communicate one central idea:

> **Keep Laravel simple. Add abstractions when complexity requires them, not before.**

The resulting boilerplate should demonstrate modern Laravel engineering practices while remaining approachable to the wider Laravel community.


---

# 39. Reusable UI Component System

The boilerplate should provide a reusable UI component foundation for the admin panel.

Recommended components:

```text
Button
Input
Textarea
Select
Checkbox
Radio
Modal
Dropdown
Alert
Toast
Badge
Card
Table
DataTable
Pagination
Confirm Dialog
Date Picker
File Upload
Tabs
Breadcrumb
Empty State
Loading/Skeleton
```

Components should be:

- Consistent
- Accessible
- Responsive
- Easy to customize
- Reusable across Livewire screens

Avoid duplicating markup and behavior across individual pages.

---

# 40. DataTable System

DataTables are a first-class feature because the boilerplate targets admin panels, SaaS applications, CRMs, ERPs, and internal tools.

Use a **Livewire-based DataTable approach**.

Do not use jQuery DataTables as the primary table architecture.

The DataTable system should support:

```text
Search
Sorting
Filtering
Pagination
Per-page selection
Bulk selection
Bulk actions
Row actions
Column visibility
Date-range filters
Relationship filters
URL query-string state
Loading state
Empty state
Responsive layout
Permission-aware actions
```

Example:

```text
Users
├── Search
├── Status filter
├── Role filter
├── Date filter
├── Sort
├── Pagination
├── Select rows
├── Bulk actions
├── Row actions
└── Column visibility
```

Reference implementations must include:

```text
UsersTable
RolesTable
PermissionsTable
```

Do not build an excessively generic DataTable abstraction that hides normal Eloquent behavior.

Prefer a reusable table foundation with clear, explicit table implementations.

---

# 41. Form Architecture

Use a consistent form architecture.

Preferred flow:

```text
Livewire Form
    ↓
Validation
    ↓
DTO
    ↓
Action
```

Example form objects/components:

```text
CreateUserForm
UpdateUserForm
CreateRoleForm
UpdateRoleForm
LoginForm
ProfileForm
```

Forms should not contain large business workflows.

---

# 42. File and Media Handling

Provide a basic, extensible file-handling foundation.

Support:

- File validation
- Storage configuration
- Public/private files
- Upload
- Replace
- Delete
- Image handling where appropriate

Do not build a complete media-management product into the core boilerplate.

Provide clean examples that applications can extend.

---

# 43. Activity / Audit Logging

Provide a basic activity/audit-log foundation suitable for admin applications.

Track important actions such as:

```text
User created
User updated
User deleted
Role assigned
Permission changed
Settings changed
```

Where appropriate, an activity entry should contain:

```text
User
Action
Subject
Old values
New values
IP address
User agent
Timestamp
```

Sensitive information such as passwords, tokens, and secrets must never be logged.

---

# 44. Transactions

Actions that modify multiple related records must consider database transactions.

Example:

```php
DB::transaction(function () {
    // Create user
    // Create profile
    // Assign roles
});
```

Use transactions when the operation must be atomic.

Do not wrap every database query in a transaction unnecessarily.

---

# 45. Soft Deletes

Soft deletes should be used selectively.

Do not automatically add `SoftDeletes` to every model.

Use soft deletes when restoring a record is a meaningful business requirement.

Where soft deletes are used, provide:

```text
Delete
Restore
Force delete
```

with appropriate authorization.

---

# 46. Caching

Use caching only where it provides a clear benefit.

Potential examples:

```text
Permissions
Application settings
Expensive dashboard statistics
Expensive queries
```

Avoid premature caching.

Document cache invalidation rules when caching is introduced.

---

# 47. Queue and Scheduler Foundation

Provide production-oriented queue and scheduler guidance.

Queue examples:

```text
SendWelcomeEmail
SendNotification
ProcessReport
```

Document:

```text
Queue connection
Failed jobs
Retry strategy
Worker configuration
Supervisor/Horizon guidance
```

Scheduler examples may include:

```text
Cleanup old records
Send scheduled notifications
Generate reports
Maintenance tasks
```

Do not add unnecessary scheduled jobs to the core application.

---

# 48. API Error Handling

Standardize API behavior for common errors.

Examples:

```text
401 Unauthorized
403 Forbidden
404 Not Found
409 Conflict
422 Validation Error
429 Too Many Requests
500 Server Error
```

Production API responses must not expose:

- Stack traces
- Database credentials
- Secrets
- Internal infrastructure details
- Sensitive exception information

Validation errors should use a predictable structure.

---

# 49. API Rate Limiting

Provide sensible rate-limiting examples for:

```text
Authentication
Registration
Password reset
General API
Write operations
```

Use Laravel's native rate-limiting capabilities where possible.

---

# 50. CORS

Provide safe CORS defaults.

Production documentation must explain how to configure allowed origins rather than allowing all origins by default.

---

# 51. Multi-Tenancy Decision

Version 1 of the boilerplate is **single-tenant**.

Multi-tenancy is not a core V1 feature.

However:

- Avoid architectural decisions that make future multi-tenancy impossible.
- Keep business logic sufficiently isolated.
- Avoid hard-coding assumptions that there can only ever be one organization.

Multi-tenancy may be considered as a future major feature or separate package.

---

# 52. Configuration and Environment

Use Laravel configuration and environment variables correctly.

Provide a complete `.env.example` covering relevant settings:

```text
Application
Database
Cache
Queue
Mail
Filesystem
Session
Sanctum
```

Never commit secrets.

Never use `env()` directly throughout application code where `config()` should be used.

---

# 53. Timezone and Localization

Establish a clear timezone strategy.

Recommended:

```text
Database
    ↓
UTC

Application
    ↓
Configurable timezone

Presentation
    ↓
Application/user timezone
```

Provide localization support through Laravel's localization system.

The default language may be English, while keeping the application easy to translate.

---

# 54. Idempotency

The architecture should leave room for idempotent API operations.

This is particularly important for future:

```text
Payments
Orders
External integrations
Webhook processing
```

A global idempotency implementation is not required for V1 unless a feature needs it.

---

# 55. Health and Observability

Provide a basic production observability foundation.

Include guidance for:

```text
Application logging
Error reporting
Health checks
Queue monitoring
Database/query monitoring
```

Avoid requiring a paid monitoring provider.

The application should be compatible with external monitoring tools without being tightly coupled to one.

---

# 56. Docker / Local Development

Provide an easy local-development path.

Prefer Laravel's supported development tooling, such as Laravel Sail, where appropriate.

The desired developer experience:

```text
Clone
 ↓
Configure
 ↓
Install
 ↓
Migrate
 ↓
Seed
 ↓
Run
```

The README must clearly document the setup process.

---

# 57. Demo / Development Seed Data

Provide seed data for local development.

Example:

```text
Admin
admin@example.com
```

and sample:

```text
Users
Roles
Permissions
Notifications
Activity logs
```

Demo credentials must be clearly marked as development-only and must not be recommended for production.

---

# 58. Reusable Application Patterns

The boilerplate should demonstrate how to build a new module.

A reference module should show:

```text
Migration
Model
Factory
Seeder
DTO
Action
Form Request / Validation
Policy
Livewire Component
DataTable
API Controller
API Resource
API Route
Feature Test
Unit Test
```

The Users module should be the primary reference implementation.

Roles and Permissions should provide additional examples.

---

# 59. Dependency Management

Do not introduce packages without justification.

For every significant third-party dependency:

- Explain why it is needed.
- Prefer actively maintained packages.
- Prefer packages aligned with the Laravel ecosystem.
- Avoid packages that duplicate core Laravel functionality.
- Avoid unnecessary frontend dependencies.

The boilerplate should remain lightweight enough for community adoption.

---

# 60. AI Agent Implementation Workflow

The AI coding agent must implement the project incrementally.

## Phase 1 — Project Inspection

Before changing code:

```text
Inspect Laravel version
Inspect PHP version
Inspect dependencies
Inspect existing authentication
Inspect frontend stack
Inspect database
Inspect routes
Inspect tests
Inspect configuration
```

Do not overwrite working functionality without understanding it.

## Phase 2 — Foundation

Implement:

```text
Configuration
Database foundation
Base layout
UI component foundation
Development tooling
Code quality tooling
```

## Phase 3 — Authentication

Implement:

```text
Login
Logout
Registration
Email verification
Password reset
Profile
Password change
```

## Phase 4 — RBAC

Implement:

```text
Users
Roles
Permissions
Policies
Authorization
```

## Phase 5 — Reusable UI

Implement:

```text
Buttons
Forms
Modals
Alerts
Toasts
Cards
Navigation
Breadcrumbs
Empty states
Loading states
```

## Phase 6 — DataTables

Implement:

```text
UsersTable
RolesTable
PermissionsTable
```

with the complete DataTable feature set.

## Phase 7 — Dashboard / Settings / Notifications

Implement:

```text
Dashboard
Settings foundation
Notifications
Activity log
```

## Phase 8 — API

Implement:

```text
API V1
Sanctum authentication
Users
Roles
Permissions
Resources
Validation
Error handling
Pagination
Filtering
Sorting
API documentation
```

## Phase 9 — Background Processing

Implement examples for:

```text
Jobs
Events
Listeners
Scheduler
Queues
```

## Phase 10 — Testing

Implement:

```text
Feature tests
Unit tests
Action tests
API tests
Authorization tests
DataTable-related behavior tests
```

Use Pest as the primary testing framework.

## Phase 11 — Quality

Run:

```text
Pint
PHPStan/Larastan
Pest
```

Fix all failures before continuing.

## Phase 12 — Documentation

Complete:

```text
README
Architecture documentation
API documentation
Contribution guide
Deployment guide
Environment documentation
```

## Phase 13 — Final QA

Verify:

```text
Fresh installation
Database migration
Seed data
Authentication
Authorization
CRUD
DataTables
API
API authentication
Tests
Static analysis
Formatting
Security
Production configuration
```

---

# 61. AI Agent Rules During Implementation

The AI agent must:

1. Inspect before modifying.
2. Work incrementally.
3. Keep changes focused.
4. Prefer Laravel-native solutions.
5. Avoid unnecessary abstractions.
6. Avoid unnecessary dependencies.
7. Keep controllers thin.
8. Keep Livewire components focused.
9. Keep Actions focused.
10. Use DTOs where they improve boundaries.
11. Use Services only for meaningful orchestration.
12. Use Repositories only when justified.
13. Reuse business Actions between Livewire and API.
14. Never duplicate business logic.
15. Write tests with important functionality.
16. Run tests after meaningful changes.
17. Run Pint.
18. Run PHPStan/Larastan.
19. Review authorization for every protected operation.
20. Never expose secrets.
21. Never silently remove existing functionality.
22. Document important architectural decisions.

---

# 62. Git and Contribution Standards

Provide community-friendly Git conventions.

Recommended:

```text
feature/*
fix/*
refactor/*
docs/*
test/*
chore/*
```

Pull requests should include:

```text
What changed
Why it changed
Tests added/updated
Potential breaking changes
```

The project should include:

```text
CONTRIBUTING.md
CODE_OF_CONDUCT.md
CHANGELOG.md
LICENSE
```

where appropriate.

---

# 63. Definition of Done — Final

The boilerplate is complete only when all of the following are satisfied:

- [ ] Laravel foundation
- [ ] Authentication
- [ ] User management
- [ ] Role management
- [ ] Permission management
- [ ] Policies
- [ ] Dashboard
- [ ] Profile
- [ ] Settings foundation
- [ ] Notifications
- [ ] Activity/audit log
- [ ] Reusable UI components
- [ ] Livewire architecture
- [ ] DataTable system
- [ ] Search
- [ ] Sorting
- [ ] Filtering
- [ ] Pagination
- [ ] Bulk actions
- [ ] Column visibility
- [ ] API V1
- [ ] Sanctum
- [ ] API Resources
- [ ] Standard API responses
- [ ] API validation errors
- [ ] API pagination/filtering/sorting
- [ ] API documentation
- [ ] DTO examples
- [ ] Action examples
- [ ] Service example
- [ ] Repository example only where justified
- [ ] Form architecture
- [ ] File handling foundation
- [ ] Jobs
- [ ] Events/listeners
- [ ] Scheduler
- [ ] Queue documentation
- [ ] Cache strategy
- [ ] Rate limiting
- [ ] CORS configuration
- [ ] Security configuration
- [ ] Transaction examples
- [ ] Soft-delete strategy
- [ ] Timezone strategy
- [ ] Localization foundation
- [ ] Health/observability guidance
- [ ] Pest tests
- [ ] PHPUnit compatibility
- [ ] Pint
- [ ] PHPStan/Larastan
- [ ] CI pipeline
- [ ] Docker/local development
- [ ] `.env.example`
- [ ] Demo seed data
- [ ] README
- [ ] Architecture documentation
- [ ] Contribution documentation
- [ ] Deployment documentation
- [ ] Fresh-install verification
- [ ] Final security review
- [ ] Final test suite passing
- [ ] Static analysis passing
- [ ] Code formatting passing

---

# 64. Final Architectural Decision

The final architecture is intentionally **pragmatic rather than pattern-heavy**.

```text
                    ┌──────────────────┐
                    │     Livewire     │
                    └────────┬─────────┘
                             │
                    ┌────────▼─────────┐
                    │      Actions     │
                    └────────┬─────────┘
                             │
              ┌──────────────┼──────────────┐
              ▼              ▼              ▼
           Models         Services         Jobs
              │              │
              │              ▼
              │        External APIs
              │
              ▼
           Database


                    ┌──────────────────┐
                    │       API        │
                    └────────┬─────────┘
                             │
                       Controllers
                             │
                         FormRequest
                             │
                            DTO
                             │
                           Action
                             │
                     Model / Service
                             │
                       API Resource
```

### Architectural priority

```text
1. Laravel-native conventions
2. Actions
3. DTOs
4. Form Requests / Policies / Resources
5. Livewire
6. DataTables
7. Jobs / Events
8. Services when workflows become complex
9. Repositories only when genuinely justified
```

### Core philosophy

> **Keep Laravel simple. Add abstractions when complexity requires them, not before.**

The boilerplate should teach developers good architecture while remaining approachable, maintainable, extensible, secure, and practical for real-world applications.
