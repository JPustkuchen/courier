<?php

/**
 * @file
 * Contains \Drupal\courier\Entity\TemplateCollection.
 */

namespace Drupal\courier\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\courier\TemplateCollectionInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\courier\CourierContextInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\courier\ChannelInterface;

/**
 * Defines a courier_template_collection entity.
 *
 * @ContentEntityType(
 *   id = "courier_template_collection",
 *   label = @Translation("Template collection"),
 *   base_table = "courier_template_collection",
 *   entity_keys = {
 *     "id" = "id",
 *   }
 * )
 */
class TemplateCollection extends ContentEntityBase implements TemplateCollectionInterface {

  /**
   * Token values keyed by token type.
   *
   * @var array
   */
  protected $tokens = [];

  /**
   * {@inheritdoc}
   */
  function getContext() {
    return $this->get('context')->entity;
  }

  /**
   * {@inheritdoc}
   */
  function setContext(CourierContextInterface $entity) {
    $this->set('context', array('entity' => $entity));
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function getOwner() {
    return $this->get('owner')->entity;
  }

  /**
   * {@inheritdoc}
   */
  function setOwner(EntityInterface $entity) {
    $this->set('owner', array('entity' => $entity));
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function getTemplate($channel_type_id) {
    foreach ($this->getTemplates() as $template) {
      if ($template->getEntityTypeId() == $channel_type_id) {
        return $template;
      }
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  function getTemplates() {
    return $this->templates->referencedEntities();
  }

  /**
   * {@inheritdoc}
   */
  function setTemplate(ChannelInterface $template) {
    // Remove any existing templates with the same channel.
    $this->removeTemplate($template->getEntityTypeId());
    // Replace this line after https://www.drupal.org/node/2473931 :
    $this->templates[] = $template;
    //$this->templates->appendItem($template);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function removeTemplate($channel_type_id) {
    foreach ($this->getTemplates() as $key => $template) {
      if ($channel_type_id == $template->getEntityTypeId()) {
        $this->templates->removeItem($key);
      }
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function addTokenValue($token, $value) {
    $this->tokens[$token] = $value;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  function getTokenValues($token = NULL) {
    if ($token) {
      return isset($this->tokens[$token]) ? $this->tokens[$token] : NULL;
    }
    else {
      return $this->tokens;
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getTemplateCollectionForTemplate(ChannelInterface $template) {
    $ids = \Drupal::entityManager()->getStorage('courier_template_collection')
      ->getQuery()
      ->condition('templates.target_type', $template->getEntityTypeId() , '=')
      ->condition('templates.target_id', $template->id(), '=')
      ->execute();

    if ($ids) {
      return \Drupal::entityManager()->getStorage('courier_template_collection')
        ->load(reset($ids));
    }

    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Template collection ID'))
      ->setDescription(t('The template collection ID.'))
      ->setReadOnly(TRUE)
      ->setSetting('unsigned', TRUE);

    // Reference to a courier_context entity.
    // DER does not support string IDs (config entities)
    $fields['context'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Context for templates.'))
      ->setSetting('target_type', 'courier_context')
      ->setCardinality(1)
      ->setReadOnly(TRUE);

    // Owner entity, or null if it is a default template.
    $fields['owner'] = BaseFieldDefinition::create('dynamic_entity_reference')
      ->setLabel(t('Owner'))
      ->setDescription(t('The owner entity.'))
      ->setCardinality(1)
      ->setReadOnly(TRUE);

    $fields['templates'] = BaseFieldDefinition::create('dynamic_entity_reference')
      ->setLabel(t('Templates'))
      ->setDescription(t('Templates for this this collection.'))
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setReadOnly(TRUE);

    return $fields;
  }

}
