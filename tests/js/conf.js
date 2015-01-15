var ScreenShotReporter = require('protractor-screenshot-reporter');

exports.config = {
    seleniumAddress: 'http://localhost:4444/wd/hub',
    specs: ['login.js', 'meeting.js', 'agenda.js'],
    multiCapabilities: [{
        browserName: 'chrome'
    },
    //{
    //    browserName: 'firefox'
    //}
    ],
    // Options to be passed to Jasmine-node.
    jasmineNodeOpts: {
        showColors: true, // Use colors in the command line report.
    },
    params: {
        address: 'http://omma.dev',
        user: {
            name: 'admin',
            password: 'admin'
        }
    },
    onPrepare: function() {
        // Add a screenshot reporter and store screenshots to `/tmp/screnshots`:
        jasmine.getEnv().addReporter(new ScreenShotReporter({
            baseDirectory: __dirname + '/screenshots'
        }));
    }
};
