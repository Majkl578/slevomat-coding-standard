<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Naming;

class GetMethodDoesNotReturnNullSniffTest extends \SlevomatCodingStandard\Sniffs\TestCase
{

	public function testNoErrors(): void
	{
		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/getMethodsNoErrors.php'));
	}

	public function testErrors(): void
	{
		$report = self::checkFile(__DIR__ . '/data/getMethodsErrors.php');

		self::assertSame(7, $report->getErrorCount());

		self::assertSniffError($report, 6, GetMethodDoesNotReturnNullSniff::CODE_TYPE_NOT_SPECIFIED);
		self::assertSniffError($report, 10, GetMethodDoesNotReturnNullSniff::NULL_NOT_ALLOWED);
		self::assertSniffError($report, 14, GetMethodDoesNotReturnNullSniff::NULL_NOT_ALLOWED);
		self::assertSniffError($report, 21, GetMethodDoesNotReturnNullSniff::CODE_TYPE_NOT_SPECIFIED);
		self::assertSniffError($report, 26, GetMethodDoesNotReturnNullSniff::NULL_NOT_ALLOWED);
		self::assertSniffError($report, 33, GetMethodDoesNotReturnNullSniff::NULL_NOT_ALLOWED);
		self::assertSniffError($report, 40, GetMethodDoesNotReturnNullSniff::NULL_NOT_ALLOWED);
	}

}
