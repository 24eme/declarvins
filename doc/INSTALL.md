# dépendances

$ sudo aptitude install couchdb libapache2-mod-php5 php5-cli php5-ldap

# git

$ cd /var/www (ou à un autre emplacement)
$ git clone https://git.gitorious.org/declarvin/declarvin.git
$ cd declarvin
$ cat .gitmodules  | sed 's/git@gitorious.org:/https:\/\/git.gitorious\.org\//' > /tmp/gitmodules && mv /tmp/gitmodules .gitmodules
$ bash bin/update

# mise à jour du code 

A la racine du projet :

$ bash bin/update

# configuration d'apache

Ajouter un vhost avec les éléments suivants :

<pre>
<code>
        DocumentRoot /var/www/declarvin/project/web
        <Directory /var/www/declarvin>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>

        Alias /sf "/var/www/declarvin/project/lib/vendor/symfony/data/web/sf"
</code>
</pre>

# création de la base couchdb

$ curl -X PUT http://localhost:5984/declarvin

# configuration de symfony

$ cd project
$ mkdir log cache
$ chown www-data log cache
$ chmod g+wx log cache
$ cp config/databases.yml{.example,}

adapter l'adresse du couchdb si nécessaire

$ cp config/app.yml{.example,}

adapter les adresse du LDAP ou du CAS si nécessaire

# import/initialisation de données de l'application

$ bash bin/views
$ bash bin/import
$ php symfony cc