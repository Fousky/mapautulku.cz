<?php declare(strict_types = 1);

namespace App\Model\OrganizationAdd;

use App\Entity\Organization\Organization;
use App\Model\Mailer\MailerAdapter;
use App\Model\Mailer\Recipients;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class OrganizationAddMailer
{
    /** @var MailerAdapter */
    protected $adapter;

    /** @var Recipients */
    protected $recipients;

    /** @var \Twig_Environment */
    protected $twig;

    public function __construct(
        MailerAdapter $adapter,
        Recipients $recipients,
        \Twig_Environment $twig
    ) {
        $this->adapter = $adapter;
        $this->recipients = $recipients;
        $this->twig = $twig;
    }

    public function notifyOrganizationAddRecipients(Organization $organization): void
    {
        $recipients = $this->recipients->organizationAdd();

        if (count($recipients) === 0) {
            return;
        }

        $message = $this->adapter->createMessage('Nová organizace');
        $message->setTo($recipients);
        $message->setBody(
            $this->twig->render('email/notification/_organization_add.html.twig', [
                'org' => $organization,
            ]),
            'text/html',
            'UTF-8'
        );

        $this->adapter->send($message);
    }
}
