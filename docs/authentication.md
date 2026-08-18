# Authentication

Web authentication uses Laravel's session guard and Livewire pages. HTTP concerns such as session regeneration, intended redirects, and validation stay at the Livewire or controller boundary; credential operations live in focused Actions.

## Login

Guests can sign in at `/login`. Login attempts:

- accept an email address, password, and optional remember-me preference;
- normalize the email address before authentication;
- reject inactive and soft-deleted users with the same generic credential error;
- regenerate the session identifier after success;
- honor Laravel's intended destination;
- throttle repeated failures by normalized email and IP address.

Five failed attempts within one minute lock that email/IP combination until the limiter becomes available again.

## Logout

Logout is a `POST` request protected by the `auth` and CSRF middleware. It logs out the web guard, invalidates the session, regenerates the CSRF token, and redirects home.

## Extension points

`LoginUser` and `LogoutUser` contain the reusable authentication operations. Livewire components and controllers are responsible for their own transport concerns and should not duplicate those operations.
