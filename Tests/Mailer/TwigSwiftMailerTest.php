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

use FOS\UserBundle\Mailer\TwigSwiftMailer;
use Swift_Mailer;
use Swift_Transport_NullTransport;

class TwigSwiftMailerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider goodEmailProvider
     */
    public function testSendConfirmationEmailMessageWithGoodEmails($emailAddress)
    {
        $mailer = $this->getTwigSwiftMailer();
        $mailer->sendConfirmationEmailMessage($this->getUser($emailAddress));

        $this->assertTrue(true);
    }

    /**
     * @dataProvider badEmailProvider
     */
    public function testSendConfirmationEmailMessageWithBadEmails($emailAddress)
    {
        $this->expectException(\Swift_RfcComplianceException::class);
        $mailer = $this->getTwigSwiftMailer();
        $mailer->sendConfirmationEmailMessage($this->getUser($emailAddress));
    }

    /**
     * @dataProvider goodEmailProvider
     */
    public function testSendResettingEmailMessageWithGoodEmails($emailAddress)
    {
        $mailer = $this->getTwigSwiftMailer();
        $mailer->sendResettingEmailMessage($this->getUser($emailAddress));

        $this->assertTrue(true);
    }

    /**
     * @dataProvider badEmailProvider
     */
    public function testSendResettingEmailMessageWithBadEmails($emailAddress)
    {
        $this->expectException(\Swift_RfcComplianceException::class);
        $mailer = $this->getTwigSwiftMailer();
        $mailer->sendResettingEmailMessage($this->getUser($emailAddress));
    }

    private function getTwigSwiftMailer()
    {
        return new TwigSwiftMailer(
            new Swift_Mailer(
                new Swift_Transport_NullTransport(
                    $this->getMockBuilder('Swift_Events_EventDispatcher')->getMock()
                )
            ),
            $this->getMockBuilder('Symfony\Component\Routing\Generator\UrlGeneratorInterface')->getMock(),
            $this->getTwigEnvironment(),
            array(
                'template' => array(
                    'confirmation' => 'foo',
                    'resetting' => 'foo',
                ),
                'from_email' => array(
                    'confirmation' => 'foo@example.com',
                    'resetting' => 'foo@example.com',
                ),
            )
        );
    }

    private function getTwigEnvironment()
    {
        return new \Twig_Environment(new \Twig_Loader_Array(array('foo' => <<<'TWIG'
{% block subject 'foo' %}

{% block body_text %}Test{% endblock %}

TWIG
        )));
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
        $emailAddress = $this->getMockBuilder('EmailAddress')
           ->onlyMethods(array('__toString'))
           ->getMock();

        $emailAddress->method('__toString')
            ->willReturn($emailAddressAsString)
        ;

        return $emailAddress;
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
