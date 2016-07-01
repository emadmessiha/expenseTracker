# expenseTracker
This is a simple web application written in PHP/MYSQL/HTML/CSS/JAVASCRIPT that enables a user to import financial transaction spreadsheets from their respective banking institutions and analyze that data to understand where they are spending their money and become more aware of their financial trends ;)

NOTE: The application still needs some refactoring for scaling.

----------
## Data entry
Data entry is done by importing Excel spreadsheets directly that you would export from your bank's online banking application. The files responsible for import are:

 - **upload.php**
 - **upload_file.php**
## Dashboard
When you open the application, the dashboard is displayed (**index.php**) which shows charts with income and expenses based on a given period of time (adjustable bu the user through a simple form). Check screenshot: **ApplicationHomePageScreenshot.png**

Data powering these javascript  charts and grid comes from the following files through ajax:

 - **fetch_data.php**
 - **pie_chart.php**
 - **get_transactions.php**

You also have the ability to manually edit transaction entries through the grid control. This is accomplished by **edit_transaction.php**.
## Data access and Database
MySQL scripts are saved under the **db_scripts** to help build the database.
A data access layer is crated using the following files:

 - **generate_data_access.php** (generates **da.php**)
 - **generate_cache.php** (generates **cache.php**)

**db_connect.php** is used as a helper to connect to the database, build queries and execute them.

## Other capabilities
The code also has the ability to go through transactions (records) and tag them based on their type (**tag_records.php**). As well as manage stores (add/edit).
