# MongoDB Relations for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

Custom unidirectional many-to-many relationship support for MongoDB in Laravel.

---

## 📦 Installation

```bash
composer require tassawartech174/mongodb-relations

```

## ⚙️ Configuration

If you want to customize the default local key used in relations, publish the package config file:

```bash
php artisan vendor:publish --provider="TassawarTech174\MongodbRelations\MongodbRelationsServiceProvider" --tag=mongodb-relations-config

```

---

- This will publish:

<pre> ```
    config/mongodb-relations.php
``` </pre>

- Example config file:

<pre> ```
    return [
        'default_local_key' => 'related_ids',
    ];
``` </pre>

- You can now access it using:

<pre> ```
    config('mongodb-relations.default_local_key');
``` </pre>