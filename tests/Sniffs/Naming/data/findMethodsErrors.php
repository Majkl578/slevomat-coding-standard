<?php

abstract class Test
{

	public function findA(): int
	{
	}

	public function findB(): array
	{
	}

	/**
	 * @return int
	 */
	abstract public function findD();

	/**
	 * @return NullifyingObject
	 */
	public static function findE()
	{
	}

	/**
	 * @return string|int|Foo[]
	 */
	protected static function findF()
	{
	}

	/**
	 * @return null[]
	 */
	private static function findG()
	{
	}

}
