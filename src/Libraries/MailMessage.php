<?php

namespace AttractCores\LaravelCoreKit\Libraries;

use Illuminate\Notifications\Messages\MailMessage as LaravelMailMessage;
use Illuminate\Support\Facades\URL;

/**
 * Class MailMessage
 *
 * @package ArepApi\Notifications\Messages
 */
class MailMessage extends LaravelMailMessage
{

    /**
     * Contains line pattern for interactions.
     *
     * @var string
     */
    public static $linePattern = '<div class="line" style="%s">%s</div>';

    /**
     * Contains custom salutation for all messages.
     *
     * @var string|null
     */
    protected static ?string $customSalutation = NULL;

    /**
     * Contains default reply to array.
     *
     * @var array
     */
    protected static array $defaultReplyTo = [];

    /**
     * Contains subject pattern.
     *
     * @var string|null
     */
    protected static ?string $subjectPattern = NULL;

    /**
     * Contains unsub link
     *
     * @var string|null
     */
    protected ?string $unsubscribeUrl = NULL;

    /**
     * MailMessage constructor.
     */
    public function __construct()
    {
        // Preset default reply to data.
        if ( ! empty(static::$defaultReplyTo) ) {
            $this->replyTo(static::$defaultReplyTo[ 0 ], static::$defaultReplyTo[ 1 ] ?? NULL);
        }
    }

    /**
     * Add image line into message
     *
     * @param      $image
     * @param      $title
     * @param null $alt
     *
     * @return $this
     */
    public function addImage($image, $title, $alt = NULL)
    {
        $html = '<p class="center">';
        $template = '<img class="%s" src="%s" title="%s" alt="' . ( $alt ? '%s' : '%3$s' ) . '">';
        $html .= sprintf($template, 'mobile-img', getCropUrl($image, 200, 125), $title, $alt); // mobile
        $html .= sprintf($template, 'desktop-img', getCropUrl($image, 500, 315), $title, $alt); // desktop
        $html .= '</p>';

        $this->line($html);

        return $this;
    }

    /**
     * Add image line into message
     *
     * @param       $lineString
     * @param array $styles
     *
     * @return $this
     */
    public function addHrBlock($lineString, $styles = [])
    {
        $this->hrLine()
             ->breakLine()
            // note each line(not default tags - <br>, <hr>) has <br> at the end automatically.
             ->paragraph($lineString, [ 'margin-top: 0', 'margin-bottom: 0', ...$styles ])
             ->breakLine()
             ->hrLine()
             ->breakLine();

        return $this;
    }

    /**
     * Insert paragraph line.
     *
     * @param string $string
     * @param array  $styles
     *
     * @return \AttractCores\LaravelCoreKit\Libraries\MailMessage
     */
    public function paragraph(string $string, array $styles = [])
    {
        $string = sprintf('<p style="%s">%s</p>', implode(';', $styles), $string);

        return $this->line($string);
    }

    /**
     * Set customized line into email.
     *
     * @param string $string
     * @param array  $styles
     *
     * @return \AttractCores\LaravelCoreKit\Libraries\MailMessage
     */
    public function customizedLine(string $string, array $styles = [])
    {
        $string = sprintf(static::$linePattern, implode(';', $styles), $string);

        return $this->line($string);
    }

    /**
     * Insert line break.
     *
     * @param int $count
     *
     * @return \AttractCores\LaravelCoreKit\Libraries\MailMessage
     */
    public function breakLine(int $count = 1)
    {
        for ( $i = 0; $i < $count; $i++ ) {
            $this->line('<br>');
        }

        return $this;
    }

    /**
     * Insert line break.
     *
     * @param int $count
     *
     * @return \AttractCores\LaravelCoreKit\Libraries\MailMessage
     */
    public function hrLine(int $count = 1)
    {
        for ( $i = 0; $i < $count; $i++ ) {
            $this->line('<hr>');
        }

        return $this;
    }

    /**
     * Set the unsubscribe part of the mail.
     *
     * @param string $routeName
     * @param array  $parameters
     *
     * @return $this
     */
    public function addUnsubscribe(string $routeName, array $parameters = [])
    {
        $this->unsubscribeUrl = URL::temporarySignedRoute($routeName, now()->addYear(), $parameters);

        return $this;
    }

    /**
     * Get an array representation of the message.
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge([
            'unsubUrl'         => $this->unsubscribeUrl,
            'customSalutation' => self::$customSalutation,
        ], parent::toArray());
    }

    /**
     * Add custom salutation for all mails.
     *
     * @param string $salutation
     */
    public static function customSalutation(string $salutation)
    {
        static::$customSalutation = $salutation;
    }

    /**
     * Add subject pattern for all emails.
     *
     * @param string $pattern
     */
    public static function setSubjectPattern(string $pattern)
    {
        static::$subjectPattern = $pattern;
    }

    /**
     * Set default reply to into messages.
     *
     * @param      $address
     * @param NULL $name
     */
    public static function setDefaultReplyTo($address, $name = NULL)
    {
        static::$defaultReplyTo = [ $address, $name ];
    }

    /**
     * Add subject into mail.
     *
     * @param string $subject
     *
     * @return \AttractCores\LaravelCoreKit\Libraries\MailMessage
     */
    public function subject($subject)
    {
        if ( self::$subjectPattern ) {
            $subject = sprintf(self::$subjectPattern, $subject);
        }

        return parent::subject($subject);
    }

}
