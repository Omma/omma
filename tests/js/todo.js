var Editable = function(element) {
    this.link = element.element(by.css('a'));
    this.input = element.element(by.css('.editable-input'));
    this.saveButton = element.element(by.css('form .editable-buttons button[type=submit]'));
    this.cancelButton = element.element(by.css('form .editable-buttons button[type=button]'));
    this.click = function() {
        this.link.click();
        return this;
    };
    this.setText = function(text) {
        this.input.clear().sendKeys(text);
        return this;
    };
    this.getText = function() {
        return element.getText();
    };
    this.save = function() {
        this.saveButton.click();
        return this;
    };
    this.cancel = function() {
        this.cancelButton.click();
        return this;
    };

};

var Todo = function(element) {
    this.titleBinding = element.element(by.binding('todo.task'));
    this.title = new Editable(element.element(by.css('.edit-title')));
    this.descriptionBinding = element.element(by.binding('todo.description'));
    this.description = new Editable(element.element(by.css('.edit-description')));
};

describe('todo', function() {
    var addButton = element(by.css('#todos #todo-add'));
    var todos = element.all(by.repeater('todo in todos'));
    it('should add a new todo', function() {
        element(by.css('a[href="#todos"]')).click();
        addButton.click();
        expect(todos.count()).toBe(1);
    });
    it('should change the title', function() {
        var todo = new Todo(todos.last());
        todo.title
            .click()
            .setText('Test Todo')
            .save()
        ;
        expect(todo.titleBinding.getText()).toEqual('Test Todo');
    });
    it('should change the title and cancel', function() {
        var todo = new Todo(todos.last());
        todo.title
            .click()
            .setText('asdfasdfasdfasdf')
            .cancel()
        ;
        expect(todo.titleBinding.getText()).toEqual('Test Todo');
    });
    it('should change the description', function() {
        var todo = new Todo(todos.last());
        var text = 'test\nfoo\nbar\nbaz';
        todo.description
            .click()
            .setText(text)
            .save()
        ;
        expect(todo.descriptionBinding.getText()).toEqual(text);
    });
});
