Feature: Authentication
  In order to use the Graduate School Application Site
  As a user
  I want to be able to register, login, and logout

Scenario: Hom
	Given I am on the home page
	And I am logged out
	When I click on Login
	Then I should see the login page

Scenario: Registration
	Given I am on the registration page
	And I am logged out
	When I fill in the username input with **username**
	And I fill in the password input with **password**
	And I click the button Submit
	Then I should see the logged in page


Scenario: Registration Failure
	Given I am on the registration page
	And I am logged out
	When I fill in the username input with **username**
	And I fill in the password input with **password**
	And I click the button Submit
	
	Then I should see the logged in page


	function itShouldRequireUsernameEmailAndPasswordWhenRegistering()
	{

	}

	function itShouldEmailAfterSuccessfulRegistration()
	{

	}

	function itShouldRequireConfirmationLinkBeforeLoginAndAfterRegistration()
	{

	}


	function itShouldLoginSuccessfullyForCorrectCredentials()
	{
		// Test user credentials

		// User logged in

		// First page should be
	}

	function itShouldFailAndDisplayErrorForIncorrectCredentials()
	{

	}

	function itShouldLogoutSuccessfullyWhenLoggedIn()