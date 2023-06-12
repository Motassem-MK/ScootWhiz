## Assumptions

### Business
- Scooters can be moved offline, so when starting a trip we consider their current location from the request instead of the one in the system
- The task names two cities in which the app will cover, however there were no requirements in this regard so this detail was ignored
- A client is not limited to using only 1 scooter

### Technical
- Server time will be used for trip-related requests, in a real world system where network delays are inevitable a comprehensive validation of a timestamp received from the client can be used to avoid overcharges
- Localisation is not required for server responses / error messages
- Logging is outside the scope
- No pagination is required when listing scooters
