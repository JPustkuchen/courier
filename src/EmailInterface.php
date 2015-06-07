<?php

/**
 * @file
 * Contains \Drupal\courier\EmailInterface.
 */

namespace Drupal\courier;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Provides an interface defining a courier_email entity.
 */
interface EmailInterface extends ContentEntityInterface {

  /**
   * Get the email address.
   *
   * @return string
   *   The email address of the recipient.
   */
  public function getEmailAddress();

  /**
   * Sets email address.
   *
   * @param string $mail
   *   The recipient email address.
   *
   * @return \Drupal\courier\EmailInterface
   *   Returns email for chaining.
   */
  public function setEmailAddress($mail);

  /**
   * Get the name of the recipient.
   *
   * @return string
   *   The name of the recipient.
   */
  public function getRecipientName();

  /**
   * Sets recipient name.
   *
   * @param string $name
   *   The recipients name.
   *
   * @return \Drupal\courier\EmailInterface
   *   Returns email for chaining.
   */
  public function setRecipientName($name);

  /**
   * Get the subject for the email.
   *
   * @return string
   *   The subject.
   */
  public function getSubject();

  /**
   * Set subject of the email.
   *
   * @param string $subject
   *   Subject of the email.
   *
   * @return \Drupal\courier\EmailInterface
   *   Returns email for chaining.
   */
  public function setSubject($subject);

  /**
   * Returns the email body.
   *
   * @return string
   *   Body of the email.
   */
  public function getBody();

  /**
   * Sets the email body.
   *
   * @param string $body
   *   The email body.
   *
   * @return \Drupal\courier\EmailInterface
   *   Returns email for chaining.
   */
  public function setBody($body);

}
