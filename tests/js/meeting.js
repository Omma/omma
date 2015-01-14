describe('create meeting', function() {
    it('should create a new meeting', function() {
        browser.get(browser.params.address+"/dashboard");
        element(by.id('create-meeting')).click();
        var title = element(by.css('.meeting-name'));
        expect(title.getText()).not.toBeNull();
    });
});
