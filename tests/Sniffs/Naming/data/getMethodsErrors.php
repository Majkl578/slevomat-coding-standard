<?php

abstract class Test
{

	public function getA()
	{
	}

	protected function getB(): ?int
	{
	}

	private function getC(): ?array
	{
	}

	/**
	 * @return
	 */
	abstract public function getD();

	/**
	 * @return null
	 */
	public static function getE()
	{
	}

	/**
	 * @return string|null
	 */
	protected static function getF()
	{
	}

	/**
	 * @return string|Foo[]|null|Bar
	 */
	private static function getG()
	{
	}

}
