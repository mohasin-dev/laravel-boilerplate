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

## Registration

Guests can create an account at `/register`. Registration validates unique lowercase email addresses and confirmed passwords using Laravel's configured password defaults. The `RegisterUser` Action normalizes input, securely hashes the password, creates an active user, and dispatches Laravel's `Registered` event. The new account is authenticated with a regenerated session.

## Extension points

`LoginUser`, `LogoutUser`, and `RegisterUser` contain the reusable authentication operations. Livewire components and controllers are responsible for their own transport concerns and should not duplicate those operations.
