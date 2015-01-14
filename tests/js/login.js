// spec.js
describe('angularjs homepage', function() {
    it('should be able log in', function() {
        browser.get(browser.params.address);
        element(by.css('input[name=_username]')).sendKeys(browser.params.user.name);
        element(by.css('input[name=_password]')).sendKeys(browser.params.user.password);
        element(by.css('input[type=submit].btn-primary')).click();
        expect(browser.getCurrentUrl()).toEqual(browser.params.address + '/dashboard');
    });
});
