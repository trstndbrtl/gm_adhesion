<?php

namespace Drupal\gm_adhesion;

use Drupal\Core\Database\Connection;
use Drupal\Core\State\StateInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides the default database storage backend for statistics.
 * 
 * @package Drupal\gm_adhesion
 */
class PaypalDatabaseStorage implements PaypalDatabaseStorageInterface {

  /**
  * The database connection used.
  *
  * @var \Drupal\Core\Database\Connection
  */
  protected $connection;

  /**
   * The state service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs the statistics storage.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection for the node view storage.
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(Connection $connection, StateInterface $state, RequestStack $request_stack) {
    $this->connection = $connection;
    $this->state = $state;
    $this->requestStack = $request_stack;
  }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function isFriend($uid_original, $uid_target) {

  //   $access = FALSE;
  //   $return_sort['original'] = [];
  //   $return_sort['target'] = [];

  //   $friends = $this->connection
  //     ->select('users_friends_relationship', 'ufr')
  //     ->fields('ufr', ['id', 'status', 'timestamp', 'uid_original', 'uid_target', 'concrete'])
  //     ->condition('uid_original', [$uid_original, $uid_target], 'IN')
  //     ->condition('uid_target', [$uid_original, $uid_target], 'IN')
  //     ->range(0, 2)
  //     ->execute()
  //     ->fetchAll();

  //   // We need 2 element to continu
  //   if (!empty($friends) && count($friends) == 2) {
  //     // Process two element
  //     foreach ($friends as $friend) {
  //       if ($uid_original == $friend->uid_original ) {$return_sort['original'] = $friend;}
  //       if ($uid_target == $friend->uid_original) { $return_sort['target'] = $friend;}
  //     }
  //     if (!empty($return_sort['original']) && !empty($return_sort['target'])) {
  //       if ($return_sort['original']->uid_original == $return_sort['target']->uid_target && $return_sort['target']->uid_original == $return_sort['original']->uid_target) {
  //         $access = $return_sort;
  //       }
  //     }
  //   }
  //   return $access;
  // }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function getFriendList($uid, $order = 'uid_target', $limit = 5, $status = 1) {
  //   assert(in_array($order, ['uid_target', 'status', 'timestamp']), "Invalid order argument.");

  //   return $this->connection
  //     ->select('users_friends_relationship', 'ufr')
  //     ->fields('ufr', ['uid_target', 'status', 'timestamp'])
  //     ->condition('uid_original', $uid)
  //     // ->orderBy($order, 'DESC')
  //     ->range(0, $limit)
  //     ->execute()
  //     ->fetchCol();
  // }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function getSendFriendRequest($uid, $order = 'uid_receiver', $limit = 5, $status = 1) {

  //   assert(in_array($order, ['uid_receiver', 'status', 'timestamp']), "Invalid order argument.");

  //   return $this->connection
  //     ->select('users_friends_relationship_request', 'ufr')
  //     ->fields('ufr', ['uid_receiver', 'status', 'timestamp'])
  //     ->condition('uid_sender', $uid)
  //     // ->orderBy($order, 'DESC')
  //     ->range(0, $limit)
  //     ->execute()
  //     ->fetchCol();

  // }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function getReceiveFriendRequest($uid, $order = 'uid_sender', $limit = 5, $status = 1) {

  //   assert(in_array($order, ['uid_sender', 'status', 'timestamp']), "Invalid order argument.");

  //   return $this->connection
  //     ->select('users_friends_relationship_request', 'ufr')
  //     ->fields('ufr', ['uid_sender', 'status', 'timestamp'])
  //     ->condition('uid_receiver', $uid)
  //     // ->orderBy($order, 'DESC')
  //     ->range(0, $limit)
  //     ->execute()
  //     ->fetchCol();

  // }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function getBlockedFriendRequest($uid) {}

  // /**
  //  * {@inheritdoc}
  //  */
  // public function addFriendRequest($uid_original, $uid_target) {
  //   return (bool) $this->connection
  //     ->merge('users_friends_relationship_request')
  //     ->key('uid_original', $uid_original)
  //     ->insertFields(array(
  //       'uid_original' => $uid_original,
  //       'uid_target' => $uid_target,
  //       'status' => 2,
  //     ))
  //     ->updateFields(array(
  //       'uid_original' => $uid_original,
  //       'uid_target' => $uid_target,
  //       'status' => 2,
  //     ))->execute();
  // }

  // /**
  //  * {@inheritdoc}
  //  */
  // public function acceptFriendRequest($uid) {}

  // /**
  //  * {@inheritdoc}
  //  */
  // public function declineFriendRequest($uid) {}

  // /**
  //  * {@inheritdoc}
  //  */
  // public function cancelFriendRequest($uid) {}

  // /**
  //  * {@inheritdoc}
  //  */
  // public function unFriend($uid) {}

  // /**
  //  * {@inheritdoc}
  //  */
  // public function blockUser($uid) {}

  // /**
  //  * {@inheritdoc}
  //  */
  // public function unblockUser($uid) {}

}
