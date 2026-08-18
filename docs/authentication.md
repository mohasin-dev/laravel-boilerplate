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

Guests can create an account at `/register`. Registration validates unique lowercase email addresses and confirmed passwords using Laravel's configured password defaults. The `RegisterUser` Action normalizes input, securely hashes the password, creates an active user, and dispatches Laravel's `Registered` event. The new account is authenticated with a regenerated session and sent to the verification notice.

## Email verification

Users implement Laravel's `MustVerifyEmail` contract. Verification links use Laravel's temporary signed URL and are throttled at the route. Users can request up to three additional verification emails per minute from the notice screen. Successful verification dispatches Laravel's `Verified` event and redirects home.

Applications should apply Laravel's `verified` middleware to routes that require a confirmed email address.

## Extension points

`LoginUser`, `LogoutUser`, and `RegisterUser` contain the reusable authentication operations. Livewire components and controllers are responsible for their own transport concerns and should not duplicate those operations.
