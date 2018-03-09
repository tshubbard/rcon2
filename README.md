## Rcon2 Quick Start

#### Add the following to your host file
```
# rcon2 Repo
192.168.10.25 rcon2.test
```

#### Clone the Repo
```
git clone git@bitbucket.org:tshubbard/rcon2.git

```

#### EDIT THE rcon2/Homestead.yaml file for your REPO path.
CHANGE THE 'map:' paths of both folders to wherever your /rcon2/site|streamer is located
```
folders:
    -
        map: ~/code/rcon2/site
        to: /home/vagrant/site
```

#### Continue with setup
```
cd rcon2
composer install
vagrant up
cd site
npm install
cd ../streamer
npm install
```

#### Setup inside Vagrant box
```
vagrant ssh
cd site
composer install
// this will rebuild autoloader with all Classes not added thru artisan make:
composer dump-autoload
// this will update the database
php artisan migrate:refresh --seed
```

Run dev or production tasks to auto minify. NEVER put files in public/ directory. Use resources/assets and it will be copied over.
```
# SSH into the VM
vagrant ssh

# Navigate to the site directory
cd site

# Run dev or production minification process
npm run dev
-or-
npm run prod
```

You should be able to view the site from http://rcon2.app

## MySQL



Credentials
```
Host: 192.168.10.25
Port: 3306
DB Name: rcon2
Username: homestead
Password: secret
```
