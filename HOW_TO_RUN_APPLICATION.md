# RemindMe - How to run application

1. run ./vendor/bin/sail up
2. run ./vendor/bin/sail bash
3. run php artisan migrate
4. run php artisan db:seed

## Run Scheduler and notification

to run scheduler please run to check email notification

- php artisan schedule:run
- php artisan queue:listen

## Run Test

1. run ./vendor/bin/sail up
2. run ./vendor/bin/sail bash
3. php artisan run test
