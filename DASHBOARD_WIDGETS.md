# Dashboard Widgets System

## Overview

The Dashboard Widgets system provides lawyers with instant visibility over critical business metrics:
- **Clients:** Total registered clients
- **Legal Cases:** Case status breakdown and totals
- **Payments:** Revenue tracking and payment method analysis

All widgets use optimized database aggregates for best performance.

---

## Widget Components

### 1. **StatsOverviewWidget** (Overview KPI Cards)
**Location:** `app/Filament/Widgets/StatsOverviewWidget.php`

Displays 5 key performance indicators:

| Metric | Description |
|--------|-------------|
| **Total Clients** | Count of all registered clients |
| **Total Cases** | Count of all legal cases in the system |
| **Pending Payments** | Total amount and count of unpaid invoices |
| **Paid Payments** | Total amount and count of completed payments |
| **Total Revenue** | Sum of all paid payments |

**Features:**
- Color-coded badges (info, primary, warning, success)
- Direct metrics without external dependencies
- Uses DB aggregates (`count()`, `sum()`) for optimization

**Database Queries:**
```php
Client::count()
LegalCase::count()
Payment::where('status', PaymentStatus::Pending->value)->sum('amount')
Payment::where('status', PaymentStatus::Paid->value)->count()
Payment::where('status', PaymentStatus::Paid->value)->sum('amount')
```

---

### 2. **CasesStatusWidget** (Case Status Breakdown Chart)
**Location:** `app/Filament/Widgets/CasesStatusWidget.php`

Displays case distribution using a doughnut chart.

**Case Status Values:**
- 🟠 **Open (مفتوحة)** - Amber color
- 🔵 **Closed (مقفولة)** - Green color  
- 🟣 **Won (مكتسبة)** - Purple color

**Features:**
- Visual doughnut chart for quick status overview
- Bilingual labels (Arabic + English)
- Optimized grouping query

**Database Query:**
```php
LegalCase::select('status', DB::raw('count(*) as count'))
    ->groupBy('status')
    ->get()
```

---

### 3. **PaymentsSummaryWidget** (Payment Collection Analysis)
**Location:** `app/Filament/Widgets/PaymentsSummaryWidget.php`

Line chart showing collected vs. pending amounts by payment method.

**Payment Methods:**
- 💵 **Cash (نقدي)** - Gray color
- 🏦 **Instapay (إنستاباي)** - Info/Blue color
- 📱 **Vodafone Cash (فودافون كاش)** - Primary/Blue color

**Features:**
- Dual-line visualization (collected in green, pending in amber)
- Payment method breakdown
- High-level financial overview

**Database Queries:**
```php
// For each payment method:
Payment::where('method', $method)
    ->where('status', PaymentStatus::Paid->value)
    ->sum('amount')

Payment::where('method', $method)
    ->where('status', PaymentStatus::Pending->value)
    ->sum('amount')
```

---

### 4. **UpcomingSessionsWidget** (Upcoming Sessions - Placeholder)
**Location:** `app/Filament/Widgets/UpcomingSessionsWidget.php`  
**View:** `resources/views/filament/widgets/upcoming-sessions-widget.blade.php`

**Features:**
- Fetches sessions for next 7 days
- Sorted by date ascending
- Shows associated case title
- Placeholder UI for future expansion

**Currently:**
- Queries last 10 upcoming sessions
- Returns empty message if no sessions exist
- Uses `created_at` field (adjust based on your schema)

**Future Enhancement:**
```php
// Adjust field name to match your actual Sessions table structure
// Examples: scheduled_at, date, start_time, etc.
Session::whereDate('date_field', '>=', now())
    ->whereDate('date_field', '<=', now()->addDays(7))
```

---

## Dashboard Registration

**Location:** `app/Filament/Pages/Dashboard.php`

The custom Dashboard page automatically registers all widgets:

```php
public function getWidgets(): array
{
    return [
        StatsOverviewWidget::class,
        CasesStatusWidget::class,
        PaymentsSummaryWidget::class,
        UpcomingSessionsWidget::class,
    ];
}
```

**Sort Order:**
Widgets display in dashboard based on `protected static ?int $sort` value:
1. StatsOverviewWidget (sort: 1)
2. CasesStatusWidget (sort: 2)
3. PaymentsSummaryWidget (sort: 3)
4. UpcomingSessionsWidget (sort: 4)

---

## Architecture & Optimization

### Query Optimization

All widgets use database aggregates to minimize query overhead:

✅ **Good** (Database aggregation)
```php
Payment::where('status', PaymentStatus::Paid->value)->sum('amount')
```

❌ **Avoid** (PHP-level processing)
```php
$payments = Payment::all();
$sum = $payments->where('status', PaymentStatus::Paid)->sum('amount');
```

### Reusable Query Scopes

For future optimization, consider adding query scopes to models:

```php
// In Payment model
public function scopePaid($query)
{
    return $query->where('status', PaymentStatus::Paid->value);
}

public function scopePending($query)
{
    return $query->where('status', PaymentStatus::Pending->value);
}

// Usage
Payment::paid()->sum('amount')
Payment::pending()->count()
```

### Cache Strategy (Optional)

For high-traffic dashboards, consider caching widget data:

```php
protected function getData(): array
{
    return cache()->remember('case-stats', 3600, function () {
        return $this->getCaseStatusStats();
    });
}
```

---

## Extending the Widgets

### Add a New Stat to StatsOverviewWidget

```php
Stat::make('Total Assignments', $this->getTotalAssignments())
    ->description('Cases awaiting assignment')
    ->descriptionIcon('heroicon-m-document-text')
    ->color('secondary'),
```

### Create a New Chart Widget

```php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class RevenueExpenseWidget extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue vs Expenses';
    protected static ?int $sort = 5;
    
    protected function getData(): array
    {
        // Your chart data
    }
    
    protected function getType(): string
    {
        return 'bar'; // or 'line', 'pie', etc.
    }
}
```

Then add to `Dashboard::getWidgets()`:

```php
public function getWidgets(): array
{
    return [
        // ... existing widgets
        RevenueExpenseWidget::class,
    ];
}
```

---

## Database Relations Reference

### Client Model
```php
$client->legalCases()      // BelongsToMany through case_client
$client->payments()         // HasMany on Payment::client_id
```

### LegalCase Model
```php
$case->clients()            // BelongsToMany through case_client
$case->payments()           // HasMany on Payment::legal_case_id
$case->sessions()           // HasMany on Session::case_id
$case->category()           // BelongsTo on Case::category_id
$case->court()              // BelongsTo on Case::court_id
```

### Payment Model
```php
$payment->legalCase()       // BelongsTo
$payment->client()          // BelongsTo
```

### Session Model
```php
$session->legalCase()       // BelongsTo on Session::case_id
```

---

## UI/UX Standards

### Icon Reference (HeroIcons)
- `heroicon-m-users` - Clients/People
- `heroicon-m-document-text` - Cases/Documents
- `heroicon-m-clock` - Pending/Time
- `heroicon-m-check-circle` - Completed/Success
- `heroicon-m-banknotes` - Revenue/Money

### Color Scheme
- **Info** - Light Blue (info)
- **Primary** - Amber (primary)
- **Warning** - Amber (warning)
- **Success** - Green (success)
- **Secondary** - Additional stats

### Arabic Language Support

All widgets support Arabic labels and RTL layout:
- Enum labels: `PaymentMethod::label()`, `CaseStatus::label()`
- View layout automatically respects RTL
- Bilingual labels in charts

---

## Troubleshooting

### Widgets Not Showing

1. **Check discovery in AdminPanelProvider:**
   ```php
   ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
   ```

2. **Verify Dashboard class:**
   - Must extend `Filament\Pages\Dashboard`
   - Must implement `getWidgets()` method
   - Located in `app/Filament/Pages/Dashboard.php`

3. **Clear cache:**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

### Incomplete Data

- Verify all models have correct relationships
- Check migration dates and column names
- Ensure PaymentStatus and CaseStatus enums match database values

### Session Widget Empty

Update the Session model and query to use correct date field:
```php
// Check your sessions table structure
php artisan tinker
>>> Schema::getColumns('sessions')
>>> Session::first()

// Then update UpcomingSessionsWidget query accordingly
```

---

## Future Enhancements

1. **Session Widget Expansion**
   - Real date field implementation
   - Session duration and location
   - Assigned judge/court info

2. **Additional Metrics**
   - Case win rate percentage
   - Average case duration
   - Client retention rate
   - Payment success rate by method

3. **Performance Tracking**
   - Query result caching
   - Scheduled data aggregation
   - Background job optimization

4. **Interactive Features**
   - Date range filters
   - Drill-down capabilities
   - Export to PDF/Excel
