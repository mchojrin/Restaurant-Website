# Restaurant Food Order Theme
This is a Restaurant Theme Website Template, designed using html and css. It was developed while teaching "Responsive Web Design Course".

**Access the Course Here** - 
[Responsive Web Design Course 2020](https://www.youtube.com/watch?v=VaV_Ro8jpPY)


## Run with Docker

The fastest way to run this PHP/MySQL app locally. Requires Docker with the Compose plugin.

1. **Configure (optional).** The repo ships with a working `.env`; to start from the template instead:
   ```bash
   cp .env.example .env
   ```
2. **Start the stack.**
   ```bash
   docker compose up -d --build
   ```
3. **Open the app:** [http://localhost:8080](http://localhost:8080)

   Admin panel: [http://localhost:8080/admin/login.php](http://localhost:8080/admin/login.php)

   Seeded local-dev admin credentials:
   - Username: `katojkalemba`
   - Password: `Kalemba@2000`

**How the database is initialised:** on first startup MariaDB creates the `food_order` database from `MARIADB_DATABASE` and imports `databasefiles/food_order.sql` (mounted read-only at `/docker-entrypoint-initdb.d/01-food_order.sql`). The dump itself contains no `CREATE DATABASE`/`USE`, so the database must be created by the container environment. This import only runs when the `db_data` volume is empty.

**Changing DB credentials:** MariaDB only creates the database and user on *first* initialisation. If you edit `DB_NAME`, `DB_USER`, or `DB_PASSWORD` in `.env` after the `db_data` volume exists, the change will not be applied to the existing database. Reset the volumes to re-initialise (this deletes database data **and** uploaded images):
```bash
docker compose down -v
docker compose up -d --build
```

Uploaded food/category images are stored in the `images_data` volume, which is seeded from the image on first run, so uploads survive restarts and rebuilds.

**Change the port:** edit `APP_PORT` in `.env` (e.g. `APP_PORT=8000`) and run `docker compose up -d` again. Alternatively set it inline: `APP_PORT=8000 docker compose up -d`.

**SITEURL:** defaults to `/` (root-relative), which works on any host/port. If you need absolute URLs, set e.g. `SITEURL=http://localhost:8080/` in `.env` — a trailing slash is added automatically.

**Reset everything** (database *and* uploaded images):
```bash
docker compose down -v
docker compose up -d --build
```

**Logs / stop:**
```bash
docker compose logs -f web
docker compose down
```

## Running tests (Codeception)

Acceptance tests run **from the host** against the app over HTTP, so the Docker
stack must be running (`docker compose up -d --wait`). They are read-only: no
database writes, no application files touched.

```bash
# One-time (or after composer.lock changes): install the test tooling on the host.
composer install

# Run the acceptance suite against http://localhost:8080 (the default APP_URL).
vendor/bin/codecept run Acceptance
# ...or via the composer script:
composer test:acceptance
```

The base URL comes from `APP_URL` (see `tests/params.php`). Override it when the
app is published on another port or when targeting the Compose network:

```bash
APP_URL=http://localhost:8000 composer test:acceptance
APP_URL=http://web vendor/bin/codecept run Acceptance
```

Failure artifacts (for example the `.failed` page dump) and any reports you
enable are written to `tests/_output/`. Run all suites with `composer test`
(`codecept run`).

Testing tooling is excluded from the runtime image via `.dockerignore`, so
`vendor/`, `tests/`, `codeception.yml` and the composer files stay on the host.

## Running unit tests (Kahlan)

Pure unit specs for the small helpers declared inline in the page files run
**from the host** and do **not** require Docker:

```bash
composer test:unit
# ...or directly:
vendor/bin/kahlan
```

Specs live in `spec/`. The app is procedural and the production files must not
be edited, so the test-only loader in `spec/support/inline-functions.php` reads
the existing inline `getPreview` (from `index.php`) and `isOnlyLetters` (from
`admin/add_food.php`) definitions from the real source at runtime. The
application files themselves are byte-for-byte untouched.

The broader page validation, password and image rules remain covered by the
Codeception acceptance suite above, not by these unit specs.

## Support Developer
1. Subscribe & Share my YouTube Channel - https://bit.ly/vijay-thapa-online-courses
2. Add a Star 🌟  to this 👆 Repository

## Donate

**[PayPal](https://bit.ly/support-vijay-thapa)**

**[Buy me a Coffee  ☕️](https://www.buymeacoffee.com/vijaythapa)**

**Donate by wire transfer:** ✉️ E-Mail at *donate@vijaythapa.com* for wire transfer details. 



## Technologies Used
1. HTML5
2. CSS3


## Pages on this Complete Free Template
1. **index.html** - Home Page (Search Food, Some Categories, Featured Foods, Social Media link)
2. **categories.html** - List all Categories on Single Page
3. **foods.html** - List all foods on a single page
4. **order.html** - Page to Order Selected Food
5. **category_foods.html** - Page to list all the Foods based on Category Selected
6. **food_search.html** - Page to list all the Foods based on Search keyword


## For Sponsor or Project Enquiry
1. Email - hi@vijaythapa.com


## Follow Me on
1. LinkedIn - [vijaythapa](https://www.linkedin.com/in/vijaythapa/ "Vijay Thapa on LinkedIn")
2. Instagram - [@vijaythapa.code](https://www.instagram/vijaythapa.code/ "Vijay Thapa on Instagram")
3. Facebook - [@thevijaythapa](https://www.facebook.com/thevijaythapa/ "Vijay Thapa on Facebook")
5. Twitter - [@thevijaythapa](https://www.twitter.com/thevijaythapa "Vijay Thapa on Twitter")
