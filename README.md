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

```

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

For be authentified you need to obtain an access token from facebook.
For doing this you need to create a Facebook App with facebook developper account here : https://developers.facebook.com/

This app has to contain facebook login and use the scope public_profile and email.

Exemple of code to keep access token:

```html
<!DOCTYPE html>
<html lang="fr">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Example to get access token</title>
  </head>
  <body style="height:100vh;display:flex;align-items: center;justify-content: center;flex-direction: column;">
	  <div id="access_token" style="width:50%;word-wrap: break-word;"></div>

	  <fb:login-button 
	    scope="public_profile,email"
	    onlogin="checkLoginState();">
	  </fb:login-button>

	   <script>
			window.fbAsyncInit = function() {
			  FB.init({
			    appId      : '<your Api id>',
			    cookie     : true,
			    xfbml      : true,
			    version    : 'v2.12'
			  });
			    
			  FB.AppEvents.logPageView();   
			    
			  FB.getLoginStatus(function(response) {
			    if (response.status === 'connected') {
			      document.getElementById("access_token").innerHTML = "<b>Access Token </b>"+response.authResponse.accessToken;
			    }
			  });
			};

			function checkLoginState() {
			    FB.getLoginStatus(function(response) {
			      if (response.status === 'connected') {
			        document.getElementById("access_token").innerHTML = "<b>Access Token </b>"+response.authResponse.accessToken;
			      }
			    });
			  }
			   
			(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = 'https://connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.12&appId=537965866575455&autoLogAppEvents=1';
			fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
	      
	  </script>
  </body>
</html>
```

For adapt the way to recover the access token see the facebook documentation : https://developers.facebook.com/docs/ 

When your user is logged you received an acess token that you have to pass to header of api request like this :

```sh
Authorization : Bearer {access token}
```

