# SESSION DEBUGGING GUIDE

## Problem
When user clicks Sepay payment button, `id_lich_chieu = 0` (missing from session).

## Root Cause Analysis

### Session Flow
1. **datve1** - Select movie + cinema + time
   - Calls `load_lc_p($id_phim, $id_lichchieu, $id_gio)` 
   - Returns: `id_lichchieu` ✓
   - Stored: `$_SESSION['tong'] = $list_lc` ✓

2. **datve2** - Select seats
   - Updates: `$_SESSION['tong']['ten_ghe']` and `$_SESSION['tong']['gia_ghe']`
   - Should preserve: `id_lichchieu` ✓

3. **dv3** - Select combo
   - Updates: `$_SESSION['tong']['ten_doan']`
   - Should preserve: `id_lichchieu` ✓

4. **dv4** - Confirm payment
   - Stores final price
   - Should preserve: `id_lichchieu` ✓

5. **Sepay button** - do_payment.php
   - Reads: `$_SESSION['tong']['id_lichchieu']`
   - ❌ Gets: 0 or null

## Possible Causes

### A. Initial datve1 -> datve2 Not Passing Parameters
If user navigates without proper GET params, load_lc_p() may not be called.

**Check URL when on datve2:**
- Should look like: `index.php?act=datve2` (and session should have data from datve1)
- Session should have been set in case "datve1"

### B. Session Regenerated or Lost
- Session destroyed between datve1 and payment
- Check php.ini session settings

### C. GET Parameters Not Provided to load_lc_p()
- datve1 expects: `?id=PHIM_ID&id_lc=LICH_CHIEU_ID&id_g=GIO_ID`
- If these aren't in URL, line 407-408 falls to SESSION or fails

### D. load_lc_p() Not Including id_lichchieu
Actually checked - it DOES include it:
```php
lc.id AS id_lichchieu,
```

## Debugging Steps

### Step 1: Check Session on Payment Page
Add to `view/thanhtoan.php` (payment summary page) near the top:

```php
<?php
error_log("=== THANH TOAN SESSION DEBUG ===");
error_log("Full SESSION['tong']: " . json_encode($_SESSION['tong'] ?? 'NULL'));
if (isset($_SESSION['tong'])) {
    error_log("Keys in tong: " . json_encode(array_keys($_SESSION['tong'])));
    error_log("id_lichchieu: " . ($_SESSION['tong']['id_lichchieu'] ?? 'MISSING'));
}
?>
```

Then refresh payment page and check `php_errors.log`.

### Step 2: Check URL When Navigating datve1 -> datve2
Open browser console (F12) -> Network tab
- When clicking to select seats, check the URL
- Should have: `?act=datve2` (after POST from datve1)
- The GET params (?id=X&id_lc=Y&id_g=Z) might be sent before the POST

### Step 3: Check datve2.php Form Submission
Look at seat selection form in `view/dv2.php`:
- Does it POST to `index.php?act=dv3`?
- If not, the session might be reset

### Step 4: Session Cookie/Timeout
Check if session is being destroyed:
```bash
# In XAMPP php.ini:
session.gc_maxlifetime = 1440  # 24 minutes default
```

If user takes too long on booking page, session expires!

## Solution Options

### Option 1: Add Hidden Field (BEST)
Add hidden field in dv2.php and dv3.php forms to pass id_lichchieu:
```html
<input type="hidden" name="id_lichchieu" value="<?= $_SESSION['tong']['id_lichchieu'] ?>">
```

Then in index.php dv3/dv4 case:
```php
if (isset($_POST['id_lichchieu'])) {
    $_SESSION['tong']['id_lichchieu'] = (int)$_POST['id_lichchieu'];
}
```

### Option 2: Keep GET Parameters
Pass id_lc and id_g through the entire flow:
- datve1 -> datve2: `?act=datve2&id=X&id_lc=Y&id_g=Z`
- datve2 -> dv3: `?act=dv3&id_lc=Y&id_g=Z`
- etc.

This ensures if session is lost, URL parameters can reconstruct it.

### Option 3: Store in Database
Instead of session, store booking in temporary table:
- Create booking when user first selects movie
- Pass booking_id through entire flow
- Retrieve from DB instead of session

## Current Workaround
do_payment.php currently allows `id_lich_chieu = 0`:
```php
if ($id_lich_chieu <= 0) {
    error_log("DO_PAYMENT WARNING: id_lich_chieu is 0");
    // Continue anyway - webhook will handle
}
```

This is TEMPORARY. The vé will be created with `id_lich_chieu = 0` and `id_ngay_chieu = 0`.

## Database Impact
When `id_lich_chieu = 0` and `id_ngay_chieu = 0`:
```sql
INSERT INTO ve (id_phim, id_rap, id_thoi_gian_chieu, id_ngay_chieu, ...)
VALUES (5, 2, 0, 0, ...)
```

This creates invalid vé! The webhook or manual confirmation won't work properly.

## Next Steps
1. Implement Option 1 or 2
2. Test full booking flow from movie selection to payment
3. Verify session contains id_lichchieu at payment stage
4. Remove the `if ($id_lich_chieu <= 0)` bypass
