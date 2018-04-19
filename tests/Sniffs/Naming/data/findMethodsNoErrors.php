<?php

class Test
{

	public function findA()
	{
	}

	public function findB(): ?int
	{
	}

	/**
	 * @return int|null
	 */
	public function findC()
	{
	}

	/**
	 * @return string|Foo|Bar[]|null
	 */
	public function findD()
	{
	}

	/**
	 * @return Foo|null Some messagee
	 */
	public function findE()
	{
	}

	/**
	 * @return
	 */
	private function findF()
	{
	}

	public function blah(): int
	{
	}

	public function find(): int
	{
	}

	public function finder(): int
	{
	}

}
