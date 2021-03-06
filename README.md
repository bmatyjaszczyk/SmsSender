SmsSender App example

Allows user to send short text message to recipient. Records texts in database, 
and process texts using RabbitMQ queue.

To run:

    1. Fill all env keys in .env file
    2. Install packages - composer install
    3. Install js packages - yarn install
    4. Compile js - yarn run dev
    5. Run migrations - php bin/console doctrine:migrations:migrate
    6. Load fixtures - php bin/console doctrine:fixtures:load
    7. Run server - symfony server:start
    8. Run worker - php bin/console messenger:consume


Notes:

    -   In local environment Twilio won't update message statuses.
        If you want to test the route works, use Postman with form-data:
        
        MessageSid:<SMS SID YOU WANT TO UPDATE>
        MessageStatus:<STATUS>
        AccountSid:<YOUR TWILIO ACCOUNT SID>
        
        Status can be one of these: 
            accepted
            queued
            sending
            sent
            failed
            delivered
            undelivered

    -   I added predis package to handle redis connection, 
        but in production `ext-redis` should be used instead
