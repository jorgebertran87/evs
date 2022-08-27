### GET /status

Indicate the service has started up correctly and is ready to accept requests.

Responses:

* **200 OK** When the service is ready to receive requests.

### PUT /evs

Load the list of available EVs in the service and remove all previous data
(existing journeys and EVs). This method may be called more than once during
the life cycle of the service.

**Body** _required_ The list of EVs to load.

**Content Type** `application/json`

Sample:

```json
[
  {
    "id": 1,
    "seats": 4
  },
  {
    "id": 2,
    "seats": 6
  }
]
```

Responses:

* **200 OK** When the list is registered correctly.
* **400 Bad Request** When there is a failure in the request format, expected
  headers, or the payload can't be unmarshalled.

### POST /journey

A group of people requests to perform a journey.

**Body** _required_ The group of people that wants to perform the journey

**Content Type** `application/json`

Sample:

```json
{
  "id": 1,
  "people": 4
}
```

Responses:

* **200 OK** or **202 Accepted** When the group is registered correctly.
* **400 Bad Request** When there is a failure in the request format or the
  payload can't be unmarshalled.

### POST /dropoff

A group of people requests to be dropped off whether they traveled or not.

**Body** _required_ The ID of the group

**Content Type** `application/json`

Sample:

```json
{
  "id": 1
}
```

Responses:

* **200 OK** or **204 No Content** When the group is unregistered correctly.
* **404 Not Found** When the group cannot be found.
* **400 Bad Request** When there is a failure in the request format or the
  payload can't be unmarshalled.

### POST /locate

Given a group ID such as `ID=X`, return the car the group is traveling
with, or no car if they are still waiting to be served.

**Body** _required_ The ID of the group

**Content Type** `application/json`

Sample:

```json
{
  "id": 1
}
```

**Accept** `application/json`

Responses:

* **200 OK** With the car as the payload when the group is assigned to a car.
* **204 No Content** When the group is waiting to be assigned to a car.
* **404 Not Found** When the group cannot be found.
* **400 Bad Request** When there is a failure in the request format or the
  payload can't be unmarshalled.
