var AgendaItem = function(element) {
    this.name = element.element(by.binding('node.name'));
    this.input = element.element(by.model('node.name'));
    this.saveButton = element.element(by.css('button[type=submit].btn-primary'));
    this.cancelButton = element.element(by.css('button[type=submit].btn-default'));

    this.addButton = element.element(by.css('a.agenda-node-add'));
    this.editButton = element.element(by.css('a.agenda-node-edit'));
    this.deleteButton = element.element(by.css('a.agenda-node-delete'));

    this.subItems = element.all(by.css('ol > li'));

    this.setName = function(name) {
        this.input.clear().sendKeys(name);
        return this;
    };

    this.edit = function() {
        this.editButton.click();
        return this;
    };

    this.save = function() {
        this.saveButton.click();
        return this;
    };

    this.cancel = function() {
        this.cancelButton.click();
        return this;
    };

    this.deleteItem = function() {
        this.deleteButton.click();
        return this;
    };

    this.addSubItem = function() {
        this.addButton.click();
        return new AgendaItem(this.subItems.last());
    };
};

describe('agenda', function() {
    var rootAddButton = element(by.css('#agenda > div > a.btn-success'));
    var rootNodes = element.all(by.css('#agenda #tree-root > li'));

    it('should add a agenda item', function() {
        element(by.css('a[href="#agenda"]')).click();
        // create first item
        rootAddButton.click();
        var first = new AgendaItem(rootNodes.first());
        first.setName('Item 1').save();
        expect(first.name.getText()).toEqual('Item 1');
    });
    it('shoul add a second item', function() {
        rootAddButton.click();
        var item = new AgendaItem(rootNodes.get(1));
        item.setName('Item 2').save();
        // change and cancel
        item.edit().setName('adsfadsfa').cancel();
        expect(item.name.getText()).toEqual('Item 2');
    });
    it('should remove non saved item', function() {
        rootAddButton.click();
        var item = new AgendaItem(rootNodes.get(2));
        item.setName('Item 3').cancel();
        expect(rootNodes.count()).toBe(2);
    });
    it('should add a sub item', function() {
        var item = new AgendaItem(rootNodes.first());
        var subItem = item.addSubItem().setName('Sub Item 1').save();
        expect(subItem.name.getText()).toEqual('Sub Item 1');
        expect(item.subItems.count()).toBe(1);
    });
    it('should add a sub item and canel editing it', function() {
        var item = new AgendaItem(rootNodes.first());
        item.addSubItem().setName('Sub Item 2').cancel();
        expect(item.subItems.count()).toBe(1);
    });
    it('should add a sub item and remove it', function() {
        var item = new AgendaItem(rootNodes.first());
        item.addSubItem().setName('Sub Item 2').save().deleteItem();
        expect(item.subItems.count()).toBe(1);
    });
});
