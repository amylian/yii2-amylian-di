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

/**
 * Yii Alias reference
 * 
 * This class can be used to specify a path containing a Yii-Alias which is
 * resolved when {@see get()} is called (i.E. by {@see Container}.
 * 
 * Yii does not have aliases ready when config files are read. Thus, using
 * <code>\Yii::getAlias('@xxx/yyy')</code> in a container definition array
 * leads to a fatal error. This can be replaced with
 * 
 * <code>\Amylian\Yii\DI\Alias::of('@xxx/yyy')</code> 
 * 
 * in container definitions.
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development <andreas.prucha@gmail.com>
 */
class Alias implements ReferenceInterface
{
    public $alias = null;
    
    public function __construct($alias)
    {
        $this->alias = $alias;
    }
    
    /**
     * Returns a instance of this class referencing the given alias
     * 
     * @param string $alias
     * @return Alias
     */
    public static function of($alias)
    {
        return new static ($alias);
    }
    
    /**
     * Returns the resolved alias
     * 
     * @param \yii\di\Container|null $container
     * @return string
     */
    public function get(?\yii\di\Container $container = null)
    {
        return \Yii::getAlias($this->alias);
    }

}
