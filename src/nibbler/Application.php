<?php

namespace Nibbler;

use Silex\Application as SilexApplication;

/**
 * Class Application
 * @package Nibbler
 */
class Application extends SilexApplication
{
    /**
     * Gets Nibbles for a specific User
     *
     * @param $userid
     * @return array
     */
    public function getPosts($userid)
    {
        $sql = $this["pdo"]->prepare(
            "SELECT p.id, p.userid, handle, imageURL, post, stamp 
             FROM posts p
             LEFT JOIN users u ON u.userid = p.userid
             WHERE u.userid = ?
             ORDER BY stamp DESC"
         );

        $sql->Execute(array($userid));

        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Gets Nibbles for all Users 
     *
     * @return array
     */
    public function getAllPosts()
    {
        $sql = $this["pdo"]->prepare(
            "SELECT handle, imageURL, post, stamp 
             FROM posts
             LEFT JOIN users ON users.userid = posts.userid 
             ORDER BY stamp DESC"
        );

        $sql->Execute();
        return $sql->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Gets User info for a specfic User
     *
     * @param $userid
     * @return array
     */
    public function getUserInfo($userid)
    {
        $sql = $this["pdo"]->prepare(
            "SELECT imageURL, handle
             FROM users
             WHERE userid = ?"
        );

        $sql->Execute(array($userid));
        return $sql->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    /**
     * Gets Number of Nibbles for a specific User
     *
     * @param $userid
     * @return array
     */
    public function getNumNibbles($userid)
    {
        $sql = $this["pdo"]->prepare(
            "SELECT count(*) as numNibbles
             FROM posts
             WHERE userid = ?"
        );

        $sql->Execute(array($userid));
        return $sql->fetchAll(\PDO::FETCH_ASSOC)[0]['numNibbles'];
    }

    /**
     * Saves a Nibble
     *
     * @param $post
     * @param $userid
     * @return mixed
     */
    public function savePost($post, $userid)
    {
        $sql = $this["pdo"]->prepare(
            "INSERT INTO posts SET userid = ? , post = ? , stamp = ?"
        );

        return $sql->Execute(array($userid, $post, time()));
    }
}