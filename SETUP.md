# Chimichange - Setup using Docker

- Clone this repository

- Recompile the Docker images

`> docker-compose build`

- Start containers

`> docker-compose up -d`

- Verify containers status after 30s

`> docker-compose ps`

- Create the JWT (JSON Web Tokens) certificate files

```
> docker-compose exec php sh -c '
    set -e
    apk add openssl
    mkdir -p config/jwt
    jwt_passphrase=${JWT_PASSPHRASE:-$(grep ''^JWT_PASSPHRASE='' .env | cut -f 2 -d ''='')}
    echo "$jwt_passphrase" | openssl genpkey -out config/jwt/private.pem -pass stdin -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    echo "$jwt_passphrase" | openssl pkey -in config/jwt/private.pem -passin stdin -out config/jwt/public.pem -pubout
    setfacl -R -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    setfacl -dR -m u:www-data:rX -m u:"$(whoami)":rwX config/jwt
    chown www-data:www-data config/jwt/*.pem
'
```

- Create database schema (run migrations)

`> docker-compose exec php bin/console make:migration`

`> docker-compose exec php bin/console doctrine:migrations:migrate`

- Load demo data

`> docker-compose exec php bin/console hautelook:fixtures:load`

- Load rates from fixer.io (process would be run background each x time)

`> docker-compose exec php bin/console rate:load`

- Documentation API REST

`> https//:localhost/docs`

- Run Tests

`> docker-compose exec php bin/phpunit`

