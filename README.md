# Tattoo CRM - Core Models & Relationships

A comprehensive Laravel-based CRM system specifically designed for tattoo artists and studios, featuring client management, booking forms, submission handling, polymorphic tagging, and Stripe subscription management.

## Overview

This system provides a complete foundation for tattoo artists to manage their booking process, client relationships, and submission workflows. Built with Laravel 12, it uses ULID primary keys for all new models and includes comprehensive testing.

## Core Models

### Organization (with Stripe Cashier Integration)

- **Purpose**: Manages studio/shop entities with subscription billing
- **Key Features**:
    - Stripe integration via Laravel Cashier
    - Subscription-based artist and submission limits
    - Payment method storage
    - Trial period management
- **Subscription Plans**: Solo, Studio Basic, Studio Pro, Enterprise
- **Relationships**: `hasMany(Artist)`, `belongsTo(User, 'owner_user_id')`

### Artist

- **Purpose**: Individual tattoo artists within organizations
- **Key Features**:
    - Username-based route model binding
    - Booking form management (open/close/auto-close)
    - Role-based permissions (owner, admin, artist)
    - Status tracking (active, inactive, pending_invite, exited)
- **Route Binding**: `/{artist}` resolves by username (e.g., `/sarah-ink`)
- **Relationships**: `belongsTo(User)`, `belongsTo(Organization)`, `hasMany(Client)`, `hasMany(CustomField)`, `hasMany(Submission)`, `hasMany(Tag)`

### Client (No Authentication Required)

- **Purpose**: Potential tattoo clients managed per artist
- **Key Features**:
    - Email uniqueness enforced per artist
    - Emergency contact information
    - Birth date tracking for age verification
- **Relationships**: `belongsTo(Artist)`, `hasMany(Submission)`, `morphToMany(Tag)`

### CustomField

- **Purpose**: Dynamic form fields for booking forms
- **Field Types**: text, textarea, select, radio, checkbox, file
- **Key Features**:
    - Validation rule generation
    - Sort ordering
    - Required field support
    - Active/inactive status
- **Relationships**: `belongsTo(Artist)`, `hasMany(CustomFieldOption)`, `hasMany(SubmissionCustomFieldEntry)`

### CustomFieldOption

- **Purpose**: Options for select/radio custom fields
- **Relationships**: `belongsTo(CustomField)`

### Submission (New)

- **Purpose**: Client booking requests and their lifecycle
- **Statuses**: pending, under_review, approved, rejected, scheduled, completed, cancelled
- **Key Features**:
    - Client data snapshots at submission time
    - Artist notes and rejection reasons
    - Price and duration estimates
    - Status management methods
- **Relationships**: `belongsTo(Artist)`, `belongsTo(Client)`, `hasMany(SubmissionCustomFieldEntry)`, `morphToMany(Tag)`

### SubmissionCustomFieldEntry (New)

- **Purpose**: Client responses to custom form fields
- **Key Features**:
    - File upload support with original filenames
    - Formatted value display based on field type
    - File size calculation and formatting
- **Relationships**: `belongsTo(Submission)`, `belongsTo(CustomField)`

### Tag (New - Polymorphic)

- **Purpose**: Categorization system for clients and submissions
- **Key Features**:
    - Artist-specific tags (unique names per artist)
    - Color coding for UI
    - Usage tracking
    - Polymorphic tagging (clients, submissions)
- **Common Tag Types**: Style tags, Size tags, Status tags, Placement tags, Color tags
- **Relationships**: `belongsTo(Artist)`, `morphedByMany(Client)`, `morphedByMany(Submission)`

## ULID Configuration

All new models use ULID (Universally Unique Lexicographically Sortable Identifier) as primary keys:

- 26-character string format
- Timestamp-based ordering
- URL-safe
- More secure than auto-increment IDs

```php
// All new models include:
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Model extends Model
{
    use HasUlids;
}
```

## Route Model Binding

Artists are bound by username instead of ID for user-friendly URLs:

```php
// routes/web.php
Route::bind('artist', function ($value) {
    return Artist::where('username', $value)->firstOrFail();
});

// These routes work:
Route::get('/{artist}', [PublicBookingController::class, 'show']); // /sarah-ink
Route::post('/{artist}/submit', [PublicBookingController::class, 'submit']); // /mike-traditional/submit
```

## Stripe Cashier Integration

### Subscription Limits

```php
// Organization subscription methods
$organization->getAllowedArtistCount(); // Based on plan
$organization->canAddArtist(); // Check current vs limit
$organization->getMonthlySubmissionLimit(); // Based on plan
$organization->getCurrentMonthSubmissionCount(); // Current usage
$organization->canAcceptSubmission(); // Check submission limits
```

### Plan Limits

- **Free Tier**: 1 artist, 50 submissions/month
- **Solo Monthly**: 1 artist, 200 submissions/month
- **Studio Basic**: 5 artists, 1,000 submissions/month
- **Studio Pro**: 15 artists, 5,000 submissions/month
- **Enterprise**: 999 artists, unlimited submissions

## Key Features

### Submission Workflow

```php
// Create submission with custom field responses
$submission = $artist->submissions()->create([...]);

// Add field responses
$submission->customFieldEntries()->create([
    'custom_field_id' => $field->id,
    'value' => 'Dragon sleeve design'
]);

// Manage submission status
$submission->approve();
$submission->reject('Too complex for requested timeline');

// Check status
$submission->isPending();
$submission->isApproved();
```

### Polymorphic Tagging

```php
// Tag clients and submissions
$client->tags()->attach($regularClientTag);
$submission->tags()->attach([$rushTag, $traditionalTag]);

// Find tagged items
$rushSubmissions = $artist->submissions()
    ->whereHas('tags', function($q) use ($rushTag) {
        $q->where('tags.id', $rushTag->id);
    })->get();
```

### File Upload Handling

```php
// File field entries include metadata
$fileEntry = $submission->customFieldEntries()
    ->where('custom_field_id', $photoField->id)
    ->first();

if ($fileEntry->hasFile()) {
    echo $fileEntry->getFileUrl();
    echo $fileEntry->getFileSizeFormatted(); // "2.4 MB"
}
```

### Booking Form Management

```php
// Artist booking form controls
$artist->isBookingFormOpen(); // Check current status
$artist->closeBookingForm(); // Manual close
$artist->openBookingForm(); // Manual open
$artist->closeBookingFormAt(Carbon::tomorrow()); // Auto-close

// Organization submission limits
$artist->canAcceptSubmissions(); // Checks form status + org limits
```

## Database Structure

### Enhanced Migrations

- **Soft deletes** on all new tables
- **Timezone-aware timestamps** (`timestampsTz()`)
- **Strategic indexing** for performance
- **Foreign key constraints** with cascade deletes
- **Unique constraints** where appropriate

### Enum Integration

```php
// All status and type fields use enums
enum SubmissionStatusType: string {
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    // ...
}
```

## Testing

Comprehensive test suite with **80+ tests** covering:

### Feature Tests

- **SubmissionTest**: 28 tests - ULID generation, relationships, status management, file handling
- **ArtistTest**: 17 tests - ULID generation, booking form management, route binding
- **ClientTest**: 11 tests - ULID generation, relationships, constraints
- **CustomFieldTest**: 20 tests - ULID generation, validation rules, factory states
- **ArtistRouteBindingTest**: 4 tests - Username-based routing

### Test Coverage

- ULID generation and format validation
- Model relationships and constraints
- Status transitions and business logic
- Factory states and realistic data generation
- Route model binding functionality
- File upload handling
- Polymorphic tagging
- Enum casting and comparisons

## Seeding

The `TattooCrmSeeder` creates a complete development dataset:

- **1 Organization** with Stripe integration setup
- **3 Core Artists** (owner, admin, regular) + additional test artists
- **30+ Clients** distributed across artists
- **20+ Custom Fields** with realistic tattoo form questions
- **35+ Submissions** with various statuses and responses
- **33 Tags** across style, size, status, placement, and color categories
- **Tag assignments** to clients and submissions
- **Custom field entries** for each submission

## Usage Examples

### Create Complete Submission

```php
// Client submits booking request
$submission = Submission::create([
    'artist_id' => $artist->id,
    'client_id' => $client->id,
    'client_name' => $client->name, // Snapshot
    'client_email' => $client->email, // Snapshot
    'client_phone' => $client->phone, // Snapshot
    'submitted_at' => now(),
]);

// Add responses to custom fields
foreach ($customFields as $field) {
    $submission->customFieldEntries()->create([
        'custom_field_id' => $field->id,
        'value' => $request->input("field_{$field->id}"),
        'file_path' => $field->field_type === 'file' ? $uploadPath : null,
        'file_original_name' => $field->field_type === 'file' ? $originalName : null,
    ]);
}

// Tag the submission
$submission->tags()->attach([$styleTag->id, $urgencyTag->id]);
```

### Artist Dashboard Query Examples

```php
// Get pending submissions with tag counts
$pendingSubmissions = $artist->submissions()
    ->pending()
    ->with(['client', 'tags', 'customFieldEntries.customField'])
    ->latest('submitted_at')
    ->get();

// Get submission statistics
$stats = [
    'pending' => $artist->submissions()->pending()->count(),
    'approved' => $artist->submissions()->approved()->count(),
    'this_month' => $artist->submissions()
        ->whereBetween('submitted_at', [now()->startOfMonth(), now()->endOfMonth()])
        ->count(),
];
```

### Tag Management

```php
// Create artist-specific tags
$traditionalTag = $artist->tags()->create([
    'name' => 'Traditional',
    'color' => '#dc2626',
    'description' => 'Traditional American style tattoos'
]);

// Get popular tags with usage counts
$popularTags = $artist->tags()
    ->withCount(['clients', 'submissions'])
    ->orderByRaw('(clients_count + submissions_count) DESC')
    ->take(10)
    ->get();
```

## File Storage

Submission file uploads are organized as:

```
storage/app/public/submissions/{artist_id}/{submission_id}/filename.jpg
```

Files are accessible via the public storage link:

```php
// In SubmissionCustomFieldEntry model
public function getFileUrl() {
    return $this->file_path ? asset('storage/' . $this->file_path) : null;
}
```

## Next Steps

With the core models and relationships in place, the system is ready for:

1. **Public Booking Controllers** - Handle client form submissions
2. **Artist Dashboard** - Manage submissions, clients, and settings
3. **Organization Management** - Billing, artist management, usage analytics
4. **API Endpoints** - Mobile app support or integrations
5. **Email Notifications** - Submission status updates
6. **File Upload Processing** - Image optimization and validation
7. **Advanced Analytics** - Submission trends, client insights
8. **Multi-language Support** - International market expansion

## Architecture Benefits

- **Scalable**: ULID-based design supports high-volume operations
- **Secure**: No auto-increment ID exposure, proper relationships
- **Flexible**: Polymorphic tagging and dynamic custom fields
- **Testable**: Comprehensive test coverage with realistic factories
- **Maintainable**: Clean separation of concerns and clear relationships
- **Business-Ready**: Stripe integration for immediate monetization
