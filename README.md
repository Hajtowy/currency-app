# CurrencyApp

## Run Locally

You need to have installed docker on your machine!

Clone the project

```bash
  git clone git@github.com:Hajtowy/currency-app.git
```

Go to the project directory and docker folder.

```bash
  cd my-project/docker
```

Run docker build.

```bash
  docker compose up -d --build
```

Open app container and run composer.

```bash
  docker compose exec app bash
  composer install
```

Update .env file based on env.example.
`API_NBP_URL=...`

Open web browser and try api:

`http://localhost:8000/api/v1/{currency}/{startDate}/{endDate}`

## TODO: (nice to have)
- some documentation to api like a swagger or something similar.
- extend currency service to have xml or json return format.
