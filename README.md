
# TTI Backend Challenge
This repo contains the Symfony code for the TTI Backend Challenge.

## Setting up the environment

Start the MySQL docker container by running the following command:
```
docker run --name tti -e MYSQL_ROOT_PASSWORD=1234 -e MYSQL_DATABASE=tti -p 3306:3306 -d mysql:8.0.31
```

Run the following commands to load the user data into the database using the Data Fixtures and Faker:
```
# Generate migrations.
php bin/console make:migration

# Execute migrations.
php bin/console doctrine:migrations:migrate

# Load fixtures.
php bin/console doctrine:fixtures:load
```

After successfully loading the data start the symfony server with the following command:
```
symfony server:start
```

Then, navigate to the homepage usually it's http://127.0.0.1:8000/ then login to any account by getting the email and password from the MySQL database.

Once you are successfully authenticated you can navigate to /patients endpoint to view all the patient data if the role of the user is doctor otherwise you'll see an permission error.

The following endpoints are available:
1. /login
2. /logout
3. /patients
4. /patient/{patient_id}
