Feature: The framework can be uploaded
  In order to copy a framework from another server
  As an editor
  I need to upload a CASE file of the framework

  @smoke @editor @case-file @case-file-102
  Scenario: A CASE file can be uploaded and downloaded
    Given I log in as a user with role "Editor"
    And I am on the homepage
    When I click "Import framework"
    Then I should see the import dialogue
    When I click "Import CASE file"
    And I upload the empty remote CASE file
    And I go to the uploaded framework
    And I download the framework CASE file
    Then the downloaded framework should match the uploaded one

