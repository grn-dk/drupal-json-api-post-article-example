# Drupal 11 Article Poster

This PHP script allows you to post articles to a Drupal 11 site via its JSON API, using authentication with environment variables stored in a `.env` file.

## Requirements

- PHP 8.3+
- Composer
- Drupal 11 site with JSON API enabled including writing permissions.
- The `vlucas/phpdotenv` package for handling environment variables

## Setup Instructions

### 1. Clone the Repository

If you haven't already, clone the repository containing this script:

```bash
git clone https://your-repository-url.git
cd your-repository-folder
composer install
```

### 2. Setup .env
create `.env` file and insert right information:

```bash
DRUPAL_URL=https://example.com  # Your Drupal site URL
DRUPAL_USERNAME=your-username      # Your Drupal username
DRUPAL_PASSWORD=your-password      # Your Drupal password
```

### 3. Setup Drupal 11
Enable JSON:API and set `Accept all JSON:API create, read, update, and delete operations.`
https://webapp.grn.dk/admin/config/services/jsonapi


### 4. Run PHP Script
`php php_drupal11-create-article-json-api.php`