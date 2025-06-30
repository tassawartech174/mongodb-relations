# MongoDB Relations for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

Custom unidirectional many-to-many relationship support for MongoDB in Laravel.

---

## 📦 Installation

```bash
composer require tassawartech174/mongodb-relations

⚙️ Configuration (Optional)
If you want to customize the default local key used in relations, you can publish the config file:

php artisan vendor:publish --provider="TassawarTech174\MongodbRelations\MongodbRelationsServiceProvider" --tag=mongodb-relations-config