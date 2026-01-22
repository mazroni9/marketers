# DASMe Platform – MR 20% Commission Engine & Marketers Dashboard

A comprehensive platform for managing a marketing and logistics network with partnership and commission system, consisting of:
- **Marketers Dashboard (Frontend)**: Professional React interface for marketers
- **MR 20% Commission Engine (Backend)**: Complete DDD system for calculating and managing commissions

---

## 📋 Table of Contents

- [Key Features](#key-features)
- [Project Structure](#project-structure)
- [Technologies Used](#technologies-used)
- [Installation & Setup](#installation--setup)
- [MR 20% Engine](#mr-20-engine)
- [API Endpoints](#api-endpoints)
- [Deployment](#deployment)
- [Documentation](#documentation)

---

## 🎯 Key Features

### Marketers Dashboard (Frontend)

#### Ranking & Levels System
- **Bronze Rank (Explorer)**: Client and vehicle acquisition, 10% base commission
- **Silver Rank (Documenter)**: Photography and inspection tasks, additional task commissions
- **Gold Rank (Ambassador)**: Property transfer representation, 20% commission + bonuses

#### Digital Wallet System
- Withdrawable balance
- Pending balance (for security)
- Balance under verification
- Detailed transaction history

#### Gamification System
- Leaderboard
- Achievement badges
- Progress bar for promotion
- Points and rewards system

#### Field Tasks
- Professional vehicle photography
- Initial inspection and review
- Property transfer representation
- GPS tracking for tasks

#### Referral System
- Deep referral links
- Special discount codes
- Automatic client tracking
- 20% commission on subscription and first sale

#### Hunter Tool
- Add new clients
- Add vehicles for listing
- Automatic partner linking
- Vehicle status tracking

### MR 20% Commission Engine (Backend)

#### Advanced Commission System
- **Lifetime Modes**: 
  - `lifetime`: Lifetime commission
  - `by_count`: Commission limited by transaction count
  - `by_period`: Commission limited by time period
- **Commission Tiers**: Variable commission rates based on transaction count
- **Attribution Models**: `first_click` or `last_click`
- **Scope**: Commission at product or category level

#### Program & Partner Management
- Create custom commission programs
- Register new partners
- Link partners to customers and products
- Track transactions and commissions

#### Digital Wallet System
- Calculate pending and available balance
- Track commissions by status
- Manage withdrawal operations

---

## 📁 Project Structure

```
marketers/
├── client/                          # Frontend (React)
│   ├── src/
│   │   ├── components/              # Shared components
│   │   ├── pages/                   # Application pages
│   │   ├── contexts/                # Context API
│   │   └── hooks/                   # Custom Hooks
│   └── public/                      # Static files
│
├── app/Mr20/                        # Backend Module (Laravel DDD)
│   ├── Domain/                      # Domain Layer
│   │   ├── Merchants/               # Merchant entities
│   │   ├── Partners/                # Partner entities
│   │   ├── Catalog/                 # Product entities
│   │   └── Customers/               # Customer entities
│   │
│   ├── Application/                 # Application Layer
│   │   ├── Merchants/               # Merchant handlers
│   │   ├── Partners/                # Partner handlers
│   │   ├── Links/                   # Link handlers
│   │   ├── Transactions/           # Transaction handlers
│   │   ├── Wallet/                  # Wallet handlers
│   │   └── Services/                # Services (LifetimeRulesEngine, CommissionCalculator)
│   │
│   └── Infrastructure/              # Infrastructure Layer
│       ├── Http/Controllers/        # API Controllers
│       └── Persistence/Eloquent/    # Repositories
│
├── mr20/                            # Legacy Module (for compatibility)
│   ├── app/
│   │   ├── Models/                  # Eloquent Models
│   │   ├── Services/                # Services
│   │   └── Listeners/               # Event Listeners
│   ├── database/migrations/         # Database Migrations
│   └── routes/                      # API Routes
│
├── docs/                             # Documentation
│   ├── MR20-SPEC.md                 # MR 20% Engine specifications
│   └── DASM-EVENTS-EXPLORATION.md   # DASM events exploration
│
└── Public/                           # Original Arabic documentation
```

---

## 🛠 Technologies Used

### Frontend
- **React 19** + **TypeScript**
- **Tailwind CSS 4** for styling
- **Wouter** for routing
- **Radix UI** for components
- **Google Maps API** for maps
- **Vite** as build tool
- **pnpm** for package management

### Backend
- **Laravel** (PHP)
- **Domain-Driven Design (DDD)**
- **Eloquent ORM**
- **RESTful API**
- **Event-Driven Architecture**

---

## 🚀 Installation & Setup

### Requirements
- **Node.js 18+**
- **pnpm 10+**
- **PHP 8.1+** (for backend)
- **Composer** (for backend)
- **Laravel** (for backend)

### Frontend Installation

```bash
# Install dependencies
pnpm install

# Run development server
pnpm dev

# Build for production
pnpm build

# Preview production build
pnpm preview
```

### Backend Installation (MR20 Module)

```bash
# Navigate to Laravel project
cd /path/to/dasm-platform

# Install dependencies
composer install

# Copy MR20 files to project
# (Merge app/Mr20/ with app/ in Laravel)

# Run migrations
php artisan migrate

# Register Service Provider
# In config/app.php:
# App\Mr20\Providers\Mr20ServiceProvider::class
```

---

## 🔧 MR 20% Engine

### Architecture (DDD)

The project is organized following **Domain-Driven Design**:

- **Domain Layer**: Core entities (Merchants, Partners, Products, etc.)
- **Application Layer**: Use Cases (Handlers) and Services
- **Infrastructure Layer**: Controllers, Repositories, External Services

### Main Components

#### 1. Handlers (Application Layer)
- `CreateMerchantHandler` - Create new merchant
- `CreateProgramHandler` - Create commission program
- `RegisterPartnerHandler` - Register new partner
- `CreateLinkHandler` - Link partner to customer and product
- `ReportTransactionHandler` - Report sale transaction
- `GetWalletSummaryHandler` - Wallet summary
- `GetPartnerCommissionsHandler` - Commissions list

#### 2. Services (Application Layer)
- `LifetimeRulesEngine` - Lifetime rules engine (lifetime/by_count/by_period)
- `CommissionCalculator` - Commission calculation (percentage/flat)
- `WalletService` - Wallet management

#### 3. Repositories (Infrastructure Layer)
- `MerchantEloquentRepository`
- `PartnerEloquentRepository`
- `ProgramEloquentRepository`
- `LinkEloquentRepository`
- `TransactionEloquentRepository`
- `CommissionEloquentRepository`
- And more...

---

## 📡 API Endpoints

### Admin APIs
- `POST /api/admin/merchants` - Create new merchant

### Merchant APIs (Requires X-API-KEY)
- `POST /api/v1/products` - Register product
- `POST /api/v1/programs` - Create commission program
- `POST /api/v1/programs/{id}/tiers` - Add commission tiers
- `POST /api/v1/links` - Link partner to customer and product
- `POST /api/v1/transactions/report` - Report sale transaction

### Partner APIs (Requires Authorization Bearer Token)
- `GET /api/partner/programs/available` - Available programs
- `POST /api/partner/programs/enroll` - Enroll in program
- `GET /api/partner/wallet/summary` - Wallet summary
- `GET /api/partner/commissions` - Commissions list

### Public APIs
- `POST /api/public/partners/register` - Register new partner

### Response Format

All APIs return the same format:

```json
{
  "success": true,
  "data": { ... }
}
```

Or in case of error:

```json
{
  "success": false,
  "error": {
    "message": "Error message"
  }
}
```

---

## 🔗 Integration with DASM-Platform

The project includes ready **Listeners** for integration with DASM-Platform events:

### Available Listeners

1. **SyncProductWithMr20**
   - Listens to: `CarCreated`
   - Executes: Register car as product in MR20

2. **SyncLinkWithMr20**
   - Listens to: `CarPartnerAssigned`
   - Executes: Link partner to car in MR20

3. **ReportTransactionToMr20**
   - Listens to: `CarSold`
   - Executes: Report sale transaction for commission calculation

### Registration in EventServiceProvider

```php
// app/Providers/EventServiceProvider.php

protected $listen = [
    \App\Events\CarCreated::class => [
        \App\Listeners\Mr20\SyncProductWithMr20::class,
    ],
    \App\Events\CarPartnerAssigned::class => [
        \App\Listeners\Mr20\SyncLinkWithMr20::class,
    ],
    \App\Events\CarSold::class => [
        \App\Listeners\Mr20\ReportTransactionToMr20::class,
    ],
];
```

**Note**: See `docs/DASM-EVENTS-EXPLORATION.md` for complete details.

---

## 📦 Deployment

### Deploy to Vercel (Frontend)

1. **Setup project on GitHub**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/YOUR_USERNAME/marketers.git
   git push -u origin main
   ```

2. **Deploy to Vercel**
   - Go to [Vercel](https://vercel.com)
   - Click "Import Project"
   - Select repository from GitHub
   - Vercel will auto-detect settings from `vercel.json`
   - Click "Deploy"

3. **Required Vercel Settings**
   - Build Command: `pnpm build`
   - Output Directory: `dist/public`
   - Install Command: `pnpm install`

### Deploy to Server (Backend)

The MR20 module is integrated with the main Laravel project (DASM-Platform).

---

## 📚 Documentation

### Available Documentation

1. **docs/MR20-SPEC.md**
   - Complete MR 20% Engine specifications
   - Entity and API details
   - Lifetime and Tiers rules

2. **docs/DASM-EVENTS-EXPLORATION.md**
   - DASM-Platform events exploration
   - How to link Listeners with Events

3. **app/Mr20/MIGRATION_PLAN.md**
   - Migration plan from traditional Laravel to DDD
   - Implementation steps

4. **Public/** (7 files)
   - Original Arabic project documentation

---

## 🎯 Main Pages (Frontend)

- `/` - Home page (Dashboard)
- `/wallet` - Digital wallet
- `/add-client` - Hunter tool (add client/vehicle)
- `/quotes-archive` - Quotes archive
- `/referrals` - Referrals and clients
- `/tasks` - Field tasks
- `/achievements` - Achievements and ranks

---

## 🔐 Security

### API Authentication

- **Merchants**: Use `X-API-KEY` header
- **Partners**: Use `Authorization: Bearer <JWT_TOKEN>`

### Sensitive Data

- All passwords are hashed
- API Keys are auto-generated when creating merchant
- JWT Tokens for partners (linked to main Auth system)

---

## 🧪 Testing

```bash
# Frontend Tests (if available)
pnpm test

# Backend Tests
php artisan test
```

---

## 📝 License

MIT License

---

## 🤝 Contributing

To contribute to the project:
1. Fork the project
2. Create a new branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📞 Support

For help and support, please contact the development team.

---

## 🔄 Future Updates

- [ ] Improve testing system
- [ ] Add Real-time notifications
- [ ] Improve API performance
- [ ] Add Analytics dashboard
- [ ] Multi-currency support
- [ ] Improve wallet system

---

**Developed by DASMe Team** 🚀
