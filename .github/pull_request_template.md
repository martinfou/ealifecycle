# Secure API Documentation Access

## Description
This PR implements security improvements for the API documentation access, ensuring that sensitive API information is only available to authenticated administrators.

### Changes Made
- Restricted Swagger UI access to authenticated administrators
- Added `CheckIfAdmin` middleware for route protection
- Disabled runtime documentation generation in production
- Moved Swagger package from dev dependencies to main requirements
- Updated configuration for better security practices
- Added environment-specific Swagger generation settings

### Security Improvements
- All Swagger-related routes are now protected by authentication
- Only administrators can access the API documentation
- Prevents information disclosure to unauthorized users
- Optimizes resource usage by disabling runtime generation in production

## Testing Checklist
- [ ] Verify documentation access requires authentication
- [ ] Confirm admin-only access restriction works
- [ ] Test documentation generation in development environment
- [ ] Verify documentation is pre-generated in production
- [ ] Check that non-admin users receive appropriate 403 responses
- [ ] Ensure existing API functionality remains unchanged

## Additional Notes
- After merging, the production deployment process needs to include `php artisan l5-swagger:generate`
- Environment variables should be updated according to the documentation
- Administrators should be notified of the new access restrictions

## Related Issues
Resolves #security-audit-2024

## Type of Change
- [x] Security Enhancement
- [x] Feature Update
- [x] Documentation Update 