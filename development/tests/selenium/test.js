var client = require('./client').client;
var expect = require('chai').expect;
 
var webroot = "http://application.gradschool.asap.dev";
//var webroot = "http://mcp.asap.um.maine.edu";

// Perform tests
describe('Gradschool Application', function(){
    before(function(done) {
        client.init().url(webroot +'/login', done);
    });
 
    describe('Login', function(){
        it('page should exist', function(done) {
            client.getTitle(function(title){
                expect(title).to.have.string('Apply to the University');
                done();
            });
        });
 
        it('should see the body', function(done) {
            // client.getText('p', function(text){
            //     expect(text).to.have.string(
            //         'for illustrative examples in documents.'
            //     );
            //     done();
            // })
            done();
        });
    });
 
    after(function(done) {
        client.end();
        done();
    });
});