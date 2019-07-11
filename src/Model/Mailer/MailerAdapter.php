<?php declare(strict_types = 1);

namespace App\Model\Mailer;

class MailerAdapter
{
    private const FROM_NAME = 'MapaUtulku.cz';
    private const FROM_EMAIL = 'info@mapautulku.cz';

    /** @var \Swift_Mailer */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function createMessage(
        ?string $subject = null,
        ?string $body = null,
        ?string $contentType = 'text/html',
        ?string $charset = 'utf8'
    ): \Swift_Message
    {
        $message = new \Swift_Message($subject, $body, $contentType, $charset);
        $message->setFrom(self::FROM_EMAIL, self::FROM_NAME);

        return $message;
    }

    public function send(\Swift_Message $message, array &$failedRecipients = []): bool
    {
        $successRecipientsCount = $this->mailer->send($message, $failedRecipients);

        return count($failedRecipients) === 0 &&
               $successRecipientsCount > 0;
    }
}
