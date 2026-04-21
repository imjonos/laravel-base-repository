# 📦 Laravel Base Repository

[![Latest Version on Packagist](https://img.shields.io/packagist/v/imjonos/laravel-base-repository.svg?style=flat-square)](https://packagist.org/packages/imjonos/laravel-base-repository)  
[![Total Downloads](https://img.shields.io/packagist/dt/imjonos/laravel-base-repository.svg?style=flat-square)](https://packagist.org/packages/imjonos/laravel-base-repository)

A **generic base repository class** for Laravel projects that provides a clean and consistent way to interact with Eloquent models. It simplifies CRUD operations and makes your code more maintainable, testable, and scalable.

---

## 🧩 Overview

This package provides an abstract `EloquentRepository` class that implements the `EloquentRepositoryInterface`. It wraps common model interactions into reusable methods, making it easier to manage data access logic in your Laravel applications.

---

## 🛠 Installation

Install the package via Composer:

```bash
composer require imjonos/laravel-base-repository
```

---

## ✅ Usage

### 1. Create Your Repository Class

Create a new repository class that extends `EloquentRepository` and specifies the model class:

```php
namespace App\Repositories;

use App\Models\Order;
use Nos\BaseRepository\EloquentRepository;

class OrderRepository extends EloquentRepository
{
    protected string $class = Order::class;
}
```

> **Note:** If you use Laravel Scout with the `Searchable` trait on your model, the repository will automatically keep the search index in sync.

### 2. Use the Repository in a Controller or Service

Inject the repository and use its methods:

```php
namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $orders = $this->repository->all();
        return view('orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $order = $this->repository->create($request->all());
        return redirect()->route('orders.show', $order->id);
    }
}
```

---

## 🔧 Available Methods

| Method                                                            | Description                                        |
|-------------------------------------------------------------------|----------------------------------------------------|
| `all()`                                                           | Get all records                                    |
| `count()`                                                         | Count all records                                  |
| `create(array $data)`                                             | Create a new record                                |
| `update(int $id, array $data)`                                    | Update a record by ID                              |
| `exists(int $id)`                                                 | Check if a record exists                           |
| `find(int $id)`                                                   | Find a record by ID (returns null if not found)    |
| `delete(int $id)`                                                 | Delete a record by ID                              |
| `query()`                                                         | Return a query builder instance for custom queries |
| `insert(array $data)`                                             | Insert multiple records (no events, no scout)      |
| `upsert(array $values, array\| string $uniqueBy, ?array $update)` | Update or insert multiple records |

### 🔍 Laravel Scout Support

The repository automatically handles Laravel Scout indexing when using the `Searchable` trait:
- After `upsert()`: Makes records searchable
- After `update()`: Updates search index
- After `delete()`: Removes from search index

---

## 🌐 Project Structure

```
src/
├── EloquentRepository.php
└── Interfaces/
    └── EloquentRepositoryInterface.php
```

---

## 📦 Requirements

- PHP 8.0+
- Laravel 5.0+ - 13.x

---

## 🧪 Testing

You can easily mock the repository interface in your tests, which helps keep your application logic decoupled from the database and improves test coverage.

---

## 🚀 Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

---

## 📝 License

This package is open-sourced software licensed under the MIT license.
Please see the [license file](license.md) for more information.
