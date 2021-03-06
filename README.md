# ARCVStore

## This Repo has been archived.
We merged the functionality in this repo into [neontribe/ARCVService](https://github.com/neontribe/ARCVService). This repo is archived and should not be commited against. 

## About ARC Voucher Store
ARCV Store is the a service that permits Children's Centres to perform registration and management of families.

[![Build Status](https://travis-ci.org/neontribe/ARCVStore.svg?branch=0.1/release)](https://travis-ci.org/neontribe/ARCVStore.svg?branch=master)

## Installation of Development instance

1. Clone the repo
2. Create a database and user (homestead, sqlite or mysql)
3. If not using [Homestead](https://https://laravel.com/docs/5.4/homestead) or Valet - you will need to configure permissions on `storage` and `bootstrap/cache`. See [Laravel 5.4 Installation](https://laravel.com/docs/5.4#installation) for more info.
4. Copy `.env.example` to `.env` and edit to local settings. We would recommend a `.test` sub-domain to avoid browsers making assumptions or uplifting to HSTS like, for example, Chrome now does. 
5. `composer install`
6. `php artisan key:generate`
7. `php artisan migrate --seed`

## Deployment

1. Travis will build and test with every push to the repo.
2. Travis will deploy to staging `https://voucher-store-staging.alexandrarose.org.uk` with every merge to default branch. When default branch is updated, change value in `.travis.yml`.

## CI deploy with Travis set up notes

1. Install travis cli tool wih `gem install travis`
2. Log in to travis cli with `travis login` using git token or creds
3. Create a `.env.travis` that is in `local` env with user `travis` and no password for database.
4. Create `.travis.yml` as per one in this repo without the `env:global:secure:` vars and without the openssl encrypted info. If you are setting up a new config - we need to encrypt and add those values.
5. Use travis cli to encrypt vars and add them to .yml e.g. `travis encrypt DEPLOY_USER=mickeymouse --add` for `$DEPLOY_USER`, `$DEPLOY_IP`, `$DEPLOY_DIR`.
6. Create an ssh key and `ssh-copy-id -i store_deploy_key.pub` to server. Encrypt the private half and add to the .yml with `travis encrypt-file store_deploy_key --add`
7. delete the `store_deploy_key` and `store_deploy_key.pub` from your machine - don't need them anymore.

# Copyright
This project was developed by :

Neontribe Ltd (registered in England and Wales #06165574) 

Under contract for

Alexandra Rose Charity (registered in England and Wales #00279157) 

As such, unless otherwise specified in the appropriate component source, associated file or compiled asset, files in this project repository are Copyright &copy; 2019 Alexandra Rose Charity, All Rights Reserved.

If you wish to discuss copyright or licensing issues, please contact:

Alexandra Rose Charity

c/o Wise & Co, 
Wey Court West, 
Union Road, 
Farnham, 
Surrey, 
England,
GU9 7PT
