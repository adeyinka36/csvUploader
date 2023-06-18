<h1>Hayden's Garage Booking System</h1>

<p>Welcome to Hayden's Garage Booking System! This document will guide you through the steps needed to get the project up and running.</p>

<h2>Getting Started</h2>

<strong>Api routes  are specified in the Documentation.yaml file in the root directory of the project. This file can be pasted into https://editor.swagger.io which will display the documentation and all endpoints which are available.</strong>

<p>Follow these steps to set up the project:</p>

<ol>
    <li>The laravel file resides in the <code>/src</code></li>
    <li><strong>Clone the repository:</strong> Run <code>git clone</code> to clone the repository to your local machine.</li>
    <li><strong>Set up the environment:</strong> Copy <code>src/.env.example</code> to <code>src/.env</code> and update the environment variables as necessary.</li>
    <li><strong>Create the SQLite database file:</strong> Create a SQLite database file called "database.sqlite" in the path /database/database.sqlite and update the db values in the env using the src/.env.example details</li>
    <li><strong>Start Docker containers:</strong> Run <code>docker-compose up -d --build</code> to start the Docker containers.</li>
    <li><strong>Enter the Docker container:</strong> Use the command <code>docker-compose exec -it store_php sh </code> to enter the PHP Docker container.</li>
    <li><strong>Install PHP dependencies:</strong> Run <code>composer install</code> to install the necessary PHP dependencies.</li>
    <li><strong>Run migrations:</strong> Use <code>php artisan migrate:fresh</code> to run the migrations and create the necessary table in the database.</li>
    <li><strong>Using Command:</strong> Use <code>php artisan import:homeowners path to file</code> to get persons from desired csv file and store in persons table</li>
    <li><strong>Tests:</strong> create the src/.env.testing file and run  <code>php artisan test</code> to run the feature tests.</li>
    <li>Inside the PHP container run <code>npm install && npm run dev</code> to serve the vue app</li>
</ol>

<h2>Contact</h2>

<p>Please get in touch with the creator of this project at <a href="mailto:adeyinka.giwa36@gmail.com">adeyinka.giwa36@gmail.com</a> for any questions or queries.</p>

