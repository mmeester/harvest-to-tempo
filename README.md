# Harvest to Tempo

If you use Harvest but have to transfer your time manually to tempo.io, this is your solution, by connecting both services and setting up a cron this should be done automatically if / when you put in your notes correctly

## Getting started

### Clone repository

```shell
git clone git@github.com:mmeester/harvest-to-tempo.git
```

### Run Composer

```shell
composer install
```

### Setup Server variables
Use `env.valet.example` as a reference for needed variables, if you use Valet plus rename this file to `env.valet` and fill out credentials and tokens.

### Run it
```shell
php tunnel.php
```

## Needed Tokens

In order to make this work you need personalised tokens from Harvest, Tempo and Jira

- Harvest: https://help.getharvest.com/api-v2/authentication-api/authentication/authentication/#personal-access-tokens 
- Tempo: Go to your Tempo dashboard in Jira -> Settings -> API Integration
- Jira: https://id.atlassian.com/manage/api-tokens

To find your Harvest account ID go to https://id.getharvest.com/accounts and see what the id is when you inspect the link to the specific account.

To find your Harvest project id, go to your account and the specific project and detect

## To-do
- [ ] Create wizard for setup
- [ ] Move entire code to Laravel
- [ ] Setup multi-tunnel -> multiple harvest accounts to multiple tempo environments