# Session 9 - News Feed Service

## Why feed systems are computationally expensive
A feed requires identifying who the current user follows, aggregating their recent posts, removing duplicates, sorting by recency or relevance, and doing this efficiently for potentially thousands of users at once. Each of these steps adds computational cost compared to a simple single-table query.

## Fan-out-on-write vs fan-out-on-read
Fan-out-on-write pre-computes each follower's feed at post time and stores a copy for every follower, making reads instant but writes expensive. Fan-out-on-read computes the feed at request time by querying followed users' posts directly, making writes cheap but reads more expensive. This lab implements a simplified fan-out-on-read approach.

## Why caching is critical
Feed generation involves joining follow relationships with content tables, which is expensive to repeat on every request. Caching the computed feed for a short period avoids recalculating it for repeated requests from the same user.

## Why pagination matters
Feeds can grow indefinitely as more content is created. Loading the entire feed at once would be slow and wasteful; pagination (or cursor-based pagination for infinite scrolling) limits how much data is fetched and rendered per request.

## Why deduplication is necessary
If a user follows multiple sources that share the same content, or if a query logic accidentally returns overlapping results, deduplication ensures each post appears only once in the final feed.

## Why modern feeds use ranking algorithms
A purely chronological feed shows the newest content first regardless of relevance. Ranking algorithms reorder content based on signals like engagement, recency, and relevance to keep the most valuable content visible to the user.

## Feed synchronization
When a user follows or unfollows someone, their cached feed becomes stale and must be invalidated so the next request reflects the updated set of followed users.

## Recommendation systems and AI feed ranking
Beyond simple chronological or engagement-based sorting, modern systems use machine learning models to predict which content a specific user is most likely to engage with, personalizing the feed per user rather than using one global ranking rule.

## Engagement optimization
Feeds are often tuned to maximize metrics like time spent, likes, or shares, which influences how ranking algorithms weigh different signals (recency vs popularity vs personal relevance).

## Real-time updates and notification integration
Production feed systems often push new content to users in real time (via websockets or polling) and integrate with notification systems to alert users about new posts from people they follow, rather than requiring a manual refresh.

## Endpoints
- POST /api/77963/v1/users/{id}/follow
- DELETE /api/77963/v1/users/{id}/follow
- GET /api/77963/v1/feed?limit=

## Testing evidence
Screenshots included: successful follow, feed generation showing followed user's content, Redis feed cache key, unfollow with empty feed result.
