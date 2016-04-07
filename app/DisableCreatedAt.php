<?php
/**
 * Created by PhpStorm.
 * User: pulung
 * Date: 4/7/16
 * Time: 6:22 PM
 */

namespace App;


trait DisableCreatedAt
{

    public function getCreatedAtColumn()
    {
        return null;
    }

    public function setCreatedAt($value)
    {
        // not setting anything here.
        return $this;
    }
}