<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Tests\EventListener;

use FOS\UserBundle\EventListener\FlashListener;
use FOS\UserBundle\FOSUserEvents;

class FlashListenerTest extends \PHPUnit\Framework\TestCase
{
    /** @var \stdClass */
    private $event;

    /** @var FlashListener */
    private $listener;

    public function setUp(): void
    {
        $this->event = new \stdClass();

        $flashBag = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Flash\FlashBag')->getMock();

        $session = $this->getMockBuilder('Symfony\Component\HttpFoundation\Session\Session')->getMock();
        $session
            ->expects($this->once())
            ->method('getFlashBag')
            ->willReturn($flashBag);

        $translator = $this->getMockBuilder('Symfony\Component\Translation\TranslatorInterface')->getMock();

        $this->listener = new FlashListener($session, $translator);
    }

    public function testAddSuccessFlash()
    {
        $this->listener->addSuccessFlash($this->event, FOSUserEvents::CHANGE_PASSWORD_COMPLETED);
    }

    public function testAddInfoFlash()
    {
        $this->listener->addInfoFlash($this->event, FOSUserEvents::CHANGE_PASSWORD_COMPLETED);
    }
}
