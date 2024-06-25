
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

Available routes:
- GET   	api/balance [fix WIP]
- GET   	api/balance_changes [fix WIP]
- GET   	api/cash_flows
- GET   	api/pnl
- POST	api/transaction_create
