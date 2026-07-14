# Entity Relationship Diagram (ERD)

Diagram berikut menggambarkan struktur basis data utama pada aplikasi **SIGAP (Sistem Informasi Gangguan dan Aspirasi Publik)**. Sistem dibangun menggunakan **Laravel 13**, **Laravel Sanctum**, dan **Spatie Laravel Permission**.

```mermaid
erDiagram

    USERS ||--o{ REPORTS : submits
    REPORT_CATEGORIES ||--o{ REPORTS : categorizes
    REPORTS ||--o{ REPORT_STATUS_LOGS : has
    USERS ||--o{ REPORT_STATUS_LOGS : updates
    USERS ||--o{ ANNOUNCEMENTS : creates

    USERS }o--o{ ROLES : assigned
    ROLES }o--o{ PERMISSIONS : grants

    USERS {
        bigint id PK
        string name
        string email
        string password
        timestamp email_verified_at
        timestamp created_at
        timestamp updated_at
    }

    ROLES {
        bigint id PK
        string name
        string guard_name
    }

    PERMISSIONS {
        bigint id PK
        string name
        string guard_name
    }

    REPORT_CATEGORIES {
        bigint id PK
        string name
        string icon
        text description
        timestamp created_at
        timestamp updated_at
    }

    REPORTS {
        bigint id PK
        bigint user_id FK
        bigint report_category_id FK
        string title
        text description
        string photo_path
        string location_address
        decimal latitude
        decimal longitude
        enum status
        enum urgency
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    REPORT_STATUS_LOGS {
        bigint id PK
        bigint report_id FK
        bigint updated_by FK
        string status
        text notes
        text task_description
        timestamp created_at
    }

    ANNOUNCEMENTS {
        bigint id PK
        string title
        text content
        boolean pinned
        bigint created_by FK
        timestamp created_at
        timestamp updated_at
    }
```

## Notes

- Authentication uses **Laravel Sanctum**.
- Authorization uses **Spatie Laravel Permission**.
- Soft Deletes are implemented on the `reports` table.
- Each report belongs to one category and one user.
- Each report stores a history of status changes in `report_status_logs`.
- Admin users can publish announcements visible to all citizens.