# Session 10 - Video Catalog and Recommendations

## Why recommendation systems are critical
Without recommendations, users must manually search for content, which increases the chance they leave the platform without finding something to watch. Personalized recommendations increase engagement by surfacing relevant content automatically.

## Why watch history matters
Watch history is the primary signal used to understand user preferences (genres watched, completion rates) and to power both recommendations and the continue-watching feature.

## Why streaming systems require caching
Catalog browsing, recommendations, and continue-watching lists are read far more often than the underlying data changes. Caching these results in Redis avoids repeated expensive queries (joins between watch history and video metadata) for every request.

## Why multimedia systems are bandwidth-intensive
Video files are orders of magnitude larger than typical API payloads. Serving them efficiently at scale requires CDN distribution, adaptive bitrate streaming, and dedicated storage infrastructure rather than treating video like ordinary application data.

## Why playback synchronization is important
Users expect to resume a video from where they left off across devices. This requires persisting playback progress (progress_seconds) tied to the user and video, and keeping it updated as they watch.

## Why recommendation engines improve engagement
By surfacing content aligned with what a user already watches (same genre, similar viewing patterns), recommendation engines reduce the effort needed to find the next thing to watch, which increases time spent on the platform.

## Why CDN infrastructure is necessary
Streaming video to users worldwide directly from a single origin server would be slow and expensive. CDNs cache video segments at edge locations close to users, reducing latency and origin server load.

## Why adaptive streaming exists
Network conditions vary between users. Adaptive streaming serves different quality levels of the same video depending on the viewer's bandwidth, avoiding buffering on slow connections while still offering high quality on fast ones.

## Netflix-like architecture
Real streaming platforms separate concerns into distinct services: catalog metadata, watch history, recommendations, video encoding/transcoding, and CDN delivery, each scaling independently based on its own load patterns.

## Distributed multimedia systems
Video content is typically stored across multiple servers or regions rather than a single location, both for redundancy and to reduce the distance between content and viewers.

## Watchlist synchronization
A user's watchlist must stay consistent across devices and sessions, which requires storing it server-side (as implemented here) rather than relying on local device storage alone.

## Continue watching logic
The continue-watching feature filters watch history to only videos with progress greater than zero and not yet complete, ordered by most recently watched, so users can quickly resume.

## Recommendation ranking
This lab implements a simplified genre-based recommendation: videos sharing a genre with previously watched content, excluding videos already watched. Production systems extend this with collaborative filtering and machine learning models.

## Metadata systems
Video metadata (title, genre, duration) is stored separately from the actual video file, allowing fast catalog browsing without needing to access the large media files themselves.

## Event-driven streaming systems
Production streaming platforms often use events (e.g., "video watched", "progress updated") to trigger downstream processes like updating recommendation models or analytics, rather than tightly coupling every feature together.

## Endpoints
- GET /api/77963/v1/videos
- POST /api/77963/v1/videos
- GET /api/77963/v1/videos/{id}
- GET /api/77963/v1/recommendations
- GET /api/77963/v1/continue-watching
- POST /api/77963/v1/watchlist
- GET /api/77963/v1/watchlist
- DELETE /api/77963/v1/watchlist/{videoId}
- POST /api/77963/v1/watch-history/{videoId}

## Testing evidence
Screenshots included: video creation, catalog retrieval, recommendations, Redis recommendation cache key, watchlist addition, watch history update, continue-watching result.
