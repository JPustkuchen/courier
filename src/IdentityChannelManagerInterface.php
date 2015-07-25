<?php

/**
 * @file
 * Contains \Drupal\courier\IdentityChannelManagerInterface.
 */

namespace Drupal\courier;

use Drupal\Core\Entity\EntityInterface;

/**
 * Interface for identity channel manager.
 */
interface IdentityChannelManagerInterface {

  /**
   * Get IdentityChannel plugin ID bridging a identity and message combination.
   *
   * @param string $channel_type_id
   *   An channel entity type ID.
   * @param string $identity_type_id
   *   An identity entity type ID.
   *
   * @return string|NULL
   *   IdentityChannel plugin ID, or NULL if no plugin was found.
   */
  public function getCourierIdentityPluginID($channel_type_id, $identity_type_id);

  /**
   * Instantiate a CourierIdentity plugin instance.
   *
   * @param string $channel_type_id
   *   A channel entity type ID.
   * @param string $identity_type_id
   *   An identity entity type ID.
   *
   * @return \Drupal\courier\Plugin\IdentityChannel\IdentityChannelPluginInterface|NULL
   *   A CourierIdentity plugin instance, or NULL if no plugin was found.
   */
  public function getCourierIdentity($channel_type_id, $identity_type_id);

  /**
   * Gets all channel implementations.
   *
   * @return array
   *   Arrays of identity entity type IDs, keyed by channel entity type ID.
   */
  public function getChannels();

  /**
   * Gets all identity types.
   *
   * @return array
   *   Arrays of identity entity type IDs.
   */
  public function getIdentityTypes();

  /**
   * Get channels supported for an identity entity type.
   *
   * @param string $identity_type_id
   *   An identity entity type ID.
   *
   * @return array
   *   An array of channel entity type IDs.
   */
  public function getChannelsForIdentityType($identity_type_id);

  /**
   * Determine which channels an identity would like a message sent to.
   *
   * @param \Drupal\Core\Entity\EntityInterface $identity
   *   An identity entity.
   *
   * @return string[]
   *   IDs of entity types which implement \Drupal\courier\ChannelInterface.
   */
  public function getChannelsForIdentity(EntityInterface $identity);

  /**
   * Determine if an entity is a template entity.
   *
   * @deprecated: use instanceof ChannelInterface
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   An entity, possibly a template.
   *
   * @return bool
   *   Whether the entity is a template entity.
   */
  public function isTemplate(EntityInterface $entity);

}
