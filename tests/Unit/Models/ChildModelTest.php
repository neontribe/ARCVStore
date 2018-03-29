<?php

namespace Tests;

use App\Family;
use App\Carer;
use App\Child;
use App\Centre;
use Carbon\Carbon;
use Config;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChildModelTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function itHasExpectedAttributes()
    {
        $child = factory(Child::class)->make();
        $this->assertNotNull($child->dob);
        $this->assertNotNull($child->born);
        $this->assertNotNull($child->entitlement);
    }

    /** @test */
    public function itHasAMethodThatCalculatesSchoolAge()
    {
    }

    /** @test */
    public function itCreditsWhenAChildIsUnderOne()
    {
        // Make a Child under one.
        $child = factory(Child::class, 'underOne')->make();
        $credits = $child->getStatus()['credits'];

        // check there's two, because child *also* under school age.
        $this->assertEquals(2, count($credits));

        // check the correct credit type is applied.
        $this->assertContains(Child::CREDIT_TYPES['ChildIsUnderOne'], $credits,'',false,false);
        $this->assertContains(Child::CREDIT_TYPES['ChildIsUnderSchoolAge'], $credits,'',false,false);
        $this->assertEquals(6, $child->entitlement);
    }

    /** @test */
    public function itCreditsWhenAChildIsUnderSchoolAge()
    {
        // Make a Child under School Age.
        $child = factory(Child::class, 'underSchoolAge')->make();
        $credits = $child->getStatus()['credits'];

        // check there's one, because child is not under one.
        $this->assertEquals(1, count($credits));

        // check the correct credit type is applied.
        $this->assertNotContains(Child::CREDIT_TYPES['ChildIsUnderOne'], $credits,'',false,false);
        $this->assertContains(Child::CREDIT_TYPES['ChildIsUnderSchoolAge'], $credits,'',false,false);
        $this->assertEquals(3, $child->entitlement);
    }

    /** @test */
    public function itDoesNotCreditWhenAChildIsOverSchoolAge()
    {
        // Make a Child under School Age.
        $child = factory(Child::class, 'overSchoolAge')->make();
        $credits = $child->getStatus()['credits'];

        // check there's one, because child is not under one.
        $this->assertEquals(0, count($credits));

        // check the correct credit type is applied.
        $this->assertNotContains(Child::CREDIT_TYPES['ChildIsUnderOne'], $credits,'',false,false);
        $this->assertNotContains(Child::CREDIT_TYPES['ChildIsUnderSchoolAge'], $credits,'',false,false);
        $this->assertEquals(0, $child->entitlement);
    }

    // Note, we do not test if a child is overdue or almost born.
    // Those rules are deactivated in Child::getStatus()

    /** @test */
    public function itNoticesWhenAChildIsAlmostOne()
    {
        // Make a Child under School Age.
        $child = factory(Child::class, 'almostOne')->make();
        $notices = $child->getStatus()['notices'];

        // check there's one, because no other event is pending.
        $this->assertEquals(1, count($notices));

        // check the correct credit type is applied.
        $this->assertContains(Child::NOTICE_TYPES['ChildIsAlmostOne'], $notices,'',false,false);
        $this->assertNotContains(Child::NOTICE_TYPES['ChildIsAlmostSchoolAge'], $notices,'',false,false);
    }

    /** @test */
    public function itNoticesWhenAChildIsAlmostSchoolAge()
    {
        // Need to change the values we use for school start to next month's integer
        $old_school_month = config('arc.school_month');
        Config::set('arc.school_month', Carbon::now()->addMonth(1)->month);


        $child = factory(Child::class, 'readyForSchool')->make();
        $notices = $child->getStatus()['notices'];

        // check there's one, because no other event is pending.
        $this->assertEquals(1, count($notices));

        // check the correct credit type is applied.
        $this->assertNotContains(Child::NOTICE_TYPES['ChildIsAlmostOne'], $notices,'',false,false);
        $this->assertContains(Child::NOTICE_TYPES['ChildIsAlmostSchoolAge'], $notices,'',false,false);

        // Set it back, just in case.
        Config::set('arc.school_month', $old_school_month);
    }
}