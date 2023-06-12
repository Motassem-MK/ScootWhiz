# ScootWhiz
Basic scooter trips management system

### Requirements
- Docker

### Stack
- PHP 8.2
- MySQL 8.0

### Installation
Run: `make setup` which will:

- Install dependencies
- Build and start the container
- Run all database migrations

### Testing
To run all tests: `make test`

### Code Quality
- `make phpcs`
- `make phpstan`

### Demo
To start the demo: `make demo` <br>
Exiting the demo will automatically clean up the generated data and the queued demo jobs

### API Docs
http://localhost/api/documentation

### Uninstallation
Run: `make remove` to delete the container and used volumes

### Database Walkthrough
#### We have 4 entities
- Client
- Scooter (contains the state and last reported location of the scooter)
- ScooterLocation (which contains historic locations reported for each scooter)
- Trip (Contains info about each trip)

#### Misc notes
- There's a MySQL trigger that updates Scooter's coordinates on each ScooterLocation insertion
- Pessimistic locks are used to prevent concurrent scooter reservation

### Folder Structure Walkthrough
Inside `app/` we have 4 main folders that contain the domain and infrastructure logic of each main concern:

- Client
- Scooter
- ScooterLocation
- Trip

Each of these folders contains a similar structure, including a model, events and listeners for handling actions related
to the entity, and a repository folder. <br>
The logic depends on an abstract repository, and we have one implementation for Eloquent which includes an Eloquent
model, repository data access logic and builders to convert between domain model and eloquent model.

The application mainly uses event-driven design, events and listeners bindings can be found
in `App\Providers\EventServiceProvider`

The validation logic for the Scooter's state can be found in `Scooter/State/Validator` however those validators are
injected through the container to provide flexibility in adding more states/validators, the relevant configuration can
be found in `App\Providers\Scooter\ScooterStateValidatorsProvider` and `App\Providers\Scooter\ScooterListenersProvider`

### Events
- TripStarting (Trip start is requested)
- TripStarted (Trip already started, we can start async background processing)
- TripUpdated
- TripEnding (similar to TripStarting)
- TripEnded

### Future
- Moving to a geospatial oriented DBMS (PostGIS, Elasticsearch..etc) for the whole application or for
  Scooter/ScooterLocation
- Using GeoHash to optimise the search for scooters

### Known Issues & Limitations
- Nothing happens if the scooter stops sending updates while the trip is ongoing
- Storing Lat/Long in a MySQL is not scalable and would cause a hassle if we need to implement zone restrictions or any
  advanced geospatial features

### Third-Party
- `nunomaduro/larastan` laravel oriented phpstan
- `darkaonline/l5-swagger` Swagger OpenAPI docs generator for Laravel
