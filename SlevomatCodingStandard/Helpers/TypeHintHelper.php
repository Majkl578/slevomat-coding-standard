<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Helpers;

use PHP_CodeSniffer\Files\File;
use const T_OPEN_TAG;
use function array_key_exists;
use function array_reduce;
use function explode;
use function in_array;
use function preg_match;
use function preg_match_all;

class TypeHintHelper
{

	public static function isSimpleTypeHint(string $typeHint): bool
	{
		return in_array($typeHint, self::getSimpleTypeHints(), true);
	}

	public static function isSimpleIterableTypeHint(string $typeHint): bool
	{
		return in_array($typeHint, self::getSimpleIterableTypeHints(), true);
	}

	public static function convertLongSimpleTypeHintToShort(string $typeHint): string
	{
		$longToShort = [
			'integer' => 'int',
			'boolean' => 'bool',
		];
		return array_key_exists($typeHint, $longToShort) ? $longToShort[$typeHint] : $typeHint;
	}

	public static function getFullyQualifiedTypeHint(File $phpcsFile, int $pointer, string $typeHint): string
	{
		if (self::isSimpleTypeHint($typeHint)) {
			return self::convertLongSimpleTypeHintToShort($typeHint);
		}

		/** @var int $openTagPointer */
		$openTagPointer = TokenHelper::findPrevious($phpcsFile, T_OPEN_TAG, $pointer);
		$useStatements = UseStatementHelper::getUseStatements($phpcsFile, $openTagPointer);
		return NamespaceHelper::resolveClassName($phpcsFile, $typeHint, $useStatements, $pointer);
	}

	/**
	 * @return string[]
	 */
	public static function getSimpleTypeHints(): array
	{
		static $simpleTypeHints;

		if ($simpleTypeHints === null) {
			$simpleTypeHints = [
				'int',
				'integer',
				'float',
				'string',
				'bool',
				'boolean',
				'callable',
				'self',
				'array',
				'iterable',
				'void',
			];
		}

		return $simpleTypeHints;
	}

	/**
	 * @return string[]
	 */
	public static function getSimpleIterableTypeHints(): array
	{
		return [
			'array',
			'iterable',
		];
	}

	public static function isSimpleUnofficialTypeHints(string $typeHint): bool
	{
		static $simpleUnofficialTypeHints;

		if ($simpleUnofficialTypeHints === null) {
			$simpleUnofficialTypeHints = [
				'null',
				'mixed',
				'true',
				'false',
				'object',
				'resource',
				'static',
				'$this',
			];
		}

		return in_array($typeHint, $simpleUnofficialTypeHints, true);
	}

	/**
	 * @param string $typeHint
	 * @param string[] $traversableTypeHints
	 * @return bool
	 */
	public static function isTraversableTypeHint(string $typeHint, array $traversableTypeHints): bool
	{
		return self::isSimpleIterableTypeHint($typeHint) || in_array($typeHint, $traversableTypeHints, true);
	}

	public static function definitionContainsTraversableTypeHintSpeficication(string $typeHint): bool
	{
		return (bool) preg_match('~\[\](?=\||$)~', $typeHint);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param string $typeHint
	 * @param string[] $traversableTypeHints
	 * @return bool
	 */
	public static function definitionContainsTraversableTypeHint(
		File $phpcsFile,
		int $pointer,
		string $typeHint,
		array $traversableTypeHints
	): bool
	{
		return array_reduce(
			explode('|', $typeHint),
			function (bool $carry, string $typeHint) use ($phpcsFile, $pointer, $traversableTypeHints): bool {
				$fullyQualifiedTypeHint = TypeHintHelper::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeHint);
				return self::isTraversableTypeHint($fullyQualifiedTypeHint, $traversableTypeHints) || $carry;
			},
			false
		);
	}

	/**
	 * @param \PHP_CodeSniffer\Files\File $phpcsFile
	 * @param int $pointer
	 * @param string $typeHintDefinition
	 * @param string[] $traversableTypeHints
	 * @return bool
	 */
	public static function definitionContainsItemsSpecificationForTraversable(File $phpcsFile, int $pointer, string $typeHintDefinition, array $traversableTypeHints): bool
	{
		if (!preg_match_all('~(?<=^|\|)(.+?)\[\](?=\||$)~', $typeHintDefinition, $matches)) {
			return false;
		}

		foreach ($matches[1] as $typeHint) {
			if (self::isTraversableTypeHint(self::getFullyQualifiedTypeHint($phpcsFile, $pointer, $typeHint), $traversableTypeHints)) {
				continue;
			}

			return true;
		}

		return false;
	}

}
