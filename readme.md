
[![](https://i.ibb.co/VqBZvn5/logoevo.png)](https://tibia-evolution.com/index.php?threads/premium-gesior-acc-2022-tibia-evolution-12-7x.8/#post-28)

------------


![](https://i.ibb.co/GPm9VYP/Gesior.png)

Requisitos:
- Apache con mod_rewrite habilitado + PHP Versión 5.6 o posterior

CÓMO INSTALAR: 
 1. Clona el Gesior-ACC desde GitHub.
 1. cambie el permiso para escribir en /cache.


    sudo chmod -R 777 /cache
Consejos y trucos:
Este sitio web tiene algunos implementos de seguridad, aquí puede usar apache2 para aplicarlos.
ejecute estos comandos para maximizar su seguridad.




        sudo a2enmod headers
        sudo a2enmod rewrite 
en ubuntu 16.06 o 14.04 vaya a la carpeta apache y edite su configuración.
correr:
- sudo vim /etc/apache2/apache2.conf 
y busca algo como esto:



    <Directory PATH_TO_YOUR_WEBSITE>
            Options Indexes FollowSymLinks
            AllowOverride All
            Require all granted         
    </Directory>
y añade algo como esto /\

PHP NECESITA QUE LO SIGUIENTE


    sudo apt-get install php5-gd
    sudo apt-get install php5-curl
Asegúrese de que curl esté habilitado en el archivo php.ini. Para mí está en /etc/php5/apache2/php.ini, si no puedes encontrarlo, esta línea podría estar en /etc/php5/conf.d/curl.ini. Asegúrese de que la línea : extension=curl.so

ahora reinicie apache.:



    sudo /etc/init.d/apache2 restart
o



    sudo service apache2 restart
PARA PROBLEMAS DE CONTABILIDAD DE UBUNTU
Si tiene problemas para registrarse usando ubuntu o cualquier otra versión de php donde el sitio afirma haberse registrado pero no se hizo, simplemente ejecute el siguiente comando en su base de datos:

    

    SET GLOBAL sql_mode = '';
**
PARTNERS**


[![Partners](http://tibia-evolution.com/styles/xenfocus/dimension/backgrounds/logo.png "Partners")](https://tibia-evolution.com/ "Partners")
