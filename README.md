## Problem Description

### Database Schema Design

Design a database schema for products with the following fields:

- **name**: Name of the product.
- **product ID**: Unique identifier for the product.
- **available stocks**: Quantity of the product available in stock.
- **price of one unit (float)**: Price of one unit of the product.
- **tax percentage (float)**: Tax percentage applicable to the product.

You can seed the values into the database or implement a CRUD page for adding these details.

### Billing Calculation Page

Design a billing calculation page with the following features:

1. **Customer Information**:
   - Provision to enter the customer's email who purchased the items.

2. **Product Selection**:
   - Add all products that the client bought to the bill by clicking on the "Add New" button.
   - Dynamically add fields to enter a product ID and the quantity of the product purchased.

3. **Denominations**:
   - Create a separator to separate the product section.
   - Default denominations are provided to collect the count of denominations in each available value.

4. **Payment Information**:
   - Collect the amount that the customer gave for the bill.

5. **Generate Bill**:
   - Clicking on "Generate Bill" should calculate the values and display the information.
   - Send the invoice to the customer’s email using a Queue.

6. **Balance Denomination Calculation**:
   - Calculate the balance denomination that needs to be given to the customer based on the denominations available in the shop.

### View Previous Purchases

- View previous purchases made by the customer.
- List all the purchases, and selecting one should show what items were purchased in that purchase.

### Rules

1. If there is any doubt, please reach out or implement based on assumptions and mention those assumptions.
2. Code should be implemented using best practices and should be production-ready.
3. More importance is given to Laravel and Database related concepts rather than views.
4. There is no predefined solution from outside, so feel free to use any approach for designing the database schema and Laravel logic.


## Implementation Overview

### Tables

- **customer_mallow**: Stores customer information.
- **products**: Stores product information.
- **denominations**: Stores denomination information.
- **customer_purchase_info**: Maps customer IDs and stores customer bill details.
- **product_logs**: Stores logs of products bought by customers.
- **denomination_logs**: Stores logs of denomination counts and balances.

### Models

- **Customer**: Stores customer information.
- **Products**: Stores product information.
- **Denomination**: Stores denomination information.
- **CustomerPurchaseInfo**: Maps customer IDs and stores bill details.
- **ProductLog**: Stores logs of products bought.
- **DenominationLog**: Stores logs of denomination counts and balances.

### Services

- **BillingService**: Handles calculations such as initial day ledger balance and change to be provided to the customer.
- **CacheServiceProvider**: Caches product and denomination lists to reduce database load during system boot.

### Features

- **Cache**: Utilized to reduce database load by caching product and denomination lists.
- **DomPdf**: Package used for generating and downloading invoices as PDFs.

### Packages

- **DomPdf**: Package used for generating PDF invoices.
- **Barryvdh-DomPdf**: Package used for generating PDF invoices and attaching it to email.

### Views

- **costing.blade**: Initial webpage for inputting customer email and selecting products for purchase.
- **billing.blade**: Displays the final bill calculation.

### Additional Features

- **jQuery**: Utilized for dynamic loading of responses into specific sections without full page reload.
