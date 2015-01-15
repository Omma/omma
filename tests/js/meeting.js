describe('create meeting', function() {
    var url = null;
    it('should create a new meeting', function() {
        browser.get(browser.params.address+"/dashboard");
        element(by.id('create-meeting')).click();
        var title = element(by.css('.meeting-name'));
        expect(title.getText()).not.toBeNull();
        url = browser.getLocationAbsUrl();
    });
    it('should change the meeting name', function() {
        var titleElement = element(by.css('.meeting-name'));
        var titleBinding = element(by.binding('meeting.name'));
        titleElement.element(by.css('h1')).click();
        titleElement.element(by.css('input[type=text]'))
            .clear()
            .sendKeys('Test Meeting')
        ;
        // submit form
        titleElement.element(by.css('button[type=submit]')).click();
        expect(titleBinding.getText()).toEqual('Test Meeting');
    });
    it('should not change the meeting name, when aborting', function() {
        var titleElement = element(by.css('.meeting-name'));
        var titleBinding = element(by.binding('meeting.name'));
        titleElement.element(by.css('h1')).click();
        titleElement.element(by.css('input[type=text]'))
            .clear()
            .sendKeys('asdfasdfadsf')
        ;
        // abort form
        titleElement.element(by.css('button[type=button]')).click();
        expect(titleBinding.getText()).toEqual('Test Meeting');
    });
    it('should change the date via datepicker', function() {
        var startDate = element(by.id('startDate'));
        var initialDate = startDate.getAttribute('value');
        var endDate = element(by.id('endDate'));
        startDate.click();
        // next month
        element(by.css('.daterangepicker .next')).click();
        var days = element.all(by.css('.daterangepicker tbody td.available'));
        days.get(20).click();
        expect(startDate.getAttribute('value')).not.toEqual(initialDate);
        // end date should be the same as start date
        expect(endDate.getAttribute('value')).toEqual(startDate.getAttribute('value'));
    });
});
