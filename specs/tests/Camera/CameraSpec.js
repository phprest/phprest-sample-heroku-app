var bootstrap = require('../../bootstrap'),
    frisby = bootstrap.frisby(),
    config = bootstrap.config;

frisby.create('Get /camera')
    .get(config.url + '/camera')
    .expectStatus(200)
.toss();

frisby.create('Post /camera?transition=on')
    .post(config.url + '/camera?transition=on')
    .expectStatus(200)
.toss();

frisby.create('Get /camera')
    .get(config.url + '/camera')
    .expectStatus(200)
    .expectJSON({
        "state": "on"
    })
.toss();

frisby.create('Post /camera?transition=on')
    .post(config.url + '/camera?transition=on')
    .expectStatus(400)
.toss();
