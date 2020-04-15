## Avris Bags • Nicer arrays for PHP ##

*Avris Bag* is a set of helpers that make working with arrays in PHP way easier.

### Instalation ###

Just [install Composer](https://getcomposer.org/download/) and run:

    composer require avris/bag
    
### Bag ###

*Bag* class represents a key-value store. It can be used both as an array and as an object, leading to a shorter and nicer code:

	$array = [
	    'foo' => 'bar',
	    'lorem' => [
	    	'ipsum' => ['dolor', 'sit', 'amet']
	    ]
	];
	
	$bag = new Bag($array);
	
	// all return 'bar'
	var_dump(isset($array['foo']) ? $array['foo'] : null);
	var_dump($bag->get('foo')); 
	var_dump($bag['foo']); 
	var_dump($bag('foo')); 
	
	// all return null
	var_dump(isset($array['nonexistent']) ? $array['nonexistent'] : null);
	var_dump($bag->get('nonexistent')); 
	var_dump($bag['nonexistent']); 
	var_dump($bag('nonexistent')); 
	
	// all return 'default'
	var_dump(isset($array['nonexistent']) ? $array['nonexistent'] : 'default');
	var_dump($bag->get('nonexistent', 'default')); 
	var_dump($bag['nonexistent'] ?: 'default'); 
	var_dump($bag('nonexistent', 'default')); 

	// all are equivalent
	$array['x'] => 'y';
	$bag->set('x', 'y');
	$bag['x'] = 'y';
	
	// all are equivalent
	var_dump(count($array));
	var_dump($bag->count());
	var_dump(count($bag));

	// all are equivalent
	var_dump(count($array) === 0);
	var_dump($bag->isEmpty());
	var_dump(count($bag) === 0);
	
    // all are equivalent
	var_dump(array_keys($array));
	var_dump($bag->keys());
	var_dump(array_keys($bag));

	// all are equivalent
	var_dump(isset($array['foo']));
	var_dump($bag->has('foo'));
	var_dump(isset($bag['foo']));

	// all are equivalent
    unset($array['foo']);
	$bag->delete('foo');
	unset($bag['foo']);
		
	// all are equivalent
    $array = [];
	$bag->clear();
	
Just like with a simple array, you can also **iterate** over a Bag and **json_encode** it.

**Additional features** include:

 * `$bag->getDeep($key, $default = null)` -- gets a value from a nested tree, using dot-separated string as a key, for instance `$bag->getDeep('lorem.ipsum.1')` will return 'sit';
 * `$bag->add(array)` -- merges the other array into the Bag, without overwriting the existing keys;
 * `$bag->replace(array)` -- merges the other array into the Bag, with overwriting the existing keys;
 * `$bag->appendToElement($key, $value)` -- treats `$bag[$key]` as a list and appends `$value` at its end;
 * `$bag->prependToElement($key, $value)` -- treats `$bag[$key]` as a list and prepends `$value` at its beginning;
 * `$bag->map(function ($key, $value) {})` -- returns a new Bag, with values mapped by the callback function;
 * `$bag->filter(function ($value, $key) {})` -- returns a new Bag, with only those values from the original Bag, for which the callback function returns true.
 
### Set ###

Set is a similar structure, but it doesn't care about the keys, but it makes sure that all the values are unique. Sometimes it's helpful to instantiate it with a callback:

	$set = new Set(['post', 'get', 'GET'], 'strtoupper');
	// internal values are: 'POST' and 'GET'
	
	var_dump($set->has('post')); // true
	var_dump($set->has('POST')); // true
	var_dump($set->has('DELETE')); // false
	
Methods:

* `$set->add($value)`
* `$set->addMultiple(array $values)`
* `$set->has($value)`
* `$set->delete($value)`
* `$set->first()`
* `$set->last()`
* `$set->clear()`

### BagHelper ###

This class provides four static methods:

* `BagHelper::isArray($object)` -- checks if `$object` is an array or array-like (`\Traversable`);
* `BagHelper::toArray($object)` -- converts `$object` to an array;
* `BagHelper::magicGetter($object, $attr, $default = null)` -- tries to get a value from object, using:
	* `$object['key']`,
	* `$object->key()`,
	* `$object->getKey()`,
	* `$object->key`
	* `$object->get('key')`
	* or fallbacks to `$default`.
* `BagHelper::magicSetter($object, $attr, $value)` -- tries to set a value to object, using:
	* `$object['key'] = $value`,
	* `$object->setKey($value)`,
	* `$object->key = $value`,
	* `$object->set($key, $value)`

### Nested ###

This class provides two static methods for a nested access to arrays and objects:

* `Nested::get($object, array $keys, $default = null)`,
* `Nested::set(array &$array, array $keys, $value)` (only arrays are supported).

For example:

	Nested::get($container, ['github', 'webhooks', 'foo']);
	
could return a value of:

	$container->get('github')->getWebhooks()['foo'];
	
unless any of the chain elements doesn't exist -- then `$default` is returned.

Similarly:

	Nested::set($array, ['foo', 'bar', 'baz'], 8);
	
is in equivalent to:

	$array['foo']['bar']['baz'] = 8;
	
but it creates all the arrays on the way, if they don't exist yet.

### Copyright ###

* **Author:** Andrzej Prusinowski [(Avris.it)](https://avris.it)
* **Licence:** [MIT](https://mit.avris.it)
