As part of the complete Basket structural revamp, coupons will finally be integrated. This provides basic notes.

The system currently contains a table for this purpose, but is only used for account email confirmation. This is done by the action field being executed as an SQL query. I plan to drop this column alongside expiryAction, and create two new tables - keys\_action and keys\_expiry, which will contain the necessary data.

keys\_action
| keyID | Action |
|:------|:-------|
| 1     | Activate\_Acc[1](1.md) |
| 2     | Basket\_Total[-10%] |
| 3     | Basket\_Delivery[0](0.md) |
| 4     | Item10\_Price\_GBP[0.99] |