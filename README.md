# Crop Entity

## 1. Purpose
The Crop entity represents agricultural crops defined by farmers. It is linked to crop plans and soil analysis values.

---

## 2. Table: crops

| Column      | Type               | Description                          |
|-------------|--------------------|------------------------------------|
| id          | int (PK)           | Primary key                        |
| name        | JSON               | Crop name (translatable)           |
| description | JSON (nullable)     | Crop description (translatable)    |
| farmer_id   | int (nullable, FK)  | Creator farmer (nullable foreign key) |
| created_at  | timestamp          | Creation date                      |
| updated_at  | timestamp          | Update date                       |

---

## 3. Relationships

| Method               | Type       | Related To         |
|----------------------|------------|--------------------|
| cropPlans()          | hasMany    | CropPlan           |
| idealAnalysisValues() | hasMany    | IdealAnalysisValue |
| farmer()             | belongsTo  | User (Farmer)      |

---

## 4. Authorization (CropPolicy)

| Action   | Allowed Roles                                   |
|----------|------------------------------------------------|
| viewAny  | ProgramManager, Farmer, AgriculturalAlert, Others |
| view     | Same as viewAny                                |
| create   | Farmer only                                    |
| update   | Only the farmer who created the crop           |
| delete   | Only the farmer who created the crop           |

---

## 5. CropService (Main Methods)

- `getAll()`: Get list of crops (with filters)  
- `get($crop)`: Get a single crop  
- `store($data)`: Create new crop (Farmer only)  
- `update($data, $crop)`: Update name or description  
- `destroy($crop)`: Delete only if no active crop plans  

---

## Notes

- Fields `name` and `description` are translatable via Spatie\Translatable\HasTranslations.  
- `farmer_id` is auto-assigned from the authenticated user.  
- Relations follow Laravel Eloquent standards.

---

# Crop Plan Entity

## 1. Purpose
The CropPlan entity represents a specific agricultural plan for planting a crop on a specific farmland. It includes planning and tracking data such as planting/harvesting dates, seed details, and area size.

---

## 2. Table: crop_plans

| Column                | Type                   | Description                     |
|-----------------------|------------------------|--------------------------------|
| id                    | int (PK)               | Primary key                    |
| crop_id               | foreign key            | Related crop                   |
| planned_by            | foreign key            | User who created the plan (planner) |
| land_id               | foreign key            | The farmland where the plan is applied |
| planned_planting_date | date                   | Planned planting date          |
| actual_planting_date  | date (nullable)        | Actual planting date           |
| planned_harvest_date  | date                   | Planned harvest date           |
| actual_harvest_date   | date (nullable)        | Actual harvest date            |
| seed_type             | JSON (multi-lang, array) | Type of seeds (translatable, array) |
| seed_quantity         | decimal(10,2)          | Quantity of seeds              |
| seed_expiry_date      | date (nullable)        | Expiry date of the seeds       |
| area_size             | decimal(10,2)          | Area size covered (e.g., m²)   |
| status                | enum                   | Status: active, in-progress, completed, cancelled |
| created_at            | timestamp              | Record creation date           |
| updated_at            | timestamp              | Record update date             |

---

## 3. Relationships

| Method                 | Type      | Related To            |
|------------------------|-----------|-----------------------|
| crop()                 | belongsTo | Crop                  |
| planner()              | belongsTo | User (creator/planner)|
| land()                 | belongsTo | Land                  |
| productionEstimations()| hasMany   | ProductionEstimation  |
| agriculturalAlerts()   | hasMany   | AgriculturalAlert     |
| cropGrowthStages()     | hasMany   | CropGrowthStage       |

---

## 4. Authorization (CropPlanPolicy)

| Action   | Allowed Roles / Conditions                              |
|----------|--------------------------------------------------------|
| viewAny  | ProgramManager, AgriculturalAlert, Farmer              |
| view     | ProgramManager: all plans<br> AgriculturalAlert: own<br> Farmer: plans for their land |
| create   | Only AgriculturalAlert                                  |
| update   | Only the creator (AgriculturalAlert role)              |
| delete   | Only the creator (AgriculturalAlert role)              |
| before   | SuperAdmin has full access                              |

---

## Notes

- `seed_type` supports multi-language and is stored as an array.  
- Status is managed using ENUM values (active, in-progress, etc.).  
- Deletion of a crop plan may cascade to related growth stages, estimations, and alerts (as per business logic).

---

# Production Estimation

## 1. Description
This module represents the estimated and actual crop production quantities for a specific crop plan. It includes details such as the estimation method and crop quality.

---

## 2. production_estimations Table Structure

| Field             | Type          | Description                      |
|-------------------|---------------|---------------------------------|
| crop_plan_id      | foreign key   | Associated crop plan             |
| expected_quantity | decimal(10,2) | Expected production quantity     |
| actual_quantity   | decimal(10,2) | Actual production quantity (optional) |
| crop_quality      | enum          | Crop quality (optional)          |
| estimation_method | text          | Estimation method (multi-language supported) |
| notes             | text          | Notes (multi-language supported) |
| reported_by       | foreign key   | User who reported the estimation |
| timestamps        | auto          | Created and updated timestamps   |
| deleted_at        | timestamp     | For soft deletion                |

---

## 3. Permissions (Policies)

| Action     | Allowed Roles                                      |
|------------|---------------------------------------------------|
| View All   | Agricultural Engineer, Program Manager            |
| View (own) | Agricultural Engineer (own estimations), Program Manager (all) |
| Create     | Agricultural Engineer only                         |
| Update     | Agricultural Engineer (own estimations only)      |
| Delete     | Agricultural Engineer (own estimations only)      |
| Full Access| SuperAdmin has full unrestricted access           |

---

## 4. Key Service Features

- Create Estimation only when the crop plan status is in-progress.  
- Smart Update logic based on plan status (expected vs actual values).  
- Email Notifications sent to all relevant users.  
- Translation Support for text fields.  
- Caching for improved performance.

---

# Pest/Disease Cases

## 1. Description
This module manages reported pest or disease cases affecting crops during specific growth stages, with support for multilingual details and severity tracking.

---

## 2. pest_disease_cases Table Structure

| Field            | Type        | Description                      |
|------------------|-------------|--------------------------------|
| id               | bigint      | Primary key                    |
| crop_growth_id   | foreign key | Linked crop growth stage       |
| reported_by      | foreign key | User who reported the case     |
| case_type        | enum        | Type of case (pest/disease)    |
| case_name        | JSON        | Case name (multi-language)     |
| severity         | enum        | Case severity: high, medium, or low |
| description      | JSON        | Detailed description (multi-language) |
| discovery_date   | date        | Date of discovery              |
| location_details | JSON        | Location details (multi-language) |
| deleted_at       | timestamp   | Soft delete support (nullable)  |
| timestamps       | auto        | Created and updated timestamps  |

---

## 3. Permissions (Policy)

| Action     | Allowed Roles                                    |
|------------|-------------------------------------------------|
| View All   | Program Manager, Agricultural Alert, Farmer     |
| View (own) | Agricultural Alert (own), Program Manager (all), Farmer (own lands) |
| Create     | Agricultural Alert only                          |
| Update     | Agricultural Alert on their own cases only       |
| Delete     | Agricultural Alert on their own cases only       |
| Full Access| SuperAdmin has unrestricted access               |

---

## 4. Core Features

- Create Case only when the crop plan is in-progress.  
- Update allowed for severity and multilingual fields.  
- Authorization enforced for all actions.  
- Email Notifications sent to relevant users (PM, SuperAdmin, Farmer, Land Owner).  
- Translation support for case name, description, and location.  
- Caching improves performance and avoids redundant DB hits.

---

<!-- # Input Requests Module

## 1. Overview
This module manages agricultural input requests such as seeds, fertilizers, and equipment. It handles request creation, status tracking, approval workflows, notifications, and role-based access control.

---

## 2. Database Schema

### input_requests Table

| Field               | Type               | Description                   |
|---------------------|--------------------|-------------------------------|
| id                  | bigint (PK)        | Primary key                  |
| requested_by        | foreignId          | Request owner (user)          |
| input_type          | enum               | Input type (seeds, fertilizers, equipment) |
| description         | json               | Multi-language description    |
| quantity            | decimal(10,2)      | Quantity requested            |
| status              | enum               | Request status (pending, approved, rejected, etc.) |
| approved_by         | foreignId (nullable) | User who approved the request |
| approval_date       | timestamp (nullable)| Approval timestamp           |
| delivery_date       | timestamp (nullable)| Delivery timestamp           |
| notes               | json (nullable)    | Multi-language notes          |
| selected_supplier_id | foreignId         | Assigned supplier             |
| timestamps          | timestamps         | Created and updated timestamps|

### input_delivery_statuses Table

| Field          | Type             | Description                  |
|----------------|------------------|------------------------------|
| id             | bigint (PK)      | Primary key                 |
| input_request_id | foreignId      | Related input request       |
| action_by      | foreignId        | User who performed the action|
| action_type    | enum             | Action type (pending, approved, etc.) |
| rejection_reason | json (nullable) | Reason for rejection (multi-language) |
| notes          | json (nullable)  | Notes related to the action  |
| action_date    | timestamp        | When the action was performed|
| timestamps     | timestamps       | Created and updated timestamps|

---

## 3. Authorization Policies

| Action   | Allowed Roles & Conditions                            |
|----------|------------------------------------------------------|
| viewAny  | Farmers, Program Managers, Suppliers                 |
| view     | Farmers see own requests; Program Managers see all; Suppliers see assigned requests |
| create   | Only Farmers                                          |
| update   | Farmers update own requests; Suppliers update assigned requests |
| delete   | Only Farmers can delete pending requests             |
| SuperAdmin | Full unrestricted access                            |

---

## 4. Key Service Features

- Fetch requests with filtering and caching.  
- Create new requests with initial status logging and notifications.  
- Update requests with role-based rules (supplier, farmer, admin).  
- Delete requests only if in pending status.  
- Manage request status and delivery tracking.  
- Multi-language support for descriptions, notes, and rejection reasons.  
- Email notifications sent to relevant users on important events.  
- Caching for performance with automatic invalidation on data changes. -->
