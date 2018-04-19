<?php declare(strict_types = 1);

namespace SlevomatCodingStandard\Sniffs\Naming;

class HasMethodReturnsBooleanOnlySniffTest extends \SlevomatCodingStandard\Sniffs\TestCase
{

	public function testNoErrors(): void
	{
		self::assertNoSniffErrorInFile(self::checkFile(__DIR__ . '/data/hasMethodsNoErrors.php'));
	}

	public function testErrors(): void
	{
		$report = self::checkFile(__DIR__ . '/data/hasMethodsErrors.php');

		self::assertSame(7, $report->getErrorCount());

		self::assertSniffError($report, 6, HasMethodReturnsBooleanOnlySniff::CODE_TYPE_NOT_SPECIFIED);
		self::assertSniffError($report, 10, HasMethodReturnsBooleanOnlySniff::CODE_NON_BOOLEAN_ALLOWED);
		self::assertSniffError($report, 14, HasMethodReturnsBooleanOnlySniff::CODE_NON_BOOLEAN_ALLOWED);
		self::assertSniffError($report, 21, HasMethodReturnsBooleanOnlySniff::CODE_TYPE_NOT_SPECIFIED);
		self::assertSniffError($report, 26, HasMethodReturnsBooleanOnlySniff::CODE_NON_BOOLEAN_ALLOWED);
		self::assertSniffError($report, 33, HasMethodReturnsBooleanOnlySniff::CODE_NON_BOOLEAN_ALLOWED);
		self::assertSniffError($report, 40, HasMethodReturnsBooleanOnlySniff::CODE_NON_BOOLEAN_ALLOWED);
	}

}
