<?php

namespace Drupal\gm_adhesion;
/**
 * Provides an interface defining Statistics Storage.
 *
 * @file
 * Contains Drupal\gm_adhesion\PaypalDatabaseStorageInterface.
 * 
 * Stores the views per day, total views and timestamp of last view
 * for entities.
 * 
 * @package Drupal\gm_adhesion
 */
interface PaypalDatabaseStorageInterface {

  // /**
  //  * isFriend
  //  *
  //  * @param int $uid_original
  //  *   The ID of the original user.
  //  * @param int $uid_target
  //  *   The ID of the target user.
  //  *
  //  * @return void
  //  *   Return FALSE|array
  //  */
  // public function isFriend($uid_original, $uid_target);

  // /**
  //  * getFriendList
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  * @param int $uid
  //  * @param string $order
  //  * @param int $limit
  //  * @param int $status
  //  * 
  //  * @return void
  //  *   Return the friend list
  //  */
  // public function getFriendList($uid, $order = 'uid_target', $limit = 5, $status = 1);

  // /**
  //  * getSendFriendRequest
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  * @param int $uid
  //  * @param string $order
  //  * @param int $limit
  //  * @param int $status
  //  * 
  //  * @return void
  //  *   Return the friend list
  //  */
  // public function getSendFriendRequest($uid, $order = 'uid_target', $limit = 5, $status = 1);

  // /**
  //  * getReceiveFriendRequest
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  * @param int $uid
  //  * @param string $order
  //  * @param int $limit
  //  * @param int $status
  //  * 
  //  * @return void
  //  *   Return the friend list
  //  */
  // public function getReceiveFriendRequest($uid, $order = 'uid_target', $limit = 5, $status = 1);

  // /**
  //  * getBlockedFriendRequest.
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  *
  //  * @return void
  //  *   Return a list of users blocked
  //  */
  // public function getBlockedFriendRequest($uid);

  // /**
  //  * addFriendRequest.
  //  *
  //  * @param int $uid_original
  //  *   The ID of the original user.
  //  * @param int $uid_target
  //  *   The ID of the target user.
  //  *
  //  * @return bool
  //  *   Return TRUE or FALSE
  //  */
  // public function addFriendRequest($uid_original, $uid_target);

  // /**
  //  * acceptFriendRequest.
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  *
  //  * @return bool
  //  *   Return the status of accept request
  //  */
  // public function acceptFriendRequest($uid);

  // /**
  //  * declineFriendRequest.
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  *
  //  * @return bool
  //  *   Return the status of decline request
  //  */
  // public function declineFriendRequest($uid);

  // /**
  //  * cancelFriendRequest.
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  *
  //  * @return bool
  //  *   Return the status of canceled request
  //  */
  // public function cancelFriendRequest($uid);

  // /**
  //  * unFriend.
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  *
  //  * @return bool
  //  *   Return the status of unFriend request
  //  */
  // public function unFriend($uid);

  // /**
  //  * blockUser.
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  *
  //  * @return bool
  //  *   Return the status of blocked request
  //  */
  // public function blockUser($uid);

  // /**
  //  * unblockUser.
  //  *
  //  * @param int $uid
  //  *   The ID of the user.
  //  *
  //  * @return bool
  //  *   Return the status of unblocked request
  //  */
  // public function unblockUser($uid);


}
