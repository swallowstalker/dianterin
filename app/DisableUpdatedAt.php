<?php
/**
 * Created by PhpStorm.
 * User: pulung
 * Date: 4/7/16
 * Time: 6:22 PM
 */

namespace App;


trait DisableUpdatedAt
{

    public function setUpdatedAt($value)
    {
        // not setting anything here.
        return $this;
    }

    public function getUpdatedAtColumn()
    {
        return null;
    }
}