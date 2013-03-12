server = "http://mcp.asap.um.maine.edu/gradschool/grad_application/application/pages/login.php"

Given /I am on the home page/ do
  visit server
  response_body.should contain('Sign In')
end

Given /I am logged out/ do
  click_link 'Logout' if not contain('')
end

When /I click on (.*)/ do |link|
  click_link link
end

Then /I should see the login page/ do
  response_body.should have_xpath(%\//input[@name='username']\)
  response_body.should have_xpath(%\//input[@name='password']\)
  response_body.should contain("Login Required")
end
