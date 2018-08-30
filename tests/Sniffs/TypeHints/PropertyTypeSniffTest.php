<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\TypeHints;

use SlevomatCodingStandard\Sniffs\TestCase;
use Traversable;

class PropertyTypeSniffTest extends TestCase
{

	public function testNoErrors(): void
	{
		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/propertyTypeNoErrors.php', [
			'traversableTypeHints' => [
				Traversable::class,
				'\QueryResultSet',
				'\FooNamespace\ClassFromCurrentNamespace',
				'\UsedNamespace\UsedClass',
				'\Doctrine\Common\Collections\ArrayCollection',
			],
		]));
	}

	public function testErrors(): void
	{
		$report = self::checkFile(__DIR__ . '/data/propertyTypeErrors.php', [
			'traversableTypeHints' => [
				Traversable::class,
				'AnyNamespace\Traversable',
				'\Doctrine\Common\Collections\ArrayCollection',
			],
		]);

		self::assertSame(10, $report->getErrorCount());

		self::assertSniffError($report, 11, PropertyTypeSniff::CODE_MISSING_TYPE_HINT);
		self::assertSniffError($report, 13, PropertyTypeSniff::CODE_MISSING_TYPE_HINT);

		self::assertSniffError($report, 15, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
		self::assertSniffError($report, 18, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
		self::assertSniffError($report, 22, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
		self::assertSniffError($report, 27, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
		self::assertSniffError($report, 32, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
		self::assertSniffError($report, 36, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
		self::assertSniffError($report, 40, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
		self::assertSniffError($report, 45, PropertyTypeSniff::CODE_MISSING_TRAVERSABLE_TYPE_HINT_SPECIFICATION);
	}

}
