# Our Green Land (أرضنا الخضراء) System Documentation

## Overview

The **Our Green Land** system is a comprehensive agricultural management platform designed to rehabilitate damaged agricultural lands, enhance crop production, and promote food security in affected regions. Developed as the final project for the focal X training program, this system leverages data-driven insights, multilingual support, and role-based access control to empower farmers, agricultural experts, and administrators. It supports land rehabilitation, crop cycle management, pest and disease control, resource allocation, and advanced reporting, fostering resilience and self-sufficiency in local communities.

**Packages Used**:

- [`nwidart/laravel-modules`](https://nwidart.com/laravel-modules): For modular architecture to organize the codebase.
- [`spatie/laravel-activitylog`](https://spatie.be/docs/laravel-activitylog): For tracking user activities and model changes.
- [`spatie/laravel-permission`](https://spatie.be/docs/laravel-permission): For role-based access control and permissions.
- [`spatie/laravel-translatable`](https://spatie.be/docs/laravel-translatable): For multilingual support (Arabic and English).
- Laravel Sanctum: For API authentication (`auth:sanctum` middleware).
- Laravel Model Factories: For test data generation using default naming conventions.

**Resources**:

- **API Documentation**: [Postman Collection](https://dark-crescent-28438.postman.co/workspace/OurGreenLand~ddc2fcee-b98e-4ff5-a8db-3b440b47d53b/collection/24015450-10645028-b70c-4d0a-afe5-aa0dd2260bfb?action=share&source=copy-link&creator=44593086)
- **ERD Diagram**: [GreenLand Diagram](https://dbdiagram.io/d/GreenLand-Diagram-6877c186f413ba350833b896)

---

## Project Objectives

- **Land Rehabilitation**: Restore damaged agricultural lands through soil and water analysis, infrastructure repair, and strategic planning.
- **Crop Management**: Track crop cycles from seeding to harvest, manage pests/diseases, and optimize agricultural practices.
- **Food Security**: Enhance productivity through data-driven insights, resource allocation, and production forecasting.
- **Community Empowerment**: Provide farmers with tools, guidance, and resources to achieve self-sufficiency.
- **Scalability and Security**: Ensure a robust, secure, and extensible system with 99.5% uptime and strong data protection.

---

## Actors and Roles

| Role                             | Description                                   | Key Responsibilities                                                                                 |
| -------------------------------- | --------------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| **Super Administrator**          | System overseer with full access              | Manage users, roles, permissions, and system settings; oversee agricultural operations.              |
| **Program Manager**              | Strategic planner for national rehabilitation | Plan agricultural strategies, prioritize lands, allocate resources, review reports.                  |
| **Agricultural Engineer/Expert** | Technical consultant                          | Analyze soil/water data, create crop plans, provide pest/disease recommendations, oversee practices. |
| **Farmer**                       | Land owner or operator                        | Register lands, manage crops, follow guidance, request inputs, record practices.                     |
| **Soil/Water Specialist**        | Field technician                              | Collect soil/water samples, input lab results, monitor quality indicators.                           |
| **Supplier**                     | Input provider                                | Supply seeds, fertilizers, equipment; update delivery status; provide quotes.                        |
| **Funding Agency**               | Project financier                             | Review progress reports, monitor funding usage, track beneficiary impact.                            |
| **Data Analyst**                 | Insights generator                            | Analyze trends, forecast shortages, evaluate agricultural practices.                                 |

---

## Modules and Features

### 1. Farm Land Management Module

Manages agricultural lands, their geospatial data, soil/water quality, and infrastructure.

#### Key Features

- **Land Registration**: Record land details (ID, owner, farmer, area, soil type, damage level) with GPS coordinates or polygon boundaries.
- **Soil and Water Analysis**: Store lab results for soil (pH, salinity, fertility, nutrients) and water (pH, salinity, quality, suitability), compare against ideal values, and generate recommendations.
- **Infrastructure Tracking**: Monitor assets (irrigation systems, greenhouses, storages) with status (functional/damaged) and maintenance history.
- **Rehabilitation Scheduling**: Track rehabilitation events (e.g., soil amendments, irrigation upgrades) and update land status.

#### Entities

**`lands`**

| Field                      | Type                      | Description                        |
| -------------------------- | ------------------------- | ---------------------------------- |
| `id`                       | `bigint`                  | Primary key                        |
| `owner_id`                 | `foreignId`               | User who owns the land             |
| `farmer_id`                | `foreignId`               | Farmer managing the land           |
| `area`                     | `decimal(10,2)`           | Area in hectares (auto-calculated) |
| `region`                   | `string`                  | Land location                      |
| `soil_type_id`             | `foreignId`               | Soil type reference                |
| `damage_level`             | `enum(low, medium, high)` | Damage severity                    |
| `gps_coordinates`          | `json`                    | Single GPS point `{ lat, lng }`    |
| `boundary_coordinates`     | `json`                    | Polygon boundary points            |
| `rehabilitation_date`      | `date`                    | Latest/planned rehabilitation date |
| `created_at`, `updated_at` | `timestamp`               | Timestamps                         |

**Relationships**: Belongs to `User` (owner, farmer), `SoilType`; Has many `Rehabilitation`, `SoilAnalysis`, `WaterAnalysis`, `AgriculturalInfrastructure`.

**Authorization**:

- **ViewAny**: `AgriculturalEngineer`, `Supplier`, `FundingAgency`.
- **View**: `Farmer` (own lands), `SoilWaterSpecialist`.
- **Create/Update/Delete**: `Farmer` (own lands).
- **ViewPrioritized**: `ProgramManager`.
- **Override**: `SuperAdmin`.

**`soil_analyses`**

| Field                      | Type                      | Description                                 |
| -------------------------- | ------------------------- | ------------------------------------------- |
| `id`                       | `int`                     | Primary key                                 |
| `land_id`                  | `int`                     | Foreign key to `lands`                      |
| `performed_by`             | `int`                     | User who performed analysis                 |
| `sample_date`              | `date`                    | Sample collection date                      |
| `ph_level`                 | `decimal(5,2)`            | Soil pH                                     |
| `salinity_level`           | `decimal(5,2)`            | Soil salinity                               |
| `fertility_level`          | `enum(high, medium, low)` | Soil fertility                              |
| `nutrient_content`         | `json`                    | Translatable nutrient details (nullable)    |
| `contaminants`             | `json`                    | Translatable contaminant details (nullable) |
| `recommendations`          | `json`                    | Translatable recommendations (nullable)     |
| `created_at`, `updated_at` | `timestamp`               | Timestamps                                  |

**Relationships**: Belongs to `Land`, `User` (performer); Morph many `Attachment`.

**Authorization**:

- **ViewAny**: `ProgramManager`, `AgriculturalEngineer`, `DataAnalyst`.
- **View**: `ProgramManager`, `DataAnalyst`, `AgriculturalEngineer` (own), `Farmer` (linked land).
- **Create**: `AgriculturalEngineer`, `SoilWaterSpecialist`.
- **Update/Delete**: `AgriculturalEngineer`/`SoilWaterSpecialist` (own).

**`water_analyses`**

| Field                      | Type                                  | Description                                 |
| -------------------------- | ------------------------------------- | ------------------------------------------- |
| `id`                       | `int`                                 | Primary key                                 |
| `land_id`                  | `int`                                 | Foreign key to `lands`                      |
| `performed_by`             | `int`                                 | User who performed analysis                 |
| `sample_date`              | `date`                                | Sample collection date                      |
| `ph_level`                 | `decimal(5,2)`                        | Water pH                                    |
| `salinity_level`           | `decimal(5,2)`                        | Water salinity                              |
| `water_quality`            | `string`                              | Quality description (nullable)              |
| `suitability`              | `enum(suitable, limited, unsuitable)` | Irrigation suitability                      |
| `contaminants`             | `json`                                | Translatable contaminant details (nullable) |
| `recommendations`          | `json`                                | Translatable recommendations (nullable)     |
| `created_at`, `updated_at` | `timestamp`                           | Timestamps                                  |

**Relationships**: Belongs to `Land`, `User` (performer); Morph many `Attachment`.

**Authorization**: Same as `soil_analyses`.

**`agricultural_infrastructures`**

| Field                      | Type                                          | Description                         |
| -------------------------- | --------------------------------------------- | ----------------------------------- |
| `id`                       | `int`                                         | Primary key                         |
| `type`                     | `enum(irrigationSystem, greenhouse, storage)` | Infrastructure type                 |
| `status`                   | `enum(functional, damaged)`                   | Current status                      |
| `description`              | `json`                                        | Translatable description (nullable) |
| `installation_date`        | `date`                                        | Installation date (nullable)        |
| `created_at`, `updated_at` | `timestamp`                                   | Timestamps                          |

**Pivot Table: `infrastructure_land`**

| Field               | Type  | Description                                   |
| ------------------- | ----- | --------------------------------------------- |
| `id`                | `int` | Primary key                                   |
| `land_id`           | `int` | Foreign key to `lands`                        |
| `infrastructure_id` | `int` | Foreign key to `agricultural_infrastructures` |

**Relationships**: Belongs to many `Land`.

**Authorization**:

- **ViewAny**: `ProgramManager`, `AgriculturalEngineer`.
- **View**: `ProgramManager`, `AgriculturalEngineer`, `Farmer` (linked land).
- **Create**: `AgriculturalEngineer`, `Farmer`.
- **Update/Delete**: `AgriculturalEngineer`, `Farmer` (linked land).
- **Override**: `SuperAdmin`.

**`rehabilitations`**

| Field                      | Type        | Description                   |
| -------------------------- | ----------- | ----------------------------- |
| `id`                       | `bigint`    | Primary key                   |
| `event`                    | `json`      | Translatable event title      |
| `description`              | `json`      | Translatable full description |
| `notes`                    | `json`      | Translatable notes (nullable) |
| `created_at`, `updated_at` | `timestamp` | Timestamps                    |

**Pivot Table: `rehabilitation_land`**

| Field               | Type  | Description                      |
| ------------------- | ----- | -------------------------------- |
| `id`                | `int` | Primary key                      |
| `land_id`           | `int` | Foreign key to `lands`           |
| `rehabilitation_id` | `int` | Foreign key to `rehabilitations` |

**Relationships**: Belongs to many `Land`.

**Authorization**:

- **ViewAny/View**: `ProgramManager`, `AgriculturalEngineer`.
- **Create/Update/Delete**: `ProgramManager`, `AgriculturalEngineer`.
- **Override**: `SuperAdmin`.

---

### 2. Crop & Production Cycle Management Module

Manages crop planning, growth tracking, pest/disease control, and best agricultural practices.

#### Key Features

- **Crop Planning**: Define crops, expected seeding/harvest dates, and seed types.
- **Growth Stage Tracking**: Record crop stages (e.g., seeding, flowering) with dates and notes.
- **Pest and Disease Management**: Log pest/disease cases and provide treatment recommendations.
- **Best Practices**: Document irrigation, fertilization, and pest control practices per growth stage.
- **Production Estimation**: Track expected vs. actual yields and identify production gaps.

#### Entities

**`crops`**

| Field                      | Type        | Description                         |
| -------------------------- | ----------- | ----------------------------------- |
| `id`                       | `int`       | Primary key                         |
| `name`                     | `json`      | Translatable crop name              |
| `description`              | `json`      | Translatable description (nullable) |
| `farmer_id`                | `int`       | Foreign key to `users` (nullable)   |
| `created_at`, `updated_at` | `timestamp` | Timestamps                          |

**Relationships**: Has many `CropPlan`, `IdealAnalysisValue`; Belongs to `User` (farmer).

**Authorization**:

- **ViewAny/View**: `ProgramManager`, `Farmer`, `AgriculturalEngineer`, others.
- **Create/Update/Delete**: `Farmer` (own crops).

**`crop_growth_stages`**

| Field                                    | Type        | Description                   |
| ---------------------------------------- | ----------- | ----------------------------- |
| `id`                                     | `bigint`    | Primary key                   |
| `crop_plan_id`                           | `bigint`    | Foreign key to `crop_plans`   |
| `name`                                   | `json`      | Translatable stage name       |
| `start_date`                             | `date`      | Stage start date              |
| `end_date`                               | `date`      | Stage end date                |
| `notes`                                  | `json`      | Translatable notes (nullable) |
| `recorded_by`                            | `bigint`    | Foreign key to `users`        |
| `created_at`, `updated_at`, `deleted_at` | `timestamp` | Timestamps                    |

**Relationships**: Belongs to `CropPlan`, `User` (recorder); Has many `BestAgriculturalPractice`, `PestDiseaseCase`.

**Authorization**:

- **ViewAny/View**: `Farmer` (linked crop plans), `AgriculturalEngineer`, `ProgramManager`.
- **Create**: `AgriculturalEngineer`, `SuperAdmin`.
- **Update/Delete**: `AgriculturalEngineer` (own), `SuperAdmin`.

**`pest_disease_recommendations`**

| Field                                    | Type           | Description                         |
| ---------------------------------------- | -------------- | ----------------------------------- |
| `id`                                     | `bigint`       | Primary key                         |
| `pest_disease_case_id`                   | `bigint`       | Foreign key to `pest_disease_cases` |
| `recommendation_name`                    | `json`         | Translatable recommendation name    |
| `recommended_dose`                       | `varchar(255)` | Dosage instructions                 |
| `application_method`                     | `json`         | Translatable application method     |
| `safety_notes`                           | `json`         | Translatable safety notes           |
| `recommended_by`                         | `bigint`       | Foreign key to `users`              |
| `created_at`, `updated_at`, `deleted_at` | `timestamp`    | Timestamps                          |

**Relationships**: Belongs to `PestDiseaseCase`, `User` (recommender).

**Authorization**:

- **ViewAny/View**: `Farmer` (linked cases), `AgriculturalEngineer`, `ProgramManager`.
- **Create**: `AgriculturalEngineer`, `SuperAdmin`.
- **Update/Delete**: `AgriculturalEngineer` (own), `SuperAdmin`.

**`best_agricultural_practices`**

| Field                                    | Type                                            | Description                         |
| ---------------------------------------- | ----------------------------------------------- | ----------------------------------- |
| `id`                                     | `bigint`                                        | Primary key                         |
| `growth_stage_id`                        | `bigint`                                        | Foreign key to `crop_growth_stages` |
| `expert_id`                              | `bigint`                                        | Foreign key to `users`              |
| `practice_type`                          | `enum(irrigation, fertilization, pest-control)` | Practice type                       |
| `material`                               | `json`                                          | Translatable material description   |
| `quantity`                               | `decimal(8,2)`                                  | Quantity used                       |
| `application_date`                       | `date`                                          | Application date                    |
| `notes`                                  | `json`                                          | Translatable notes (nullable)       |
| `created_at`, `updated_at`, `deleted_at` | `timestamp`                                     | Timestamps                          |

**Relationships**: Belongs to `CropGrowthStage`, `User` (expert).

**Authorization**:

- **ViewAny/View**: `Farmer` (linked stages), `AgriculturalEngineer`, `ProgramManager`.
- **Create**: `AgriculturalEngineer`, `SuperAdmin`.
- **Update/Delete**: `AgriculturalEngineer` (own), `SuperAdmin`.

#### Entities

| Field                       | Type                    | Description                                  |
|-----------------------------|-------------------------|----------------------------------------------|
| `id`                        | `bigint`                | Primary key                                  |
| `crop_plan_id`              | `bigint`                | Foreign key to `crop_plans` table            |
| `name`                      | `json`                  | Multilingual name (e.g., `{"en": "Flowering", "ar": "التزهير"}`) |
| `start_date`                | `date`                  | Start date of the growth stage               |
| `end_date`                  | `date`                  | End date of the growth stage                 |
| `notes`                     | `json`                  | Multilingual notes (nullable)                 |
| `recorded_by`               | `bigint`                | Foreign key to `users` (user who recorded)   |
| `created_at` / `updated_at` | `timestamp`             | Timestamps                                   |
| `deleted_at`                | `timestamp`             | Soft delete timestamp                        |

**Features**:
- Multilingual support for `name` and `notes` using `spatie/laravel-translatable`.
- Activity logs track changes to `crop_plan_id`, `start_date`, `end_date`, `name`, `notes`, and `recorded_by`.
- Factory support via `CropGrowthStageFactory` for seeding and testing.
- Validation ensures `crop_plan_id` links to an `in-progress` crop plan during creation.

**Relationships**:
- Belongs to `CropPlan` (via `crop_plan_id`).
- Belongs to `User` (recorder, via `recorded_by`).
- Has many `BestAgriculturalPractice` (via `growth_stage_id`).
- Has many `PestDiseaseCase` (via `crop_growth_id`).

### 3. Agricultural Extension & Knowledge Access Module

Facilitates knowledge sharing and communication between farmers and experts.

#### Key Features

- **Knowledge Library**: Digital repository for best practices, crop guides, and educational media.
- **Alerts and Instructions**: Personalized notifications based on crop type, soil conditions, and weather.
- **Expert Communication**: Q&A platform for farmers to ask questions and receive expert answers.

#### Entities

**`agricultural_guidances`**

| Field                      | Type                    | Description                 |
| -------------------------- | ----------------------- | --------------------------- |
| `id`                       | `bigint`                | Primary key                 |
| `title`                    | `varchar(100)`          | Guidance title              |
| `summary`                  | `text`                  | Content/summary             |
| `category`                 | `varchar(50)`           | Thematic category           |
| `language`                 | `enum(arabic, english)` | Multilingual support        |
| `added_by_id`              | `bigint`                | Foreign key to `users`      |
| `tags`                     | `varchar(255)`          | Tags (comma-separated/JSON) |
| `created_at`, `updated_at` | `timestamp`             | Timestamps                  |

**Relationships**: Belongs to `User` (added_by).

**Authorization**:

- **ViewAny/View**: `Farmer`, `AgriculturalEngineer`, `ProgramManager`.
- **Create/Update/Delete**: `AgriculturalEngineer`, `ProgramManager`, `SuperAdmin`.

**`agricultural_alerts`**

| Field                      | Type                                                      | Description                            |
| -------------------------- | --------------------------------------------------------- | -------------------------------------- |
| `id`                       | `bigint`                                                  | Primary key                            |
| `title`                    | `varchar(100)`                                            | Alert title                            |
| `message`                  | `text`                                                    | Main content                           |
| `crop_plan_id`             | `bigint`                                                  | Foreign key to `crop_plans` (nullable) |
| `alert_level`              | `enum(Info, Warning)`                                     | Severity                               |
| `alert_type`               | `enum(Weather, General, Fertilization, Pest, Irrigation)` | Alert category                         |
| `send_time`                | `timestamp`                                               | Scheduled send time                    |
| `created_by`               | `bigint`                                                  | Foreign key to `users`                 |
| `created_at`, `updated_at` | `timestamp`                                               | Timestamps                             |

**Relationships**: Belongs to `CropPlan`, `User` (created_by).

**Authorization**:

- **ViewAny/View**: `Farmer` (linked crop plans), `AgriculturalEngineer`, `ProgramManager`.
- **Create/Update/Delete**: `AgriculturalEngineer`, `ProgramManager`, `SuperAdmin`.

**`questions`**

| Field                      | Type                 | Description            |
| -------------------------- | -------------------- | ---------------------- |
| `id`                       | `bigint`             | Primary key            |
| `farmer_id`                | `bigint`             | Foreign key to `users` |
| `title`                    | `varchar(100)`       | Question subject       |
| `description`              | `text`               | Detailed question      |
| `status`                   | `enum(Open, Closed)` | Status                 |
| `created_at`, `updated_at` | `timestamp`          | Timestamps             |

**`answers`**

| Field                      | Type        | Description                |
| -------------------------- | ----------- | -------------------------- |
| `id`                       | `bigint`    | Primary key                |
| `expert_id`                | `bigint`    | Foreign key to `users`     |
| `question_id`              | `bigint`    | Foreign key to `questions` |
| `answer_text`              | `text`      | Response content           |
| `created_at`, `updated_at` | `timestamp` | Timestamps                 |

**Relationships**: `Questions` belongs to `User` (farmer); `Answers` belongs to `User` (expert), `Question`.

**Authorization**:

- **ViewAny/View**: `Farmer` (own questions), `AgriculturalEngineer`, `ProgramManager`.
- **Create**: `Farmer` (questions), `AgriculturalEngineer` (answers).
- **Update/Delete**: `Farmer` (own questions), `AgriculturalEngineer` (own answers), `SuperAdmin`.

---

### 4. Agricultural Resources & Inputs Module

Manages suppliers and farmer input requests (seeds, fertilizers, equipment).

#### Key Features

- **Supplier Management**: Track supplier details, types, and performance ratings.
- **Input Requests**: Handle farmer requests with approval workflows, status tracking, and delivery updates.
- **Notifications**: Alert stakeholders on request status changes.

#### Entities

**`suppliers`**

| Field                      | Type         | Description                |
| -------------------------- | ------------ | -------------------------- |
| `id`                       | `bigint`     | Primary key                |
| `user_id`                  | `foreignId`  | Linked user profile        |
| `supplier_type`            | `json`       | Translatable supplier type |
| `phone_number`             | `string(50)` | Contact number             |
| `license_number`           | `string(50)` | License number             |
| `created_at`, `updated_at` | `timestamp`  | Timestamps                 |

**`supplier_ratings`**

| Field                      | Type          | Description                |
| -------------------------- | ------------- | -------------------------- |
| `id`                       | `bigint`      | Primary key                |
| `supplier_id`              | `foreignId`   | Foreign key to `suppliers` |
| `reviewer_id`              | `foreignId`   | User who rated             |
| `rating`                   | `tinyInteger` | Rating (1-5)               |
| `comment`                  | `text`        | Feedback (nullable)        |
| `created_at`, `updated_at` | `timestamp`   | Timestamps                 |

**Relationships**: `Suppliers` belongs to `User`; `SupplierRatings` belongs to `Supplier`, `User` (reviewer).

**Authorization**:

- **ViewAny/View/Create/Update/Delete**: `ProgramManager`, `SuperAdmin`.

**`input_requests`**

| Field                      | Type                                  | Description                   |
| -------------------------- | ------------------------------------- | ----------------------------- |
| `id`                       | `bigint`                              | Primary key                   |
| `requested_by`             | `foreignId`                           | User who requested            |
| `input_type`               | `enum(seeds, fertilizers, equipment)` | Input type                    |
| `description`              | `json`                                | Translatable description      |
| `quantity`                 | `decimal(10,2)`                       | Quantity requested            |
| `status`                   | `enum(pending, approved, rejected)`   | Request status                |
| `approved_by`              | `foreignId`                           | User who approved (nullable)  |
| `approval_date`            | `timestamp`                           | Approval timestamp (nullable) |
| `delivery_date`            | `timestamp`                           | Delivery timestamp (nullable) |
| `notes`                    | `json`                                | Translatable notes (nullable) |
| `selected_supplier_id`     | `foreignId`                           | Assigned supplier             |
| `created_at`, `updated_at` | `timestamp`                           | Timestamps                    |

**`input_delivery_statuses`**

| Field                      | Type                                | Description                              |
| -------------------------- | ----------------------------------- | ---------------------------------------- |
| `id`                       | `bigint`                            | Primary key                              |
| `input_request_id`         | `foreignId`                         | Foreign key to `input_requests`          |
| `action_by`                | `foreignId`                         | User who performed action                |
| `action_type`              | `enum(pending, approved, rejected)` | Action type                              |
| `rejection_reason`         | `json`                              | Translatable rejection reason (nullable) |
| `notes`                    | `json`                              | Translatable notes (nullable)            |
| `action_date`              | `timestamp`                         | Action timestamp                         |
| `created_at`, `updated_at` | `timestamp`                         | Timestamps                               |

**Relationships**: `InputRequests` belongs to `User` (requested_by, approved_by), `Supplier`; `InputDeliveryStatuses` belongs to `InputRequest`, `User` (action_by).

**Authorization**:

- **ViewAny**: `Farmer`, `ProgramManager`, `Supplier`.
- **View**: `Farmer` (own), `ProgramManager` (all), `Supplier` (assigned).
- **Create**: `Farmer`.
- **Update**: `Farmer` (own), `Supplier` (assigned).
- **Delete**: `Farmer` (pending).
- **Override**: `SuperAdmin`.

---

### 5. Reporting & Analytics Module

Provides dashboards and reports for monitoring and decision-making.

#### Key Features

- **Role-Based Dashboards**: Customized views for farmers, engineers, and administrators.
- **Production Reports**: Analyze yields by crop, soil type, region, and trends.
- **Land Status Maps**: Interactive maps showing rehabilitated, damaged, or in-progress lands.
- **Funding Reports**: Transparent reports for funding agencies on project impact.

#### Available Reports

**Farmer Reports**

| Report                 | Description                  | Route                                       |
| ---------------------- | ---------------------------- | ------------------------------------------- |
| Farmer Lands Summary   | Overview of owned lands      | `GET /api/reporting/farmer-lands-summary`   |
| Latest Crop Plans      | Recent crop plans            | `GET /api/reporting/latest-crop-plans`      |
| Recent Guidance        | Latest agricultural guidance | `GET /api/reporting/recent-guidance`        |
| Crop Plan Status Stats | Breakdown of crop statuses   | `GET /api/reporting/crop-plan-status-stats` |

**Engineer Reports**

| Report               | Description            | Route                                   |
| -------------------- | ---------------------- | --------------------------------------- |
| Soil Analyses        | Soil lab results       | `GET /api/reporting/soil-analyses`      |
| Water Analyses       | Water quality reports  | `GET /api/reporting/water-analyses`     |
| Pest & Disease Cases | Pest/disease incidents | `GET /api/reporting/pest-disease-cases` |

**Super Admin Reports**

| Report                      | Description                         | Route                                                          |
| --------------------------- | ----------------------------------- | -------------------------------------------------------------- |
| Production Overview         | Agricultural production summary     | `GET /api/reporting/production-overview`                       |
| Rehabilitated Areas Summary | Rehabilitated land statistics       | `GET /api/reporting/rehabilitated-areas-summary`               |
| Production Gaps Report      | Expected vs. actual production gaps | `GET /api/reporting/finished-production-estimations-with-gaps` |

**Production Estimation Reports**

| Report        | Description            | Route                                      |
| ------------- | ---------------------- | ------------------------------------------ |
| By Crop       | Yields by crop type    | `GET /api/reporting/prod-est-by-crop`      |
| By Soil Type  | Yields by soil type    | `GET /api/reporting/prod-est-by-soil-type` |
| By Region     | Regional productivity  | `GET /api/reporting/prod-est-by-region`    |
| Yearly Trends | Yield trends over time | `GET /api/reporting/prod-trends-by-year`   |

**Routes**: Protected by `auth:sanctum` middleware under `/api/reporting/`.

---

## Technical Specifications

- **Performance**: Response times <2 seconds; handles large geospatial and agricultural datasets.
- **Security**:
  - Strong authentication via Laravel Sanctum.
  - Role-based access control with `spatie/laravel-permission`.
  - Audit logging for all critical actions (`spatie/laravel-activitylog`).
  - Protection against common vulnerabilities (e.g., SQL injection, XSS).
- **Scalability**: Modular architecture with `nwidart/laravel-modules` supports additional features (e.g., livestock management, market integration).
- **Availability**: 99.5% uptime target.
- **Multilingual Support**: Arabic and English via `spatie/laravel-translatable` for translatable fields (e.g., `name`, `notes`, `description`).
- **APIs**: RESTful APIs documented in [Postman Collection](https://dark-crescent-28438.postman.co/workspace/OurGreenLand~ddc2fcee-b98e-4ff5-a8db-3b440b47d53b/collection/24015450-10645028-b70c-4d0a-afe5-aa0dd2260bfb?action=share&source=copy-link&creator=44593086).
- **Database**:
  - MySQL/PostgreSQL compatible (see [ERD Diagram](https://dbdiagram.io/d/GreenLand-Diagram-6877c186f413ba350833b896)).
  - JSON fields for translatable data.
  - Soft deletes for data recovery.
- **Geospatial Features**: GeoHelper for area calculations; supports GPS points and polygon boundaries.

---

## Future Enhancements

- **Media Integration**: Support image/video uploads for crop monitoring and pest/disease diagnosis.
- **External APIs**: Integrate weather, satellite, and drone data for automated insights.
- **Mobile App**: Develop farmer-focused mobile app for field updates.
- **Analytics Expansion**: Add predictive models for crop shortages and pest outbreaks.
- **Market Linkages**: Connect farmers to buyers for produce sales.

---

## Installation and Setup

1. Clone the repository: `git clone https://github.com/Salman-S6/OurGreenLandSystem.git`.
2. Install dependencies: `composer install`.
3. Configure environment: Copy `.env.example` to `.env` and set database/API keys.
4. Run migrations: `php artisan migrate`.
5. Seed database: `php artisan db:seed`.
6. Start server: `php artisan serve`.
7. Access API: Base URL `/api` with Sanctum authentication.

---

## Documentation

- **API Docs**: [Postman Collection](https://dark-crescent-28438.postman.co/workspace/OurGreenLand~ddc2fcee-b98e-4ff5-a8db-3b440b47d53b/collection/24015450-10645028-b70c-4d0a-afe5-aa0dd2260bfb?action=share&source=copy-link&creator=44593086).
- **Database Schema**: [ERD Diagram](https://dbdiagram.io/d/GreenLand-Diagram-6877c186f413ba350833b896).
- **Security Policies**: Defined in Laravel Policy classes per entity using `spatie/laravel-permission`.

---

## Acknowledgments

This project was developed as the final project for the focal X training program by:

- Maryam Asha
- Abeer Sham Sary
- Eyad Swadi
- Ahmad Shehade
- Salman Sumak
