# Dashboard Widgets - Developer Quick Reference

## 🚀 Quick Start

### View Current Widgets
1. Navigate to `/admin` dashboard
2. See all 4 widgets automatically displayed
3. Check widget order by `sort` value

### Add a New Widget

#### Step 1: Create the Widget Class
```bash
# Create new Stats widget
touch app/Filament/Widgets/NewStatsWidget.php
```

#### Step 2: Implement the Widget
**Example - Stats Widget:**
```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NewStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        return [
            Stat::make('Metric Name', $this->getValue())
                ->description('Description')
                ->descriptionIcon('heroicon-m-icon-name')
                ->color('success'),
        ];
    }

    private function getValue(): string
    {
        // Your logic here
        return '0';
    }
}
```

**Example - Chart Widget:**
```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class NewChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart Title';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Dataset',
                    'data' => [1, 2, 3],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar'],
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // 'line', 'pie', 'doughnut', etc.
    }
}
```

#### Step 3: Register in Dashboard
Edit `app/Filament/Pages/Dashboard.php`:
```php
public function getWidgets(): array
{
    return [
        StatsOverviewWidget::class,
        CasesStatusWidget::class,
        PaymentsSummaryWidget::class,
        UpcomingSessionsWidget::class,
        NewStatsWidget::class,        // ← Add here
        NewChartWidget::class,        // ← Add here
    ];
}
```

---

## 📊 Available Widget Base Classes

### 1. StatsOverviewWidget
**For:** Simple statistics cards (KPIs)

```php
extends StatsOverviewWidget as BaseWidget

// Returns array of Stat objects
protected function getStats(): array {
    return [
        Stat::make('Label', $value)
            ->description('Text')
            ->descriptionIcon('heroicon-name')
            ->color('primary'),
    ];
}
```

**Color Options:** `primary`, `success`, `warning`, `danger`, `info`

### 2. ChartWidget
**For:** Visual charts (graphs)

```php
extends ChartWidget

protected function getData(): array {
    // Returns datasets and labels
}

protected function getType(): string {
    // Returns: 'line', 'bar', 'pie', 'doughnut', 'area', etc.
}
```

### 3. Widget
**For:** Custom HTML/Blade views

```php
extends Widget

protected static string $view = 'filament.widgets.custom-widget';

// Access in Blade: @livewire property
```

---

## 💾 Database Query Patterns

### Count Records
```php
use App\Models\Client;

Client::count()  // Total: 42

// Or filtered:
LegalCase::where('status', 'open')->count()  // 15
```

### Sum Values
```php
use App\Models\Payment;
use App\Enums\PaymentStatus;

Payment::where('status', PaymentStatus::Paid->value)
    ->sum('amount')  // 15000.50
```

### Group By
```php
use Illuminate\Support\Facades\DB;

LegalCase::select('status', DB::raw('count(*) as count'))
    ->groupBy('status')
    ->get()
    // [
    //   {'status': 'open', 'count': 10},
    //   {'status': 'closed', 'count': 5},
    // ]
```

### Query Scope (Optional Best Practice)
```php
// In Model
public function scopePaid($query) {
    return $query->where('status', PaymentStatus::Paid->value);
}

// In Widget
Payment::paid()->sum('amount')
```

---

## 🎨 Color & Icon Reference

### Stat Colors
```php
->color('primary')    // Blue
->color('success')    // Green
->color('warning')    // Amber
->color('danger')     // Red
->color('info')       // Light Blue
```

### HeroIcons (Commonly Used)
```
heroicon-m-users               // People/Clients
heroicon-m-document-text       // Documents/Cases
heroicon-m-clock              // Time/Pending
heroicon-m-check-circle       // Checkmark/Success
heroicon-m-banknotes          // Money/Revenue
heroicon-m-chart-bar          // Charts
heroicon-m-exclamation-circle // Alert
heroicon-m-phone              // Contact
heroicon-m-envelope           // Email
heroicon-m-calendar           // Calendar
heroicon-m-map-pin            // Location
```

Full list: https://heroicons.com/

---

## 🌍 Bilingual Support

### Arabic Labels in Enums
```php
// Already implemented in PaymentMethod, CaseStatus, PaymentStatus
enum PaymentStatus: string {
    case Paid = 'paid';
    
    public function label(): string {
        return match ($this) {
            self::Paid => 'مدفوع',
        };
    }
}

// Usage in widget:
PaymentStatus::Paid->label()  // Returns: "مدفوع"
```

### Chart with Arabic Labels
```php
protected function getData(): array
{
    return [
        'datasets' => [...],
        'labels' => [
            'الحالة المفتوحة',      // Arabic
            'Open Status',           // English
            'In Progress',
        ],
    ];
}
```

---

## 🔧 Common Modifications

### Change Widget Sort Order
```php
// In widget class
protected static ?int $sort = 3;  // 1 = first, 2 = second, etc.
```

### Change Widget Title (Heading)
```php
// In ChartWidget class
protected static ?string $heading = 'New Title';

// In Dashboard page
protected static ?string $title = 'Dashboard Title';
```

### Change Chart Type
```php
protected function getType(): string
{
    return 'bar';  // was 'line'
}
```

### Hide Widget Temporarily
In `Dashboard::getWidgets()`:
```php
public function getWidgets(): array
{
    return [
        StatsOverviewWidget::class,
        // CasesStatusWidget::class,  ← Commented = hidden
        PaymentsSummaryWidget::class,
    ];
}
```

### Cache Widget Data (Performance)
```php
protected function getData(): array
{
    return cache()->remember('chart-data', 600, function () {
        // Expensive query here
        return $this->calculateData();
    });
}
```

---

## 🧪 Testing

### Test Widget Renders
```php
// In Feature Test
test('dashboard widgets render', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)
        ->get('/admin');
    
    $response->assertSuccessful();
    $response->assertSee('Total Clients');
});
```

### Test Widget Data
```php
// In Unit Test
test('stats widget calculates total clients', function () {
    Client::factory(5)->create();
    
    $widget = new StatsOverviewWidget();
    $stats = $widget->getStats();
    
    expect($stats[0]->value)->toBe(5);
});
```

---

## 🐛 Debugging

### Check Widget Discovery
```bash
php artisan model:show App\\Filament\\Widgets\\StatsOverviewWidget
```

### Clear Cache
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Check Database Queries
```php
// In widget, enable query logging:
\Illuminate\Support\Facades\DB::enableQueryLog();

// Your query here

dd(\Illuminate\Support\Facades\DB::getQueryLog());
```

### Laravel Debugbar (If Installed)
- Install: `composer require barryvdh/laravel-debugbar`
- View all widget queries in debugbar

---

## 📱 Responsive Design

Filament widgets automatically responsive:
- ✅ Mobile (single column)
- ✅ Tablet (2 columns)
- ✅ Desktop (4 columns - for small widgets)

No additional CSS needed.

---

## 🎯 Best Practices

✅ **DO:**
- Use database aggregates (count, sum, groupBy)
- Cache expensive queries (600-3600 seconds)
- Use Stat objects in StatsOverviewWidget
- Follow sortby convention (1, 2, 3...)
- Document widget purpose
- Use descriptive stat names

❌ **DON'T:**
- Load all records in PHP then calculate (N+1)
- Loop through collections for counting
- Update cache too frequently
- Leave debug code in production
- Hard-code values
- Mix business logic with presentation

---

## 📚 Additional Resources

- [Filament Widgets Docs](https://filament.io/docs/3.x/panels/dashboard/widgets)
- [Laravel Queries](https://laravel.com/docs/queries)
- [HeroIcons Library](https://heroicons.com/)

---

## 💡 Quick Snippets

### Get Stats
```php
$totalClients = Client::count();
$totalRevenue = Payment::paid()->sum('amount');
$pendingCount = Payment::pending()->count();
```

### Format Currency
```php
'EGP ' . number_format(1500.50, 2)  // "EGP 1,500.50"
```

### Get Enum Label
```php
PaymentStatus::Paid->label()          // "مدفوع" (Arabic)
PaymentMethod::Cash->label()          // "نقدي"
CaseStatus::OPEN->label()             // "مفتوحة"
```

### Next 7 Days
```php
Session::whereDate('date_field', '>=', now())
    ->whereDate('date_field', '<=', now()->addDays(7))
    ->get()
```

---

**Need help? Check `DASHBOARD_WIDGETS.md` for detailed documentation.**
