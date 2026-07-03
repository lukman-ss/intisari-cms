# Changelog

All notable changes to this project will be documented in this file.

## Unreleased

- Added release documentation and security policy.
- Fixed route order so admin bulk, revision, and autosave routes are registered before public catch-all routes.
- Hardened web installer validation and password hashing.
- Registered the application service provider during bootstrap.
- Aligned middleware classes with the framework middleware contract.
- Enabled global security headers and CSRF protection.
- Added export and line-ending rules for Composer archives.
