# Session 8 - Photo Upload Service

## Why object storage is necessary
Storing large binary files (images, video) directly in a relational database bloats the database, slows down queries, and makes backups much larger. Object/file storage keeps the database focused on structured metadata while files live in a separate, purpose-built storage layer.

## Why databases should not store large images directly
Databases are optimized for structured, queryable data, not large binary blobs. Storing images as BLOBs increases table size dramatically, slows down indexing and replication, and makes simple metadata queries (like listing photo titles) far more expensive than they need to be.

## Why CDN improves performance
A CDN caches static files (like images) at edge locations close to users worldwide, reducing latency and reducing repeated load on the origin server for the same file.

## Why asynchronous processing matters
Tasks like image resizing, thumbnail generation, or format conversion can take noticeable time. Performing this synchronously inside the HTTP request blocks the client and wastes server resources. Asynchronous processing lets the upload respond immediately while the heavier work happens in the background.

## Why upload systems require scalability
Upload systems are bursty by nature (sudden spikes in usage) and storage/bandwidth grow continuously over time. They need infrastructure that can scale storage capacity and processing capacity independently of the rest of the application.

## Why Redis improves metadata performance
Photo metadata (title, caption, status) is read far more often than it is written. Caching frequently accessed metadata in Redis avoids repeated PostgreSQL queries for the same photo or photo list, reducing database load and response time.

## Why modern social media systems use microservices
Splitting upload handling, image processing, metadata storage, and feed generation into separate services allows each part of the system to scale independently, fail independently, and be developed/deployed without affecting unrelated functionality.

## Endpoints
- GET /api/77963/v1/photos
- POST /api/77963/v1/photos
- GET /api/77963/v1/photos/{id}
- DELETE /api/77963/v1/photos/{id}

## Testing evidence
Screenshots included: successful upload, public image access via browser, single photo retrieval, Redis cache population after listing.
