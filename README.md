
<h3>Transaction report app</h3>
<h5>Short how to</h5>

If sail already installed, got to step with sail up

Install commands:
- <p>composer require laravel/sail --dev</p>
- <p>php artisan sail:install</p>
- <p>alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'</p>

- <p>sail up -d</p>
- <p>sail a migrate</p>
- <p>sail a import:types transaction-types.csv</p>
- <p>sail a import:transactions test-data.csv</p>
- <p>sail a import:operations operations.csv</p>

Available routes:
- GET   	api/balance?date=2024-06-09
- GET   	api/balance_changes?start_date=2024-06-10 00:00:00&end_date=2024-07-22 00:00:00
- GET   	api/cash_flows?start_date=2024-06-10 00:00:00&end_date=2024-07-22 00:00:00
- GET   	api/pnl?start_date=2024-06-10 00:00:00&end_date=2024-07-22 00:00:00
- POST	api/transaction_create <br>body: <br>{"timestamp": "2022-06-24 21:57:20", "type": "EXPENDITURE", "amount": "100"}
