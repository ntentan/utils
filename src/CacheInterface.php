<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ntentan\utils;

/**
 * Description of CacheInterface
 *
 * @author ekow
 */
interface CacheInterface 
{
    public function get($key);
    public function set($key, $value, $ttl = null);
    public function exists($key);
    public function delete($key);
}
