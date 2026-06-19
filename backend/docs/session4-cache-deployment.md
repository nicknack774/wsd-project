# Session 4 - Cache and Deployment

## What tasks.index caches

The `tasks.index` cache key stores the full list of tasks returned by `Task::all()`. It uses `Cache::remember('tasks.index', 60, ...)` with a 60-second TTL, so repeated reads within that window hit Redis instead of PostgreSQL.

## Why store, update, and destroy call Cache::forget('tasks.index')

Any write operation changes the underlying data, so the cached list becomes stale immediately. Calling `Cache::forget('tasks.index')` after create, update, or delete forces the next read to rebuild the cache from the database, guaranteeing the cache never serves outdated data (cache-aside pattern with explicit invalidation).

## Purpose of Redis in this stack

Redis acts as a fast in-memory cache layer between the Laravel API and PostgreSQL. It avoids hitting the database for repeated identical reads, reducing latency and database load, especially under heavy traffic.

## Purpose of Nginx in this stack

Nginx serves the built Vue frontend as static files and reverse-proxies API and health check requests to the Laravel backend container. It allows both frontend and backend to be reachable through a single port (8090) instead of two separate ports.

## Commands used to verify cache behavior

- `docker compose exec redis redis-cli PING` - confirms Redis is alive
- `docker compose exec redis redis-cli -n 1 DBSIZE` - checks number of cached keys (Laravel cache uses Redis database index 1)
- `curl http://127.0.0.1:8090/api/tasks` - triggers cache population
- `curl -X POST http://127.0.0.1:8090/api/tasks ...` - triggers cache invalidation
- `time curl -s http://127.0.0.1:8090/api/tasks` - measures cold vs warm read latency
