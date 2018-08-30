<?php

namespace FooNamespace;

use Doctrine;
use Doctrine\Common\Collections as DoctrineCollections;
use UsedNamespace\UsedClass;

abstract class FooClass
{

	/** @var bool */
	private $boolean = true;

	/** @var string[] */
	public $array = [];
	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyType
	 */
	public $boolWithGlobalSuppress = true;

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyType.MissingTypeHint
	 */
	public $boolWithSuppress = true;

	/** @var string[] */
	public $traversable = [];

	/** @var string[]|\Traversable|null */
	public $traversableWithMultipleAnnotaton = [];

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyType
	 * @var array
	 */
	public $traversableWithGlobalSuppress = [];

	/**
	 * @phpcsSuppress SlevomatCodingStandard.TypeHints.PropertyType.MissingTraversableTypeHintSpecification
	 * @var array
	 */
	public $traversableWithSuppress = [];

	/**
	 * @var bool[][]|array[]
	 */
	public $multidimensionalArray = [];

	/**
	 * @var \Traversable[]|mixed[][]
	 */
	private $multidimensionalTraversable = [];

	/**
	 * @var array[]|string[][]|null
	 */
	public $nullableMultidimensionalArray = [];

	/**
	 * @var Doctrine\Common\Collections\ArrayCollection|mixed[]
	 */
	protected $partialUseTraversable;

	/**
	 * @var DoctrineCollections\ArrayCollection|mixed[]
	 */
	protected $partialUseWithAliasTraversable;

	/**
	 * {@inheritdoc}
	 */
	public $inheritdoc;

}
