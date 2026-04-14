# Simple Purchase Flow Update TODO

## 1. Controller Changes [ ]
- Update store(): payment_status = 'UNPAID'
- Add pay() method
- Update updateReceipt(): editable qty_received, diff stock, status logic

## 2. Routes [ ]
- POST simple-purchases/{id}/pay
- POST simple-purchases/{id}/receipt (change from PATCH)

## 3. Blade show.blade.php [ ]
- Pay button if UNPAID
- Editable qty_received inputs
- Update Receipt button (POST)

## 4. Validation [ ]
- qty_received validation
- Stock validation

## Testing [ ]
- Create → UNPAID/NOT_RECEIVED
- Pay → PAID
- Update Receipt → stock diff + status update

