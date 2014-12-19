var bootstrap = require('../../bootstrap'),
    frisby = bootstrap.frisby(),
    config = bootstrap.config;

frisby.create('Post /temperatures [correct]')
    .post(config.url + '/temperatures', {},
        {
            body: '{ "value": 10, "created": "2014-12-01T10:00:00Z" }',
            headers: { 'Accept': '*/*', 'Content-Type': 'application/json' }
        })
    .expectStatus(201)
.toss();

frisby.create('Post /temperatures [wrong Content-Type]')
    .post(config.url + '/temperatures', {},
    {
        body: '{ "value": 150 }',
        headers: { 'Accept': '*/*', 'Content-Type': 'application/yml' }
    })
    .expectStatus(415)
.toss();

frisby.create('Post /temperatures [wrong data]')
    .post(config.url + '/temperatures', {},
    {
        body: '{ "value": 150 }',
        headers: { 'Accept': '*/*', 'Content-Type': 'application/json' }
    })
    .expectStatus(422)
.toss();

frisby.create('Get /temperatures/1')
    .get(config.url + '/temperatures/1')
    .expectStatus(200)
    .expectJSON({
        "id": 1,
        "unit": "celsius",
        "value": 10,
        "created": "2014-12-01T10:00:00+0000",
        "_links": {
            "self": {
                "href": "http://localhost/temperatures/1"
            }
        }
    })
.toss();

frisby.create('Get /temperatures/0 [Non-exist]')
    .get(config.url + '/temperatures/0')
    .expectStatus(404)
.toss();
