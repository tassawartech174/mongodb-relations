# MongoDB Relations for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

Custom unidirectional many-to-many relationship support for MongoDB in Laravel.

---

## 📦 Installation

```bash

composer require tassawartech174/mongodb-relations

```
**Requires PHP 7.4+ and Laravel 6.x – 11.x.**

---

## ⚙️ Configuration

If you want to customize the default local key used in relations, publish the package config file:

```bash
php artisan vendor:publish --provider="TassawarTech174\MongodbRelations\MongodbRelationsServiceProvider" --tag=mongodb-relations-config

```
Or use this command:

```bash
php artisan vendor:publish --tag=mongodb-relations-config

```

- This will publish:

<pre>
config/mongodb-relations.php
</pre>

---

## 🚀 Usage

**Step 1: Use the Trait**

In your Eloquent model (e.g. `User`), use the provided trait:

<pre>
use TassawarTech174\MongodbRelations\Traits\MongodbRelations;

class User extends Model
{
    use MongodbRelations;

    public function roles()
    {
        return $this->manyToManyRelation(Role::class, 'role_ids');
    }
}
</pre>

Or use relation like:

<pre>
use TassawarTech174\MongodbRelations\Traits\MongodbRelations;

class User extends Model
{
    use MongodbRelations;

    public function roles()
    {
        return $this->manyToManyRelation(Role::class);
    }
}
</pre>

**Step 3: Relationship Methods**

- Get related models:

<pre>
$user->roles;
</pre>

- Attach related models:

<pre>
$user->roles()->attach(['id1', 'id2']);
</pre>

- Detach related models:

<pre>
$user->roles()->detach(['id1']);
</pre>

- Sync related models:

<pre>
$user->roles()->sync(['id1', 'id3']);
</pre>

---

## ✅ Supported Laravel Versions

- Laravel 6.x

- Laravel 7.x

- Laravel 8.x

- Laravel 9.x

- Laravel 10.x

- Laravel 11.x

## 🔐 License

This package is open-source software licensed under the MIT license.
