Feature: Viewer visits the Home Page
  In order to read the page
  As a viewer
  I want to see the home page of my app

Scenario: View Home Page
  Given I am on the home page
  Then I should see "Sign In"


Scenario: Login
  Given I am logged in using "timbone945@gmail.com" "test123"
  When I am viewing "http://gradapp/application/section/personal-information"
  Then I should see "Personal Information"
