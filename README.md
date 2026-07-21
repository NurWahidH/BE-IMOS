# Rancangan API: antammedika IMOS


/*
|--------------------------------------------------------------------------
| API Endpoints: antammedika IMOS
|--------------------------------------------------------------------------
|
| Berikut adalah rancangan (blueprint) untuk seluruh endpoint API
| yang akan digunakan di dalam sistem antammedika IMOS.
|
*/

/*
|--------------------------------------------------------------------------
| 1. Auth Management
|--------------------------------------------------------------------------
| Berlaku untuk semua aktor (Master Admin, Admin, User, Public User).
*/
// POST   /login                  -> Login account (menggunakan username & password)
// POST   /logout                 -> Logout account
// GET    /profile                -> Check profile


/*
|--------------------------------------------------------------------------
| 2. User & Role Management
|--------------------------------------------------------------------------
| Akses utama: Master Admin.
*/
// GET    /users                  -> Get all users data
// POST   /users                  -> Add new user & assign role (Kelola user & role)
// GET    /users/{id}             -> Get user by ID
// PUT    /users/{id}             -> Update user & role
// DELETE /users/{id}             -> Delete user
// GET    /users/logs             -> Get user activity logs (Lihat user log)


/*
|--------------------------------------------------------------------------
| 3. Asset Management
|--------------------------------------------------------------------------
| Akses utama: Admin & fitur scan QR untuk Public.
*/
// GET    /assets                 -> Get all master assets
// GET    /assets/{id}            -> Get asset detail
// POST   /assets                 -> Add new asset manually (Kelola master aset manual)
// POST   /assets/upload          -> Add assets via CSV/Excel (Kelola master aset bulk)
// PUT    /assets/{id}            -> Update asset data
// DELETE /assets/{id}            -> Delete asset data

// --- Asset Categories ---
// GET    /assets/categories      -> Get all asset categories
// POST   /assets/categories      -> Add new category
// PUT    /assets/categories/{id} -> Update category
// DELETE /assets/categories/{id} -> Delete category

// --- QR Code Scanning ---
// GET    /assets/scan/{qr_code}  -> Get asset information by scanning QR code


/*
|--------------------------------------------------------------------------
| 4. Unit Management
|--------------------------------------------------------------------------
| Akses utama: Admin & User.
*/
// GET    /units                  -> Get list of units (Lihat daftar unit)
// GET    /units/{id}             -> Get unit by ID
// POST   /units                  -> Add new unit (Kelola unit)
// PUT    /units/{id}             -> Update unit data
// DELETE /units/{id}             -> Delete unit


/*
|--------------------------------------------------------------------------
| 5. Mutation (Transfer) Management
|--------------------------------------------------------------------------
| Akses mencakup pengajuan oleh User/Public dan Approval oleh Admin.
*/
// GET    /mutations              -> Get all mutation requests (Approval list)
// GET    /mutations/history      -> Get mutation history (Lihat histori mutasi)
// GET    /mutations/{id}         -> Get mutation details
// POST   /mutations              -> Submit new mutation request (Request / Pengajuan mutasi)
// PATCH  /mutations/{id}/approve -> Approve mutation request (Approve mutasi)
// PATCH  /mutations/{id}/reject  -> Reject mutation request (Reject mutasi)


/*
|--------------------------------------------------------------------------
| 6. Complaint & Ticketing Management
|--------------------------------------------------------------------------
| Akses mencakup pengajuan oleh Public/User dan Konfirmasi oleh Admin.
*/
// GET    /complaints             -> Get list of complaint tickets (Lihat list tiket komplain)
// GET    /complaints/{id}        -> Get complaint details
// POST   /complaints             -> Submit new complaint/report (Pengajuan laporan)
// PATCH  /complaints/{id}/confirm-> Confirm/resolve complaint status (Confirm status)

```
