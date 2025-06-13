# Strategy Reports API Documentation

## Overview
The Strategy Reports API provides endpoints for managing PDF reports associated with trading strategies. These endpoints allow users to upload, download, and delete strategy reports while ensuring proper authentication and authorization.

## Authentication
All endpoints require authentication using Laravel Sanctum. Include the token in the Authorization header:
```
Authorization: Bearer YOUR_TOKEN
```

## Endpoints

### Upload Report
Upload a new PDF report for a strategy.

**Endpoint:** `POST /api/v1/strategies/{strategy}/reports`

**Headers:**
- `Authorization: Bearer YOUR_TOKEN`
- `Content-Type: multipart/form-data`

**Parameters:**
- `strategy` (path parameter): The ID of the strategy
- `report` (file): The PDF file to upload (max 10MB)

**Request Example:**
```bash
curl --location 'http://localhost:8000/api/v1/strategies/3/reports' \
--header 'Authorization: Bearer YOUR_TOKEN' \
--form 'report=@"/path/to/your/report.pdf"'
```

**Success Response (201):**
```json
{
    "message": "Report uploaded successfully",
    "filename": "uuid-generated-filename.pdf",
    "original_filename": "original-report-name.pdf"
}
```

**Error Responses:**
- 401 Unauthorized: Invalid or missing token
- 403 Forbidden: User doesn't have permission to edit the strategy
- 422 Unprocessable Entity: Invalid file type or size

### Download Report
Download a strategy's PDF report.

**Endpoint:** `GET /api/v1/strategies/{strategy}/reports`

**Headers:**
- `Authorization: Bearer YOUR_TOKEN`
- `Accept: application/pdf`

**Parameters:**
- `strategy` (path parameter): The ID of the strategy

**Request Example:**
```bash
curl --location 'http://localhost:8000/api/v1/strategies/3/reports' \
--header 'Accept: application/pdf' \
--header 'Authorization: Bearer YOUR_TOKEN' \
--output strategy-report.pdf
```

**Success Response (200):**
- Content-Type: application/pdf
- Content-Disposition: attachment; filename="original-filename.pdf"
- Binary PDF data

**Error Responses:**
- 401 Unauthorized: Invalid or missing token
- 403 Forbidden: User doesn't have permission to view the strategy
- 404 Not Found: Strategy or report not found

### Delete Report
Delete a strategy's PDF report.

**Endpoint:** `DELETE /api/v1/strategies/{strategy}/reports`

**Headers:**
- `Authorization: Bearer YOUR_TOKEN`

**Parameters:**
- `strategy` (path parameter): The ID of the strategy

**Request Example:**
```bash
curl --location --request DELETE 'http://localhost:8000/api/v1/strategies/3/reports' \
--header 'Authorization: Bearer YOUR_TOKEN'
```

**Success Response (200):**
```json
{
    "message": "Report deleted successfully"
}
```

**Error Responses:**
- 401 Unauthorized: Invalid or missing token
- 403 Forbidden: User doesn't have permission to edit the strategy
- 404 Not Found: Strategy or report not found

## Authorization Rules

### View Permissions
Users can download reports if they:
- Own the strategy
- Have any permission (read/write) in the strategy's group

### Edit Permissions
Users can upload or delete reports if they:
- Own the strategy
- Have write permission in the strategy's group

## File Storage
- Reports are stored in the `public` disk under `strategies/{strategy_id}/reports/`
- Each file is stored with a UUID-generated filename
- Original filenames are preserved in the database
- Maximum file size: 10MB
- Only PDF files are accepted

## Error Handling
The API uses standard HTTP status codes:
- 200: Success
- 201: Created
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error

## Rate Limiting
API requests are rate-limited to prevent abuse. The current limits are:
- 60 requests per minute per IP address
- 1000 requests per hour per user

## Best Practices
1. Always include the `Accept: application/pdf` header when downloading reports
2. Use the `--output` option with curl to save downloaded files
3. Handle rate limiting by implementing exponential backoff
4. Validate file types and sizes before uploading
5. Implement proper error handling for all API responses

## Testing
The API endpoints are covered by comprehensive tests in `tests/Feature/Api/V1/StrategyReportTest.php`. The test suite includes:
- Upload functionality
- Download functionality
- Delete functionality
- File type validation
- File size validation
- Authorization checks
- Error handling

## Related Resources
- [Strategy API Documentation](../strategies.md)
- [Authentication Guide](../../authentication.md)
- [File Upload Guidelines](../../file-uploads.md) 