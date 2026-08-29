# Graph Report - pos-app  (2026-08-28)

## Corpus Check
- Corpus is ~35,838 words - fits in a single context window. You may not need a graph.

## Summary
- 634 nodes · 1017 edges · 100 communities (77 shown, 23 thin omitted)
- Extraction: 92% EXTRACTED · 8% INFERRED · 0% AMBIGUOUS · INFERRED: 86 edges (avg confidence: 0.97)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Auth Controllers (Fortify)
- Core Controllers & Routes
- Composer Dependencies
- Database Migrations
- NPM/Frontend Dependencies
- Composer Scripts
- Project Architecture Rules
- Config & Factories
- POS Module Requirements
- User Management Controller
- Authentication Tests
- Login Request & Auth Logic
- Database Seeders
- POS Feature Tests
- POS Controller & Product Model
- Product Tests
- User Management Tests
- Category Controller
- Category Tests
- Dashboard Tests
- Email Verification
- Password Reset
- Settings/QRIS Controller
- Agent & Laravel Boost
- Service Providers
- Navigation Tests
- Logging Config
- Password Confirmation Tests
- Unit Tests
- Profile Blade Partials
- Console Routes
- App Layout & Navigation
- GitHub Setup
- Community 33
- Community 34
- Community 35
- Community 36
- Community 37
- Community 38
- Community 39
- Community 48

## God Nodes (most connected - your core abstractions)
1. `User` - 81 edges
2. `Product` - 39 edges
3. `Sale` - 37 edges
4. `Category` - 35 edges
5. `TestCase` - 34 edges
6. `Controller` - 28 edges
7. `PosTest` - 24 edges
8. `SaleItem` - 20 edges
9. `ReportTest` - 16 edges
10. `ProductTest` - 15 edges

## Surprising Connections (you probably didn't know these)
- `PosTest` --references--> `Category`  [EXTRACTED]
  tests/Feature/PosTest.php → app/Models/Category.php
- `ProductTest` --references--> `Category`  [EXTRACTED]
  tests/Feature/ProductTest.php → app/Models/Category.php
- `PosTest` --references--> `Product`  [EXTRACTED]
  tests/Feature/PosTest.php → app/Models/Product.php
- `CategoryTest` --references--> `User`  [EXTRACTED]
  tests/Feature/CategoryTest.php → app/Models/User.php
- `PosTest` --references--> `User`  [EXTRACTED]
  tests/Feature/PosTest.php → app/Models/User.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Docker Compose Service Stack** — docker-compose.yml_app_service, docker-compose.yml_nginx_service, docker-compose.yml_db_service, docker-compose.yml_redis_service, docker-compose.yml_queue_worker_service, docker-compose.yml_scheduler_service, docker-compose.yml_pos_network, docker-compose.yml_pos_db_volume, docker-compose.yml_dockerfile [INFERRED]
- **POS Sale Lifecycle (Checkout to Receipt)** — CLAUDE.md_pos_transaction_module, CLAUDE.md_sales_table, CLAUDE.md_sale_items_table, CLAUDE.md_products_table, CLAUDE.md_database_transaction_pattern, CLAUDE.md_receipt_module, CLAUDE.md_transaction_history_module [INFERRED]
- **POS UI/UX Design Pattern** — CLAUDE.md_pos_design_principles, CLAUDE.md_pos_transaction_module, CLAUDE.md_inventory_management, CLAUDE.md_ui_ux_guidelines, CLAUDE.md_ux_feedback_patterns, CLAUDE.md_ux_loading_states [INFERRED]

## Communities (100 total, 23 thin omitted)

### Community 0 - "Auth Controllers (Fortify)"
Cohesion: 0.06
Nodes (33): AuthenticatedSessionController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordController, PasswordResetLinkController, RegisteredUserController (+25 more)

### Community 1 - "Core Controllers & Routes"
Cohesion: 0.05
Nodes (16): DashboardController, ReportController, SaleController, Sale, SaleItem, Carbon\Carbon, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model (+8 more)

### Community 2 - "Composer Dependencies"
Cohesion: 0.05
Nodes (42): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+34 more)

### Community 3 - "Database Migrations"
Cohesion: 0.10
Nodes (3): Illuminate\Database\Migrations\Migration, Illuminate\Database\Schema\Blueprint, Illuminate\Support\Facades\Schema

### Community 4 - "NPM/Frontend Dependencies"
Cohesion: 0.07
Nodes (28): alpinejs, autoprefixer, concurrently, @laravel/multiplex, laravel-vite-plugin, devDependencies, alpinejs, autoprefixer (+20 more)

### Community 5 - "Composer Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 6 - "Project Architecture Rules"
Cohesion: 0.14
Nodes (25): Development Phases (10-Phase Approach), Development Rules (Pre/Post Implementation), Docker Architecture Requirements, Docker Command Rules, Laravel Architecture Patterns, POS Application, Security Patterns, Testing Procedure (+17 more)

### Community 7 - "Config & Factories"
Cohesion: 0.11
Nodes (10): CategoryFactory, ProductFactory, SaleFactory, SaleItemFactory, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Support\Facades\Hash, Illuminate\Support\Str (+2 more)

### Community 8 - "POS Module Requirements"
Cohesion: 0.17
Nodes (22): Authentication Module, Categories Table, Manajemen Kategori (Category Management) Module, Dashboard Module, Database Schema, Database Transaction Pattern, Future Extensibility Design, Inventory Management (Stok) (+14 more)

### Community 9 - "User Management Controller"
Cohesion: 0.14
Nodes (4): UserController, User, Illuminate\Foundation\Auth\User, ProfileTest

### Community 10 - "Authentication Tests"
Cohesion: 0.13
Nodes (7): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, AuthenticationTest, PasswordUpdateTest, RegistrationTest, ExampleTest, TestCase

### Community 11 - "Login Request & Auth Logic"
Cohesion: 0.17
Nodes (7): LoginRequest, ProfileUpdateRequest, Illuminate\Auth\Events\Lockout, Illuminate\Contracts\Validation\ValidationRule, Illuminate\Foundation\Http\FormRequest, Illuminate\Support\Facades\RateLimiter, Illuminate\Validation\Rule

### Community 12 - "Database Seeders"
Cohesion: 0.23
Nodes (6): CategorySeeder, DatabaseSeeder, ProductSeeder, UserSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Seeder

### Community 20 - "Email Verification"
Cohesion: 0.25
Nodes (4): Illuminate\Auth\Events\Verified, Illuminate\Support\Facades\Event, Illuminate\Support\Facades\URL, EmailVerificationTest

### Community 21 - "Password Reset"
Cohesion: 0.25
Nodes (3): Illuminate\Auth\Notifications\ResetPassword, Illuminate\Support\Facades\Notification, PasswordResetTest

### Community 23 - "Agent & Laravel Boost"
Cohesion: 0.33
Nodes (6): Agent Setup Process, Laravel Boost Guidelines, Laravel Boost Installation Command, PHP and Composer Prerequisites, Laravel Boost for AI Agents, Laravel Learning Resources

### Community 26 - "Logging Config"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 29 - "Profile Blade Partials"
Cohesion: 0.50
Nodes (3): profile.partials.delete-user-form, profile.partials.update-password-form, profile.partials.update-profile-information-form

## Knowledge Gaps
- **92 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+87 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **23 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User Management Controller` to `Auth Controllers (Fortify)`, `Core Controllers & Routes`, `Config & Factories`, `Authentication Tests`, `Login Request & Auth Logic`, `Database Seeders`, `POS Feature Tests`, `POS Controller & Product Model`, `Product Tests`, `User Management Tests`, `Category Tests`, `Dashboard Tests`, `Email Verification`, `Password Reset`, `Navigation Tests`, `Password Confirmation Tests`?**
  _High betweenness centrality (0.170) - this node is a cross-community bridge._
- **Why does `Controller` connect `Auth Controllers (Fortify)` to `Core Controllers & Routes`, `User Management Controller`, `POS Controller & Product Model`, `Category Controller`, `Settings/QRIS Controller`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._
- **Why does `PosTest` connect `POS Feature Tests` to `Core Controllers & Routes`, `User Management Controller`, `Authentication Tests`, `POS Controller & Product Model`, `Category Controller`?**
  _High betweenness centrality (0.029) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _92 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Auth Controllers (Fortify)` be split into smaller, more focused modules?**
  _Cohesion score 0.05824561403508772 - nodes in this community are weakly interconnected._
- **Should `Core Controllers & Routes` be split into smaller, more focused modules?**
  _Cohesion score 0.051577152600170505 - nodes in this community are weakly interconnected._
- **Should `Composer Dependencies` be split into smaller, more focused modules?**
  _Cohesion score 0.046511627906976744 - nodes in this community are weakly interconnected._