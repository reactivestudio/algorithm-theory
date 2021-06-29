# Algorithm Theory

## Deploying an application locally with docker-compose

1. Install docker, docker-compose
1. Add to `/etc/hosts` (host machine) follows:
    ```
    127.0.0.1 theory.dev.local
    ```   
1. Clone a repository: `git pull && cd algorithmTheory`
1. Start building containers: `docker-compose up -d`
1. Enter into the application container and install dependencies:
    ```
    docker-compose exec app sh
    composer install
    ```
1. Check `http://theory.dev.local`
