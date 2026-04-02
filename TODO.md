# Implement Purchase Accounting Logic by Payment Method

**Status: Complete ✅**

### Completed:
- [x] 1. Updated purchase/create.blade.php - Added cash dropdown + JS toggle
- [x] 2. Updated PurchasingController::store - Added validation + journal logic (Debit 110501 Inventory, Credit cash/210101 AP)
- [x] 3. Seeded ChartOfAccountSeeder 

### Test Instructions:
1. Login admin@gmail.com / password123
2. Go to `/purchase/create`
3. Test Cash: Select cash → choose cash account → fill items → Save → Check /accounting for PO- journal
4. Test Credit: Select credit → no dropdown → Save → Check journal with AP 210101
5. Verify balanced debit=credit=$total

**Success Message:** "Purchase berhasil dibuat dan journal entry otomatis dibuat!"

**Previous GR Debug: Fixed**

Ready for testing! 🎉
