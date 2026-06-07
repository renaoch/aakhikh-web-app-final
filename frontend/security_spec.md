# Security Specification for AAKHIKH Store

## Data Invariants
1. Products can only be created/updated/deleted by Admins.
2. Orders can be created by any authenticated user.
3. Users can only read their own orders.
4. Admins can read and manage all orders.
5. All price and amount fields must be non-negative.
6. Order status transitions must follow a logical flow (e.g., pending -> paid).

## The Dirty Dozen Payloads (Target: DENIED)

1. **Identity Spoofing**: Attempt to create a product as a non-admin.
2. **Identity Spoofing**: Attempt to read another user's order.
3. **Identity Spoofing**: Attempt to update an order status to 'shipped' as a regular user.
4. **Price Poisoning**: Attempt to create a product with a negative price.
5. **Resource Exhaustion**: Attempt to create an order with a massive text string in `customerName`.
6. **Integrity Breach**: Attempt to update a product name as a regular user.
7. **Type Poisoning**: Attempt to set `stock` as a string instead of a number.
8. **Shadow Field Injection**: Attempt to create an order with an unauthorized `isAdminOverride: true` field.
9. **Timestamp Spoofing**: Attempt to set `createdAt` to a future date instead of `request.time`.
10. **ID Poisoning**: Attempt to use a 1KB string as a product document ID.
11. **Deletion Breach**: Attempt to delete a product as a regular user.
12. **Blanket Read Breach**: Attempt to list all orders without being an admin or filtering by own UID.

## Test Runner Logic (Conceptual)
The `firestore.rules.test.ts` will verify these cases.
