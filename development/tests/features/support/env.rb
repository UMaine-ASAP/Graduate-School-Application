#require_relative "../.."
# require "Capybara"
# require "Capybara/cucumber"
# #require "rspec"
# World do
#   #Capybara.app = MyApp
#   include Capybara::DSL
#   include RSpec::Matchers
# end

# RSpec
require 'rspec/expectations'

# Webrat
require 'webrat'

require 'test/unit/assertions'
World(Test::Unit::Assertions)

Webrat.configure do |config|
  config.mode = :mechanize
end

World do
  session = Webrat::Session.new
  session.extend(Webrat::Methods)
  session.extend(Webrat::Matchers)
  session
end