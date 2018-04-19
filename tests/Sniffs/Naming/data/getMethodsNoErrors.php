<?php

class Test
{

	public function getA(): int
	{
	}

	/**
	 * @return int
	 */
	public function getB()
	{
	}

	/**
	 * @return string|Foo|Bar[]
	 */
	public function getC()
	{
	}

	/**
	 * @return NullifyingObject Some messagee
	 */
	public function getD()
	{
	}

	public function blah(): ?int
	{
	}

	public function get(): ?int
	{
	}

	public function getingStarted(): ?int
	{
	}

}
