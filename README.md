[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4bdfba57-986f-485d-9905-64130742d59d/big.png)](https://insight.sensiolabs.com/projects/4bdfba57-986f-485d-9905-64130742d59d)

# Bilemo

Ecommerce API for project 7 on Openclassroom

## Requirements

For works this project require PHP7 and Composer.

## Installation

### With Git

You can use git and clone the repository on your folder.

```sh
cd /path/to/myfolder
git clone https://github.com/yanb94/bilemo.git
```  

### With Folder

You can download the repository at zip format and unzip it on your folder

### Install dependencies 

Install dependencies with composer.

```sh
composer update
```

### Virtual Host

For optimal working it is recommended to use a virtual host who are pointing on the folder public.

```apache
<VirtualHost *:80>
	ServerName bilemo
	DocumentRoot "path/to/my/project/public"
	<Directory  "path/to/my/project/public/">
		Options +Indexes +Includes +FollowSymLinks +MultiViews
		AllowOverride All
		Require local
	<IfModule mod_rewrite.c>
            Options -MultiViews
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ index.php [QSA,L]
        </IfModule>
	</Directory>
	RewriteEngine On
        RewriteCond %{HTTP:Authorization} ^(.*)
        RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
</VirtualHost>
```
## Configuration

For that project works it is necessary to add a file **.env** 

**.env**

```
###> symfony/framework-bundle ###
APP_ENV=<Your environnement 'dev' or 'prod'>
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL=<Your database connection>
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_PRIVATE_KEY_PATH=<Your private key path>
JWT_PUBLIC_KEY_PATH=<Your public key path>
JWT_PASSPHRASE=<Your passphrase>
###< lexik/jwt-authentication-bundle ###

```

For optimal functionnal of the API you need to generate SSH keys and add the path of those keys to **.env**.

For more information see LexikJWTAuthenticationBundle Doc : https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation 

## Initialize Project

### Create DataBase

For create the database of project execute this following command
```sh
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
```

### Load the initial list of products

For init the initial list of products execute this following command

```sh
php bin\console doctrine:fixtures:load
```

## Usage

### Authentification

For be authentified you need to obtain an access token from JWT.

For obtained this token you need to connect with your username and you password on Json Format on this url : */api/login_check*. 

```json
{
	"username": <Your username>,
	"password": <Your password>
}
```

When your user is logged you received an acess token that you have to pass to header of api request like this :

```sh
Authorization : Bearer {access token}
```