# Session 7 - Proximity Search Service

## Purpose of the module
This module allows users to find nearby restaurants based on their current coordinates, returning results sorted by distance.

## Why distance calculation is necessary
The Haversine formula calculates the great-circle distance between two latitude/longitude points on a sphere, which is required to know how far each restaurant actually is and to filter out the ones outside the search radius.

## Why spatial systems are more complex than standard CRUD systems
CRUD systems filter and sort using simple column comparisons. Proximity search requires a mathematical distance calculation between two coordinate pairs for every comparison, and sorting by a computed value rather than a stored column.

## Why proximity systems are difficult at scale
At small scale, comparing a user's location against every row works fine. At the scale of systems like Uber or food delivery apps with millions of locations, scanning every row for every request becomes too slow.

## Why read-heavy architectures require caching
Proximity search is read-heavy: the same area is queried repeatedly by many users. Caching the result for a given (lat, lng, radius) combination avoids recalculating distances for identical queries.

## Why spatial indexing matters
Spatial indexes let a database narrow its search to a relevant region first, instead of comparing distance against every row in the table. This turns a full table scan into a much faster lookup as the dataset grows.

## Why relational databases alone are insufficient
A relational database can store latitude and longitude as plain numeric columns, but without a spatial extension it has no efficient way to answer "what is near this point" without scanning every row.

## What geohashing is
Geohashing encodes a latitude/longitude pair into a short alphanumeric string by recursively dividing the world into smaller grid cells. Locations that are spatially close usually share a longer prefix in their geohash string.

## What a quadtree is
A quadtree is a tree structure that recursively subdivides two-dimensional space into four quadrants. Dense areas get subdivided further, while sparse areas stay as larger regions, making spatial queries efficient even as data density varies.

## Why low latency matters in web systems
Proximity search is often used in real-time, user-facing scenarios. High latency directly affects user experience, so caching and efficient distance calculation are used together to keep response times low.

## Endpoints
- GET /api/77963/v1/restaurants
- POST /api/77963/v1/restaurants
- GET /api/77963/v1/restaurants/{id}
- GET /api/77963/v1/restaurants/nearby?lat=&lng=&radius=

## Testing evidence
Screenshots included: successful restaurant creation, nearby search results sorted by distance, Redis cache key population for nearby queries.
