<?php

/*
 * BSD 3-Clause License
 * 
 * Copyright (c) 2019, Abexto - Helicon Software Development / Amylian Project
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * 
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * 
 * * Neither the name of the copyright holder nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 */

namespace Amylian\Yii\DI;

use yii\di\Container;
use yii\di\Instance;

/**
 * Reference to an array of references
 * 
 * {@see get()} can be used to return the array with references resolved.
 * 
 * {@see \yii\di\Instance} objects and objects implementing {@see ReferenceInterface} can be used
 * to declare references. 
 * 
 * If an item is of another type it's left unchanged.
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development <andreas.prucha@gmail.com>
 */

class InstanceArray implements ReferenceInterface
{

    private $_items = [];

    public function __construct($items)
    {
        $this->_items = $items;
    }

    /**
     * Resolves the itmes and returns the array
     * @param Container|null $container
     * @return array
     */
    public function get(?Container $container = null): Array
    {
        $result = $this->_items;
        foreach ($result as $k => $v) {
            if ($v instanceof ReferenceInterface) {
                $result[$k] = $v->get($container);
            } elseif ($v instanceof Instance) {
                $result[$k] = $v->get($container);
            }
        }
        return $result;
    }

    /**
     * Builds the instance array
     * @param array $items
     */
    public static function of(array $items)
    {
        return new static($items);
    }

}
