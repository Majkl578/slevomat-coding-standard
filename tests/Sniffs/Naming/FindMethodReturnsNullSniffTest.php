<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Naming;

class FindMethodReturnsNullSniffTest extends \SlevomatCodingStandard\Sniffs\TestCase
{

	public function testNoErrors(): void
	{
		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/findMethodsNoErrors.php'));
	}

	public function testErrors(): void
	{
		$report = self::checkFile(__DIR__ . '/data/findMethodsErrors.php');

		self::assertSame(6, $report->getErrorCount());

		self::assertSniffError($report, 6, FindMethodReturnsNullSniff::NULL_REQUIRED);
		self::assertSniffError($report, 10, FindMethodReturnsNullSniff::NULL_REQUIRED);
		self::assertSniffError($report, 17, FindMethodReturnsNullSniff::NULL_REQUIRED);
		self::assertSniffError($report, 22, FindMethodReturnsNullSniff::NULL_REQUIRED);
		self::assertSniffError($report, 29, FindMethodReturnsNullSniff::NULL_REQUIRED);
		self::assertSniffError($report, 36, FindMethodReturnsNullSniff::NULL_REQUIRED);
	}

}
