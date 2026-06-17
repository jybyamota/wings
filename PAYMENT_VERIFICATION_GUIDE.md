# Wing Master - Payment Proof Upload Guide

## Complete Reservation & Payment Verification Workflow

### **Step 1: Make a Reservation**
- Visit [http://localhost:8000/reservation.php](http://localhost:8000/reservation.php)
- Login or create an account
- Click "Reserve a Table"
- Select party size, date, and time
- Submit your reservation
- **You'll receive a unique Reference Number** (e.g., `WM-20260617-001`)

### **Step 2: Send GCash Payment**
Send **₱500** via GCash to: **0909 163 9984**
- Include your reference number in the GCash memo/message field
- Example: "WM-20260617-001"

### **Step 3: Upload Payment Proof** ← THIS IS THE KEY STEP
Go to: [http://localhost:8000/upload-payment.php](http://localhost:8000/upload-payment.php)

**What to do:**
1. Enter your reference number (e.g., WM-20260617-001)
2. Upload a screenshot of your GCash payment confirmation that shows:
   - Transaction amount (₱500)
   - Timestamp of the transaction
   - Recipient phone number (0909 163 9984)
3. Click "Upload Proof of Payment"

### **Step 4: Admin Verification**
- Admin panel at: [http://localhost:8000/admin.php](http://localhost:8000/admin.php)
- Admin reviews the uploaded payment screenshot
- Admin clicks "Verify" to confirm the payment
- Your reservation status updates to "Confirmed"
- You'll see "Payment Verified" in your reservation history

---

## Features Summary

### **Customer-Facing Pages:**

| Page | URL | Purpose |
|------|-----|---------|
| Reservation | `/reservation.php` | Book a table, get reference number |
| Upload Payment | `/upload-payment.php` | Submit GCash payment proof with reference number |

### **Admin Panel:**
| Page | URL | Purpose |
|------|-----|---------|
| Admin Dashboard | `/admin.php` | Review all reservations and payment proofs |
| | | Verify or reject payments |
| | | View customer details and payment screenshots |

---

## Payment Reference Number Format
- **Format:** `WM-YYYYMMDD-XXX`
- **Example:** `WM-20260617-001`
  - `WM` = Wing Master
  - `20260617` = Date (June 17, 2026)
  - `001` = Sequential number for that day

---

## How Admin Verification Works

1. **Login to Admin Panel**
   - Password: `Wing@Master2024`

2. **View All Reservations**
   - Displays customer name, email, reservation details
   - Shows payment status (Pending/Verified/Rejected)
   - Shows if payment screenshot was uploaded

3. **Review Payment Proof**
   - Click "View Image" to see the customer's GCash screenshot
   - Verify the amount (₱500), recipient (0909 163 9984), and reference number

4. **Approve or Reject**
   - Click "Verify" → Status changes to "Confirmed" + "Payment Verified"
   - Click "Reject" → Status changes to "Cancelled" + "Payment Rejected"

---

## File Structure

```
/data/
├── reservations.json     (stores all reservation data)
├── users.json           (stores customer accounts)
└── uploads/            (stores payment screenshots)
    ├── WM-20260617-001.png
    ├── WM-20260617-002.jpg
    └── ... (one per reservation)
```

---

## Key Information Stored Per Reservation

```json
{
  "reference_number": "WM-20260617-001",
  "email": "customer@example.com",
  "name": "Juan Dela Cruz",
  "party": 2,
  "date": "2026-06-25",
  "time": "6:00 PM",
  "notes": "Birthday celebration",
  "status": "Confirmed",
  "payment_screenshot": "WM-20260617-001.png",
  "payment_status": "Verified",
  "created_at": "2026-06-17 10:17:36",
  "screenshot_uploaded_at": "2026-06-17 10:18:05",
  "verified_at": "2026-06-17 10:17:52"
}
```

---

## Testing the System

### Test Scenario 1: Complete Reservation with Payment Upload

1. Go to `/reservation.php`
2. Register: `test@example.com` / `password123`
3. Click "Reserve a Table"
4. Select 2 guests, date 2026-06-25, time 6:00 PM
5. Click "Confirm Reservation"
6. Copy your reference number (e.g., WM-20260617-001)
7. Go to `/upload-payment.php`
8. Enter reference number
9. Upload a payment screenshot
10. Go to `/admin.php`
11. Login: password `Wing@Master2024`
12. Find your reservation in the table
13. Click "Verify" to approve
14. Reservation status updates to "Confirmed"

### Test Scenario 2: View in Customer History

1. After verifying payment in admin panel
2. Go back to `/reservation.php` (login if needed)
3. Scroll to "My Reservations" section
4. See your reservation with:
   - Reference number
   - "Confirmed" status badge
   - "Payment Verified" badge in green

---

## Admin Password

**Default Password:** `Wing@Master2024`

⚠️ **Security Note:** Change this password in production! Edit line 9 in `admin.php`:
```php
define('ADMIN_PASSWORD', 'Wing@Master2024');  // ← Change this
```

---

## Questions & Support

**Contact Information:**
- Phone: 0909 163 9984
- Email: wingmastersilogsamal@gmail.com

