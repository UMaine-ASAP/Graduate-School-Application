Given /^I am on the home page$/ do
  visit "http://gradapp/login"
end


Then /^I should see "(.*?)"$/ do |text|
  assert !!(response_body =~ /#{Regexp.escape text}/m)
end

Given /^I am logged in using "(.*?)" "(.*?)"$/ do |username, password|
  visit "http://gradapp/login"
  fill_in "email", :with => username
  fill_in "password", :with => password
  click_button "signin"
  #assert !!(response_body =~ /#{Regexp.escape username}/m)
end

When /^I am viewing "(.*?)"$/ do |url|
	visit url
end

Then /^I should visit "(.*?)"$/ do |username|
  #assert !!(false)
  #assert !!(response_body =~ /#{Regexp.escape text}/m)
end
