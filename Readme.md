# Demo Broker
### Used Technologies
 - PHP7.1
 - RabbitMQ message broker
 
### User Libraries:
- "nikic/fast-route": "^1.2",
- "videlalvaro/php-amqplib": "^2.7",
- "soapbox/laravel-formatter": "^2.0"

### Prerequisites
https://www.rabbitmq.com/
Installation quide https://tecadmin.net/install-rabbitmq-server-on-ubuntu/#

## Installation
 - composer install
### Routes
POST http://broker.dev/rabbit/sendEmail

POST http://broker.dev/sendEmail

payload
```
{
  "message": "test Rabbit Email"
}
```
### TODO
 - Redis cache