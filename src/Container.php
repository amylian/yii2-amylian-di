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

use yii\di\Container as YiiContainer;
use yii\di\Instance;
use yii\di\NotInstantiableException;
use yii\base\InvalidConfigException;

/**
 * Description of Container
 *
 * @author Andreas Prucha, Abexto - Helicon Software Development <andreas.prucha@gmail.com>
 */
class Container
        extends YiiContainer
{
    
    /**
     * @var bool[] Set to true when {@see remapParamsDefinition()} had been called for a class
     */
    private $_paramsRemapped = [];

    /**
     * @var array Array of Array of parameter reflections.
     */
    private $_constructorParameterReflections = [];
    
    /**
     * @var \ReflectionClass Reflection of this object used to work-around visibility restrictions 
     */
    protected $_privacyHackReflection = null;
    
    /**
     *
     * @var \ReflectionProperty Reflection of private _params property. Used to work-around visibility
     *      restrictions in the original implementation
     */
    protected $_privacyHackParamsPropertyReflection = null;

    /**
     * Returns the name-indexed array of parameter reflections
     * 
     * @param \Amylian\Yii\DI\String|null $class
     * @param \ReflectionClass|null $reflection
     * @return boolean
     * @throws Exception
     */
    protected function getConstructorParameterReflections(?String $class, ?\ReflectionClass $reflection)
    {
        $result = false;
        // Determine the class name based on the reflection if class name is not specified
        if (empty($class)) {
            if ($reflection) {
                if (empty($class)) {
                    $class = $reflection->getName();
                }
            } else {
                throw new Exception('Internal Error: ' . __METHOD__ . ' Needs class name or reflection');
            }
        }
        // Check if we have an reusable entry entry and use it if possible
        If (isset($this->_constructorParameterReflections[$class])) {
            return $this->_constructorParameterReflections[$class]; // ===> RETURN reusable entry
        }
        // Create cache array entry if not already available
        if (!isset($this->_constructorParameterReflections[$class])) {
            if ($reflection || class_exists($class) || interface_exists($class)) {
                if (!$reflection) {
                    list($reflection, $dependencies) = $this->getDependencies($class);
                }
                $result = [];
                $constructorReflection = $reflection->getConstructor();
                if ($constructorReflection) {
                    foreach ($constructorReflection->getParameters() as $parameterReflection) {
                        $result[$parameterReflection->getName()] = $parameterReflection;
                    }
                }
            } else {
               $result = false;
            }
        }

        return $this->_constructorParameterReflections[$class] = $result; // Set the reusable entry and ==> RETURN
    }

    /**
     * Resolves the named constructor parameters and maps them to the position
     * 
     * Uses the reflection of the constructor of the class.
     * 
     * If <code>$reflection</code> is null, the reflection is created based on the
     * class name specified in <code>$class</class>. If <code>$reflection</code>
     * 
     * 
     * @param string $class Class name
     * @param array $params Array of parameters to map
     * @param \ReflectionClass $reflection Reflection of the class
     * @return array 
     */
    protected function mapParams(?String $class, ?\ReflectionClass $reflection, Array $params)
    {
        $result = $params;
        
        if (empty($params)) {
            return $params; // Empty Array ===> RETURN
        }

        // Map named entires to index
        // If no refelection is available (i.e. on aliases) we do not try to map and
        // just return the unchanged parameter array
        $constructorParameterReflections = $this->getConstructorParameterReflections($class, $reflection);
        if (is_array($constructorParameterReflections)) {
            foreach ($result as $index => $value) {
                if (is_string($index)) {
                    if (isset($constructorParameterReflections[$index])) {
                        unset($result[$index]);
                        $result[$constructorParameterReflections[$index]->getPosition()] = $value;
                    } else {
                        throw new InvalidConfigException("Constructor of '$class' does not have a parameter named '$index'");
                    }
                }
            }
        }
        
        return $result;
    }
    
    
    /**
     * Changes the params definition of a class from name based to index based
     * 
     * When a class is built the stored params definition is changed to index-based.
     * 
     * Implementation Background: This method changes the entry stored in params from
     * name-based to index based. This is done when {@see get()} or {@see build()} is called
     * as the original implementation of cannot handle named parameters.
     * As _params is defined private a hack using Reflection is used.
     * 
     * @param string $class
     * @return void
     */
    protected function remapParamsDefinition($class)
    {
        if (isset($this->_paramsRemapped[$class])) {
            return; // Already done ===> RETURN
        }
        
        if (!isset($this->_privacyHackReflection)) {
            $this->_privacyHackReflection = new \ReflectionClass(YiiContainer::class);
        }
        if (!isset($this->_privacyHackParamsPropertyReflection)) {
            $this->_privacyHackParamsPropertyReflection = $this->_privacyHackReflection->getProperty('_params');
            $this->_privacyHackParamsPropertyReflection->setAccessible(true);
        }
        $paramsDefinitions = $this->_privacyHackParamsPropertyReflection->getValue($this);
        
        if (isset($paramsDefinitions[$class]) && !empty($paramsDefinitions[$class])) {
            $paramsDefinitions[$class] = $this->mapParams($class, null, $paramsDefinitions[$class]);
            $this->_privacyHackParamsPropertyReflection->setValue($this, $paramsDefinitions);
            $this->_paramsRemapped[$class] = true;
        } 
    }

    /**
     * Resolves the reference (if any) and returns the value
     * 
     * This method resolves references of Type {@see Instance} and {@see ReferenceInterface}. If the passed
     * value is of another type, the value is returned unchanged.
     * 
     * If {@ee Instance::$id} is set to null, null is returned.
     * 
     * @param mixed $value
     * @return mixed Resolved value
     */
    protected function resolveDependency($value)
    {
        if (is_object($value)) {
            if ($value instanceof Instance) {
                if ($value->id !== null) {
                    return $value->get($this);
                } else {
                    return null;
                }
            } elseif ($value instanceof ReferenceInterface) {
                return$value->get($this);
            } else {
                return $value;
            }
        } else {
            return $value;
        }
    }

    /**
     * {@inheritDoc}
     * @param \ReflectionClass $reflection Reflection of class *if* called in context of paraemters, otherwise null
     */
    protected function resolveDependencies($dependencies, $reflection = null)
    {
        $result = [];

        // Resolve references in array
        foreach ($dependencies as $k => $v) {
            if ($v instanceof Instance && $v->id === null && $reflection !== null) {
                $name = $reflection->getConstructor()->getParameters()[$k]->getName();
                $class = $reflection->getName();
                throw new InvalidConfigException("Missing required parameter \"$name\" when instantiating \"$class\".");
            }
            $result[$k] = $this->resolveDependency($v);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @param string $class
     * @param array $params
     * @param array $config
     */
    public function get($class, $params = array(), $config = array())
    {
        $this->remapParamsDefinition($class);
        $params = $this->mapParams($class, null, $params);
        
        return parent::get($class, $params, $config);
    }
    
    /**
     * Creates an instance of the specified class.
     * This method will resolve dependencies of the specified class, instantiate them, and inject
     * them into the new instance of the specified class.
     * @param string $class the class name
     * @param array $params constructor parameters
     * @param array $config configurations to be applied to the new instance
     * @return object the newly created instance of the specified class
     * @throws NotInstantiableException If resolved to an abstract class or an interface (since 2.0.9)
     */
    protected function build($class, $params, $config)
    {
        // Map $params to indexes
        $this->remapParamsDefinition($class);
        $params = $this->mapParams($class, null, $params);
        
        // Remove method call config items from $config array and
        // save them for later use as the original implementation cannot handle
        // them and there is no way to hook in
        $postBuildMethodCalls = [];
        foreach ($config as $k => $v) {
            if (substr($k, -2) === '()') {
                if (!is_array($v)) {
                    throw new InvalidConfigException('Argument(s) of a setter in config must be specified in an array. ' . $class . '::' . $k . ' is of type ' . typeof($v));
                }
                $postBuildMethodCalls[$k] = $v;
                unset($config[$k]);
            }
        }

        // Use original implementation to create the object

        $result = parent::build($class, $params, $config);

        // Call the setters 

        foreach ($postBuildMethodCalls as $k => $v) {
            $method = substr($k, 0, -2);
            $callable = [$result, $method];
            $v = $this->resolveDependencies($v, null);
            call_user_func_array($callable, $v);
        }

        // Done

        return $result;
    }

}
