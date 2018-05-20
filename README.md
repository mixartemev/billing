<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Simplest billing system based on Yii 2 Basic Project Template</h1>
    <br>
</p>


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

*If (* you happy linux user, you can install this project using the one following command: *) {*

~~~
git clone git@github.com:mixartemev/billing.git && cd billing && sh install.sh root 321
~~~

*} else {*

you should make this steps manually:

**1. Clone project**
~~~
git clone git@github.com:mixartemev/billing.git
~~~
or, if you use http protocol
~~~
git clone https://github.com/mixartemev/billing.git
~~~


**2. Go to project dir**
~~~
cd billing
~~~

**3. Get dependencies**
~~~
composer install
~~~

**4. Configure database access**

Edit the file `config/db.php` with real data, for example:
```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=billing',
    'username' => 'root',
    'password' => '321',
];
```
*NOTES:*
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


**5. Create databese named "billing"**
~~~
mysqladmin -u {mysql_login} -fp{mysql_password} create billing
~~~

**6. Create database structure and fill it**
~~~
./yii migrate
~~~

**7. Set up cron job: daily currency rate logger**
echo '0 11 * * * ~/billing/yii cli/get-currency-rates' >> /var/spool/cron/crontabs/`whoami`

*}*

#### Forward your web server domainName to "~/billing/web/" dir, and

**Check this out:**
~~~
http://domainName/
~~~

DESCRIPTION
-----------

HTTP API представляет следующие интерфейсы:
1) регистрация клиента с указанием его имени, страны, города регистрации, валюты
создаваемого кошелька.



2) зачисление денежных средств на кошелек клиента
3) перевод денежных средств с одного кошелька на другой.
4) загрузка котировки валюты к USD на дату

7) Отчет должен отображать историю всех операций по кошельку указанного клиента за период.
1) Параметры: Имя клиента (обязательный параметр), Начало периода (необязательный
параметр), конец периода (необязательный параметр).
2) Необходимо также вывести общую сумму операций по счету за период в USD и валюте счета
3) Должна быть предусмотрена возможность скачивания результатов отчета в файл (например,
в CSV или XML формате).