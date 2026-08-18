# Database Architecture

The boilerplate uses Laravel migrations as the source of truth for its schema and keeps database decisions intentionally conventional.

## Identifier strategy

Core tables use auto-incrementing unsigned big integers. They are well supported by Laravel and MySQL, keep relationships simple, and avoid adding UUID complexity without a concrete requirement. Applications that expose identifiers publicly can introduce UUIDs or ULIDs at that boundary.

## Timezone strategy

Database timestamps are stored in UTC. The application timezone is configurable with `APP_TIMEZONE`, while a user's optional timezone is reserved for presentation. Dates should be converted only when displayed, not before persistence.

## User lifecycle

Users support soft deletion because restoring an accidentally deleted account is meaningful in an admin application. The `is_active` flag disables access without deleting the account. Features that query users must deliberately decide whether inactive or soft-deleted records belong in their result.

## Seed safety

The root seeder creates demonstration data only in local and testing environments and is safe to run repeatedly. Production data must be provisioned through explicit deployment or administrative workflows.

## Transactions

Transactions belong around workflows whose related writes must succeed or fail together. Single independent writes should not be wrapped in transactions by default.
