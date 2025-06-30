# MongoDB Relations for Laravel

[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

Custom unidirectional many-to-many relationship support for MongoDB in Laravel.

---

## 📦 Installation

```bash
composer require tassawartech174/mongodb-relations

```
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

- You can now access it using:

<pre>
    config('mongodb-relations.default_local_key');
</pre>

---

## 🚀 Usage

**Step 1: Use the Trait**
In your Eloquent model (e.g. User), use the provided trait:

- `<code>` is inline, not block-level
- Doesn't render multiline code or syntax highlighting
- GitHub Markdown prefers triple backticks (` ```php `)

---

Would you like me to help format other sections like `Usage` or `API` in this same style?

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