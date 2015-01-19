var ScreenShotReporter = require('protractor-screenshot-reporter');
var path = require('path');

exports.config = {
    seleniumAddress: 'http://localhost:4444/wd/hub',

    specs: [
        'login.js',
        'meeting.js',
        'agenda.js',
        'todo.js',
    ],
    multiCapabilities: [
        {
            browserName: 'chrome'
        },
        {
            browserName: 'firefox'
        }
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
        var number = 0;
        // Add a screenshot reporter and store screenshots to `/tmp/screnshots`:
        jasmine.getEnv().addReporter(new ScreenShotReporter({
            baseDirectory: path.join(__dirname) + '/screenshots',
            pathBuilder: function pathBuilder(spec, descriptions, results, capabilities) {
                // Return '<browser>/<specname>' as path for screenshots:
                // Example: 'firefox/list-should work'.
                number++;
                return number + ' - ' + descriptions.join('-');
            }
        }));
    }
};
