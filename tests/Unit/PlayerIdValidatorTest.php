<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validators\PlayerIdValidator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Mobile Legends Player ID / Server ID format contract.
 */
final class PlayerIdValidatorTest extends TestCase
{
    /**
     * @param string|int $playerId
     * @param string|int $serverId
     */
    #[DataProvider('validIdProvider')]
    public function test_valid_ids_pass(
        string|int $playerId,
        string|int $serverId,
    ): void {
        $result = PlayerIdValidator::validate($playerId, $serverId);

        $this->assertTrue($result['success']);
        $this->assertIsString($result['message']);
    }

    /**
     * @return array<string, array{0: string|int, 1: string|int}>
     */
    public static function validIdProvider(): array
    {
        return [
            'string ids' => ['1234567890', '12345'],
            'integer ids' => [1234567890, 12345],
            'trimmed whitespace' => ['  1234567890  ', ' 12345 '],
            'min player / min server' => ['123456', '123'],
            'max player / max server' => ['1234567890123', '123456'],
        ];
    }

    #[DataProvider('emptyIdProvider')]
    public function test_empty_ids_fail_as_obligatorio(
        string|int $playerId,
        string|int $serverId,
    ): void {
        $result = PlayerIdValidator::validate($playerId, $serverId);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('obligatorio', $result['message']);
    }

    /**
     * @return array<string, array{0: string|int, 1: string|int}>
     */
    public static function emptyIdProvider(): array
    {
        return [
            'empty player' => ['', '12345'],
            'empty server' => ['1234567890', ''],
            'whitespace-only player' => ['   ', '12345'],
        ];
    }

    public function test_letters_in_player_fail(): void
    {
        $result = PlayerIdValidator::validate('12345abc90', '12345');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('solo números', $result['message']);
    }

    /**
     * @param string $playerId
     */
    #[DataProvider('invalidPlayerLengthProvider')]
    public function test_invalid_player_length_fails(string $playerId): void
    {
        $result = PlayerIdValidator::validate($playerId, '12345');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('longitud', $result['message']);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidPlayerLengthProvider(): array
    {
        return [
            '5 digits (too short)' => ['12345'],
            '14 digits (too long)' => ['12345678901234'],
        ];
    }

    public function test_letters_in_server_fail(): void
    {
        $result = PlayerIdValidator::validate('1234567890', '12ab5');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('solo números', $result['message']);
    }

    /**
     * @param string $serverId
     */
    #[DataProvider('invalidServerLengthProvider')]
    public function test_invalid_server_length_fails(string $serverId): void
    {
        $result = PlayerIdValidator::validate('1234567890', $serverId);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('longitud', $result['message']);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function invalidServerLengthProvider(): array
    {
        return [
            '2 digits (too short)' => ['12'],
            '7 digits (too long)' => ['1234567'],
        ];
    }
}
