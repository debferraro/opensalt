<?php

use Codeception\Exception\Skip;
use Codeception\Util\Locator;

class CommentDocCest
{
    static public $docPath = '/cftree/doc/';

    public function _before(AcceptanceTester $I)
    {
        $toggles = $I->grabService('qandidate.toggle.manager');
        $context = $I->grabService('qandidate.toggle.context_factory');

        if (!$toggles->active('comments', $context->createContext())) {
            throw new Skip();
        }
    }

    // tests
    public function seeCommentsSectionAsAnAnonymousUser(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->seeElement('.jquery-comments');
        $I->see('To comment please login first');
    }

    public function dontSeeCommentsFormAsAnAnonymousUser(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->dontSeeElement('.jquery-comments .commenting-field');
        $I->see('To comment please login first');
    }

    public function seeCommentsSectionAsAnAuthenticatedUser(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $loginPage = new \Page\Login($I);
        $loginPage->loginAsRole('Editor');
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->seeElement('.commenting-field');
    }

    public function commentAsAnAuthenticatedUser(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $loginPage = new \Page\Login($I);
        $loginPage->loginAsRole('Editor');
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->createAComment('acceptance doc comment '.sq($I->getDocId()));
        $I->waitForJS('return $.active == 0;', 2);
        $I->see('acceptance doc comment '.sq($I->getDocId()), '.comment-wrapper .wrapper .content');

        // Verify a different user can see the comment
        $loginPage->logout();
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->waitForJS('return $.active == 0;', 2);
        $I->see('acceptance doc comment '.sq($I->getDocId()), '.comment-wrapper .wrapper .content');
    }

    public function upvoteOrDownvoteAsAnAnonymousUser(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->click(Locator::firstElement('.upvote'));
        $I->waitForJS('return $.active == 0;', 2);
        $I->seeCurrentUrlEquals('/login');
    }

    public function upvoteAsAnAuthenticatedUser(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $loginPage = new \Page\Login($I);
        $loginPage->loginAsRole('Editor');
        $I->amOnPage(self::$docPath.$I->getDocId());
        $upvotes = $I->grabTextFrom(Locator::firstElement('.upvote'));
        $I->click(Locator::firstElement('.upvote'));
        $I->waitForJS('return $.active == 0', 2);
        $I->see($upvotes + 1, Locator::firstElement('.upvote'));
    }

    public function downvoteAsAnAuthenticatedUser(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $loginPage = new \Page\Login($I);
        $loginPage->loginAsRole('Editor');
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->createAComment('downvote doc comment '.sq($I->getDocId()));
        $I->waitForJS('return $.active == 0', 2);
        $I->click(Locator::firstElement('.upvote'));
        $I->waitForJS('return $.active == 0', 2);
        $upvotes = $I->grabTextFrom(Locator::firstElement('.upvote'));
        $I->click(Locator::firstElement('.upvote'));
        $I->waitForJS('return $.active == 0', 2);
        $I->see($upvotes - 1, Locator::firstElement('.upvote'));
    }

    public function dontSeeCommentsInCopyItemsTab(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $loginPage = new \Page\Login($I);
        $loginPage->loginAsRole('Super User');
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->click('#rightSideCopyItemsBtn');
        $I->waitForElement('#tree2Section');
        $I->dontSeeElement('js-comments-container');
    }

    public function dontSeeCommentsInCreateAssociationsTab(AcceptanceTester $I)
    {
        $I->getLastFrameworkId();
        $loginPage = new \Page\Login($I);
        $loginPage->loginAsRole('Super User');
        $I->amOnPage(self::$docPath.$I->getDocId());
        $I->click('#rightSideCreateAssociationsBtn');
        $I->waitForElement('#tree2Section');
        $I->dontSeeElement('js-comments-container');
    }
}
