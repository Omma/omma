describe('agenda', function() {
    it('should add multiple agenda items', function() {
        element(by.css('a[href="#agenda"]')).click();
        // create first item
        var rootAddButton = element(by.css('#agenda a.btn-success'));
        rootAddButton.click();
        var rootNodes = element.all(by.css('#agenda #tree-root > li'));
        rootNodes.first().element(by.model('node.name')).sendKeys('Item 1');
        rootNodes.first().element(by.css('button[type=submit].btn-primary')).click();
        browser.pause();
    });
});
