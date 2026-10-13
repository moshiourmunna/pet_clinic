# PetCare Clinic & Hospital — Laravel 12

Bilingual (English / বাংলা) veterinary clinic website with online appointment booking, optional online payment, customer dashboard and admin panel. It is built from the approved HTML mockups and keeps the same design (`public/assets/style.css`).

**No Node/npm is needed.** The CSS and JS are plain files in `public/assets`.

## Requirements
- PHP **8.3** with these extensions: `pdo_mysql` (or `pdo_sqlite`), `mbstring`, `openssl`, `xml`, `curl`, `fileinfo`, `zip`, `intl`, `bcmath`
- Composer 2.x
- MySQL 8 / MariaDB 10.6+ (XAMPP or Laragon), or SQLite for a quick test
- Built with Laravel **12.x** (tested on 12.70) and PHP 8.3.35

## Install on Windows

### Laragon (recommended)
1. In Laragon, go to Menu → PHP → Version and choose **8.3.x**.
2. Copy the project into `C:\laragon\www\pet_clinic`. If you prefer to keep it at `E:\projects\pet_clinic`, add that folder in Laragon instead.
3. Open the Laragon Terminal in that folder and run:
   ```bat
   composer install
   copy .env.example .env
   php artisan key:generate
   ```
4. Create the database `pet_clinic` in HeidiSQL (Laragon → Database). Then check `DB_*` in `.env`. The defaults are user `root` with no password.
5. Run the setup:
   ```bat
   php artisan migrate --seed
   php artisan storage:link
   ```
6. Open http://pet_clinic.test, or run `php artisan serve` and open http://127.0.0.1:8000.

### XAMPP
1. Install a XAMPP build that ships **PHP 8.3** and install Composer for Windows, pointing it to `C:\xampp\php\php.exe`.
2. In `C:\xampp\php\php.ini`, enable these extensions: `extension=intl`, `zip`, `fileinfo`, `pdo_mysql`, `mbstring`, `openssl`, `curl`.
3. Start Apache and MySQL. In phpMyAdmin, create the database `pet_clinic` with collation `utf8mb4_unicode_ci`.
4. In the project folder, run the same commands as in Laragon steps 3 and 5, then run `php artisan serve`.
   - To serve through Apache instead, point a virtual host at the `public/` folder.

### Quick test with SQLite (no MySQL)
In `.env`, set `DB_CONNECTION=sqlite` and remove the other `DB_*` lines. Then run:
`type nul > database\database.sqlite` and `php artisan migrate --seed`.

## Logins (seeded)
| Role | Login | Password |
|---|---|---|
| Admin | `admin@petcareclinic.com` (or phone `01700000000`) | `password` |
| Customer (demo) | `rafiq@example.com` (or phone `01711111111`) | `password` |

The admin panel is at **/admin**. **Change both passwords before going live.**

## .env keys
| Key | Purpose |
|---|---|
| `APP_URL` | Your site URL. Payment callbacks use it. |
| `APP_TIMEZONE` | Default is `Asia/Dhaka`. |
| `MAIL_*` | The default `log` mailer writes booking emails to `storage/logs/laravel.log`. Set SMTP for real email. |
| `SMS_DRIVER` | `log` (default) writes SMS and OTP texts to the log. `http` sends through a real gateway (`SMS_API_URL`, `SMS_API_KEY`, `SMS_SENDER_ID`). The parameter names are in `config/sms.php`. |
| `PAYMENT_SIMULATE` | When `true` and no gateway keys are set, a demo "gateway" page opens so you can test paid and failed flows. **Set it to `false` in production.** |
| `SSLCZ_STORE_ID`, `SSLCZ_STORE_PASSWORD`, `SSLCZ_SANDBOX` | SSLCommerz card and online payment. Get sandbox keys at developer.sslcommerz.com. |
| `BKASH_APP_KEY`, `BKASH_APP_SECRET`, `BKASH_USERNAME`, `BKASH_PASSWORD`, `BKASH_SANDBOX` | bKash Tokenized Checkout, which is also used for the "bKash / Nagad" option. |

Gateway callback URLs. These are excluded from CSRF so the gateways can call them.
- SSLCommerz: `/payment/sslcommerz/success|fail|cancel` and IPN `/payment/sslcommerz/ipn`
- bKash: `/payment/bkash/callback`

To add another SMS provider or payment gateway, implement `App\Services\Sms\SmsDriver` or `App\Services\Payments\PaymentGateway` and register it in `SmsManager` or `PaymentManager`.

## Features
- **Bilingual.** The EN/বাং switch calls `/lang/{en|bn}`, the choice is stored in the session, and the `SetLocale` middleware applies it.
  - UI text is translated through `lang/bn.json`.
  - Database content uses `*_en` and `*_bn` columns through the `HasTranslations` trait, so `$service->name` gives the current language.
  - **If only one language is filled in, that text is shown in both languages.**
- **Booking**, at `/appointment`, works in two modes:
  1. *Choose Doctor & Time.* Slots load live from `GET /api/doctors/{id}/slots?date=YYYY-MM-DD`. They are built from the doctor's weekly schedule minus slots already booked and times already past. Taken slots are disabled, and the summary sidebar updates without reloading.
  2. *Send a Request.* No doctor or time is needed; staff confirm it in the admin panel.
  - Guests can book; a phone number is required.
  - Bookings are checked by `StoreAppointmentRequest`.
  - Double booking is prevented by a transaction with a row lock plus a unique `slot_key` index.
  - Payment is optional: pay at clinic (default), bKash/Nagad, or card through SSLCommerz.
  - Each booking sends an email (customer and clinic) and an SMS.
- **Accounts.**
  - Register, and log in with phone **or** email.
  - OTP login (`OtpService`, 6-digit code, 5-minute expiry, attempt limit).
- **Customer dashboard:**
  - overview stats
  - appointments, with cancel and reschedule (live slots)
  - pets (add, edit, delete)
  - medical records, vaccinations, payments, and profile settings
- **Admin panel** (`/admin`, admin role only):
  - stats
  - appointments: filter, confirm, complete, cancel, assign a doctor, mark as paid
  - add, edit and delete services, doctors (with weekly schedule editor), plans, blog posts, FAQ, gallery and testimonials
  - contact messages
  - clinic settings: phone, email, address, hours, social links and map
- **Live JavaScript:** doctor search and filter without reloading, contact and newsletter forms over AJAX with field errors, and an FAQ accordion.
- **Good practice:**
  - named routes and one controller per area
  - policies, CSRF protection and rate limits (booking, contact, OTP, login)
  - SEO meta and Open Graph tags on every page, plus schema.org data
  - `/sitemap.xml`, a custom 404 page and pagination

## Tests
```bash
php artisan test
```
There are 25 feature tests covering slot booking, request booking, double-booking rejection, cancel and reschedule, payment flow, locale switch and fallback, auth (password and OTP), dashboard, admin access and AJAX forms.

## Structure
- `app/Http/Controllers`: public pages, booking and payments, plus `Auth/`, `Customer/` and `Admin/`
- `app/Services`: SlotService, BookingService, OtpService, `Sms/`, `Payments/`
- `app/Support/AdminResources.php`: field definitions for the admin content screens
- `resources/views`: `layouts/`, `partials/`, `components/`, `pages/`, `appointment/`, `dashboard/`, `admin/`
- `public/assets`: style.css (from the mockup), extra.css, app.js, booking.js, images and logo
