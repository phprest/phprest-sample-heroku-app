module.exports = {
    config: {
        url: 'http://localhost'
    },
    frisby: function() {
        var frisby = require('./node_modules/frisby');

        frisby.globalSetup({
            request: {
                headers: {
                    'Accept': '*/*'
                }
            }
        });

        return frisby;
    }
};
