# News Aggregator Backend

A Laravel-based news aggregator backend that fetches articles from multiple news sources and provides a RESTful API for frontend applications.

## Features

- **Multi-Source News Aggregation**: Fetches articles from NewsAPI, The Guardian, and New York Times
- **RESTful API**: Complete CRUD operations for articles with advanced filtering
- **Data Storage**: Stores articles in database with deduplication
- **Scheduled Updates**: Console command for regular article fetching
- **Advanced Filtering**: Filter by author, source, category, date, and more
- **Pagination**: Built-in pagination for large datasets
- **Validation**: Comprehensive request validation
- **Testing**: Unit and feature tests included

## Requirements

- PHP 8.2+
- Laravel 12.0+
- MySQL/PostgreSQL/SQLite
- Composer

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd news-aggregator
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure environment variables**
   Add your API keys to `.env`:
   ```env
   # News API Configuration
   NEWSAPI_KEY=your_newsapi_key_here
   NEWSAPI_URL=https://newsapi.org/v2/top-headlines

   # The Guardian API Configuration
   GUARDIAN_KEY=your_guardian_key_here
   GUARDIAN_URL=https://content.guardianapis.com/search

   # New York Times API Configuration
   NYT_KEY=your_nyt_key_here
   NYT_URL=https://api.nytimes.com/svc/news/v3/content/all/all.json
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

## API Documentation

### Base URL
```
http://localhost:8000/api
```

### Endpoints

#### Get All Articles (with filtering)
```
GET /articles
```

**Query Parameters:**
- `author` (optional): Filter by author name
- `source` (optional): Filter by source
- `category` (optional): Filter by category
- `date` (optional): Filter by date (YYYY-MM-DD)

**Example:**
```
GET /api/articles?author=John Doe&category=technology
```

#### Get Single Article
```
GET /articles/{id}
```

#### Create Article
```
POST /articles
```

**Body:**
```json
{
    "title": "Article Title",
    "description": "Article description",
    "author": "Author Name",
    "source": "Source Name",
    "category": "Category",
    "url": "https://example.com/article",
    "published_at": "2024-01-01 12:00:00"
}
```

#### Update Article
```
PUT /articles/{id}
```

#### Delete Article
```
DELETE /articles/{id}
```

#### Fetch Latest Articles from All Sources
```
GET /articles/fetch-latest
```

#### Fetch Articles by Provider
```
GET /articles/fetch-latest/{provider}
```

**Providers:** NewsApi, Guardian, Nyt

#### Advanced Filtering Endpoints
```
GET /articles/fetch-latest/{provider}/{category}
GET /articles/fetch-latest/{provider}/{category}/{date}
GET /articles/fetch-latest/{provider}/{category}/{date}/{author}
GET /articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}
GET /articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}
GET /articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}
GET /articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}/{sort}
GET /articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}/{sort}/{order}
GET /articles/fetch-latest/{provider}/{category}/{date}/{author}/{source}/{limit}/{offset}/{sort}/{order}/{fields}
```

## Console Commands

### Fetch Latest Articles
```bash
php artisan news:fetch
```

This command fetches the latest articles from all configured news providers and stores them in the database.

## Architecture

### Models
- **Article**: Main model for storing article data

### Services
- **NewsAggregatorService**: Orchestrates fetching from multiple providers
- **NewsProviderInterface**: Contract for news providers
- **NewsApiService**: Implementation for NewsAPI
- **GuardianService**: Implementation for The Guardian
- **NytService**: Implementation for New York Times

### Repositories
- **ArticleRepository**: Handles data access and filtering

### Controllers
- **ArticleController**: Handles all API endpoints

### Request Classes
- **FilterArticlesRequest**: Validates filtering parameters
- **StoreArticleRequest**: Validates article creation
- **UpdateArticleRequest**: Validates article updates

## Testing

Run the test suite:
```bash
php artisan test
```

## Database Schema

### Articles Table
- `id` - Primary key
- `title` - Article title
- `description` - Article description
- `author` - Article author
- `source` - News source
- `category` - Article category
- `url` - Article URL (unique)
- `published_at` - Publication date
- `created_at` - Record creation timestamp
- `updated_at` - Record update timestamp

## Error Handling

The API returns appropriate HTTP status codes:
- `200` - Success
- `201` - Created
- `204` - No content (delete)
- `422` - Validation error
- `404` - Not found
- `500` - Server error

## Rate Limiting

Consider implementing rate limiting for production use, especially for the fetch endpoints that make external API calls.

## Security Considerations

1. **API Keys**: Store API keys securely in environment variables
2. **Validation**: All inputs are validated
3. **SQL Injection**: Uses Eloquent ORM for protection
4. **CORS**: Configure CORS for frontend integration

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
