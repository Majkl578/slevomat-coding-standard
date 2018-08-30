<?php

use AnyNamespace\Anything;
use AnyNamespace\Traversable;
use Doctrine;
use Doctrine\Common\Collections as DoctrineCollections;

abstract class FooClass
{

	private $boolean = true;

	public $array = [];

	/** @var array */
	public $traversableWithUnsufficientSingleAnnotation = [];

	/** @var array|\Traversable|null */
	public $traversableWithUnsufficientMultipleAnnotation = [];

	/**
	 * @var array[]
	 */
	public $multidimensionalArray = [];

	/**
	 * @var \Traversable[]
	 */
	private $multidimensionalTraversable = [];

	/**
	 * @var array[]|null
	 */
	public $nullableMultidimensionalArray = [];

	/** @var Traversable[] */
	private $usedTraversable = [];

	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 */
	protected $partialUseTraversable;

	/**
	 * @var DoctrineCollections\ArrayCollection
	 */
	protected $partialUseWithAliasTraversable;

}
