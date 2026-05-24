<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FOS\UserBundle\Tests\Mailer;

use FOS\UserBundle\Mailer\Mailer;
use Swift_Events_EventDispatcher;
use Swift_Mailer;
use Swift_Transport_NullTransport;

class MailerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider goodEmailProvider
     */
    public function testSendConfirmationEmailMessageWithGoodEmails($emailAddress)
    {
        $mailer = $this->getMailer();
        $mailer->sendConfirmationEmailMessage($this->getUser($emailAddress));

        $this->assertTrue(true);
    }

    /**
     * @dataProvider badEmailProvider
     */
    public function testSendConfirmationEmailMessageWithBadEmails($emailAddress)
    {
        $this->expectException(\Swift_RfcComplianceException::class);
        $mailer = $this->getMailer();
        $mailer->sendConfirmationEmailMessage($this->getUser($emailAddress));
    }

    /**
     * @dataProvider goodEmailProvider
     */
    public function testSendResettingEmailMessageWithGoodEmails($emailAddress)
    {
        $mailer = $this->getMailer();
        $mailer->sendResettingEmailMessage($this->getUser($emailAddress));

        $this->assertTrue(true);
    }

    /**
     * @dataProvider badEmailProvider
     */
    public function testSendResettingEmailMessageWithBadEmails($emailAddress)
    {
        $this->expectException(\Swift_RfcComplianceException::class);
        $mailer = $this->getMailer();
        $mailer->sendResettingEmailMessage($this->getUser($emailAddress));
    }

    private function getMailer()
    {
        return new Mailer(
            new Swift_Mailer(
                new Swift_Transport_NullTransport(
                    $this->getMockBuilder('Swift_Events_EventDispatcher')->getMock()
                )
            ),
            $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')->getMock(),
            $this->getTemplating(),
            array(
                'confirmation.template' => 'foo',
                'resetting.template' => 'foo',
                'from_email' => array(
                    'confirmation' => 'foo@example.com',
                    'resetting' => 'foo@example.com',
                ),
            )
        );
    }

    private function getTemplating()
    {
        $templating = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        return $templating;
    }

    private function getUser($emailAddress)
    {
        $user = $this->getMockBuilder('FOS\UserBundle\Model\UserInterface')->getMock();
        $user->method('getEmail')
            ->willReturn($emailAddress)
        ;

        return $user;
    }

    private static function getEmailAddressValueObject($emailAddressAsString)
    {
        return new class($emailAddressAsString) {
            private $value;

            public function __construct($value)
            {
                $this->value = $value;
            }

            public function __toString()
            {
                return $this->value;
            }
        };
    }

    public static function goodEmailProvider()
    {
        return array(
            array('foo@example.com'),
            array('foo@example.co.uk'),
            array(self::getEmailAddressValueObject('foo@example.com')),
            array(self::getEmailAddressValueObject('foo@example.co.uk')),
        );
    }

    public static function badEmailProvider()
    {
        return array(
            array('foo'),
            array(self::getEmailAddressValueObject('foo')),
        );
    }
}
