<?php

namespace spec;

use PHPSpec2\ObjectBehavior;

class Account extends ObjectBehavior
{
    function it_should_be_initializable()
    {
        $this->shouldHaveType('Account');
    }
}
