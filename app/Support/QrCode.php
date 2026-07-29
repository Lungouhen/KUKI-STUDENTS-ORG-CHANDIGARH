<?php

namespace App\Support;

/**
 * Minimal, dependency-free QR Code encoder rendering to inline SVG.
 *
 * Replaces the previous `chart.googleapis.com` image endpoint, which Google
 * permanently shut down in March 2024 (the ID-card QR was a broken image).
 *
 * Generating server-side means the QR works offline, requires no Composer
 * package, leaks no member IDs to a third party, and prints reliably.
 *
 * Byte mode, ECC level M, automatic version selection (1-10 => up to 213 bytes),
 * which comfortably covers a verification URL.
 *
 * Algorithm per ISO/IEC 18004. Derived from the public-domain/MIT reference
 * implementation by Kazuhiko Arase (http://www.d-project.com/, MIT licensed).
 */
final class QrCode
{
    private const PAD0 = 0xEC;
    private const PAD1 = 0x11;

    /** Total codewords and ECC-per-block for level M, versions 1-10. */
    private const RS_BLOCK_M = [
        1 => [[1, 26, 16]],
        2 => [[1, 44, 28]],
        3 => [[1, 70, 44]],
        4 => [[2, 50, 32]],
        5 => [[2, 67, 43]],
        6 => [[4, 43, 27]],
        7 => [[4, 49, 31]],
        8 => [[2, 60, 38], [2, 61, 39]],
        9 => [[3, 58, 36], [2, 59, 37]],
        10 => [[4, 69, 43], [1, 70, 44]],
    ];

    private const ALIGNMENT_PATTERN = [
        1 => [], 2 => [6, 18], 3 => [6, 22], 4 => [6, 26], 5 => [6, 30],
        6 => [6, 34], 7 => [6, 22, 38], 8 => [6, 24, 42], 9 => [6, 26, 46],
        10 => [6, 28, 50],
    ];

    private static array $expTable = [];
    private static array $logTable = [];

    private int $moduleCount;
    /** @var array<int,array<int,bool|null>> */
    private array $modules = [];

    private function __construct(
        private readonly string $data,
        private readonly int $version,
    ) {
        $this->moduleCount = $version * 4 + 17;
    }

    /**
     * Render the payload as an inline SVG string.
     *
     * @param  int  $size  Rendered width/height in pixels.
     */
    public static function svg(string $text, int $size = 100, int $quietZone = 2): string
    {
        $qr = self::encode($text);
        $count = $qr->moduleCount;
        $total = $count + $quietZone * 2;

        // Build one path for all dark modules: far smaller than one <rect> each.
        $path = '';
        for ($row = 0; $row < $count; $row++) {
            for ($col = 0; $col < $count; $col++) {
                if ($qr->modules[$row][$col] === true) {
                    $path .= 'M'.($col + $quietZone).' '.($row + $quietZone).'h1v1h-1z';
                }
            }
        }

        return sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 %d %d" '
            .'shape-rendering="crispEdges" role="img" aria-label="Verification QR code">'
            .'<rect width="%d" height="%d" fill="#ffffff"/><path d="%s" fill="#000000"/></svg>',
            $size, $size, $total, $total, $total, $total, $path
        );
    }

    /** Render as a `data:` URI, usable directly in an <img src="...">. */
    public static function dataUri(string $text, int $size = 100, int $quietZone = 2): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode(self::svg($text, $size, $quietZone));
    }

    private static function encode(string $text): self
    {
        $version = self::minimumVersion($text);
        $qr = new self($text, $version);
        $qr->make();

        return $qr;
    }

    private static function minimumVersion(string $text): int
    {
        $length = strlen($text);

        for ($version = 1; $version <= 10; $version++) {
            // Blocks are [blockCount, totalCodewords, dataCodewords]; the usable
            // payload is the sum of the *data* codewords across every block.
            $dataCodewords = 0;
            foreach (self::RS_BLOCK_M[$version] as $block) {
                $dataCodewords += $block[0] * $block[2];
            }
            // 4 bits mode indicator + 8/16 bits character count.
            $headerBits = 4 + ($version < 10 ? 8 : 16);
            if ($dataCodewords * 8 >= $headerBits + $length * 8) {
                return $version;
            }
        }

        throw new \InvalidArgumentException('QR payload too long: '.$length.' bytes (max 213).');
    }

    private function make(): void
    {
        $best = null;
        $bestPenalty = PHP_INT_MAX;

        // Try all 8 masks and keep the one with the lowest penalty score.
        for ($mask = 0; $mask < 8; $mask++) {
            $this->buildMatrix($mask);
            $penalty = $this->penaltyScore();
            if ($penalty < $bestPenalty) {
                $bestPenalty = $penalty;
                $best = $this->modules;
            }
        }

        $this->modules = $best;
    }

    private function buildMatrix(int $maskPattern): void
    {
        $this->modules = array_fill(0, $this->moduleCount, array_fill(0, $this->moduleCount, null));

        $this->setupPositionProbePattern(0, 0);
        $this->setupPositionProbePattern($this->moduleCount - 7, 0);
        $this->setupPositionProbePattern(0, $this->moduleCount - 7);
        $this->setupPositionAdjustPattern();
        $this->setupTimingPattern();
        $this->setupTypeInfo($maskPattern);

        // Versions 7 and above carry an additional 18-bit version information
        // block in two corners; without it decoders cannot resolve the symbol.
        if ($this->version >= 7) {
            $this->setupTypeNumber();
        }

        $this->mapData($this->createData(), $maskPattern);
    }

    private function setupPositionProbePattern(int $row, int $col): void
    {
        for ($r = -1; $r <= 7; $r++) {
            for ($c = -1; $c <= 7; $c++) {
                if ($row + $r <= -1 || $this->moduleCount <= $row + $r
                    || $col + $c <= -1 || $this->moduleCount <= $col + $c) {
                    continue;
                }

                $this->modules[$row + $r][$col + $c] =
                    (0 <= $r && $r <= 6 && ($c === 0 || $c === 6))
                    || (0 <= $c && $c <= 6 && ($r === 0 || $r === 6))
                    || (2 <= $r && $r <= 4 && 2 <= $c && $c <= 4);
            }
        }
    }

    private function setupPositionAdjustPattern(): void
    {
        $pos = self::ALIGNMENT_PATTERN[$this->version];

        foreach ($pos as $row) {
            foreach ($pos as $col) {
                if ($this->modules[$row][$col] !== null) {
                    continue;
                }
                for ($r = -2; $r <= 2; $r++) {
                    for ($c = -2; $c <= 2; $c++) {
                        $this->modules[$row + $r][$col + $c] =
                            $r === -2 || $r === 2 || $c === -2 || $c === 2 || ($r === 0 && $c === 0);
                    }
                }
            }
        }
    }

    private function setupTimingPattern(): void
    {
        for ($i = 8; $i < $this->moduleCount - 8; $i++) {
            if ($this->modules[$i][6] === null) {
                $this->modules[$i][6] = $i % 2 === 0;
            }
            if ($this->modules[6][$i] === null) {
                $this->modules[6][$i] = $i % 2 === 0;
            }
        }
    }

    private function setupTypeInfo(int $maskPattern): void
    {
        // ECC level M = 0b00, shifted with the mask pattern, then BCH encoded.
        $data = (0 << 3) | $maskPattern;
        $bits = $this->bchTypeInfo($data);

        for ($i = 0; $i < 15; $i++) {
            $mod = (($bits >> $i) & 1) === 1;

            if ($i < 6) {
                $this->modules[$i][8] = $mod;
            } elseif ($i < 8) {
                $this->modules[$i + 1][8] = $mod;
            } else {
                $this->modules[$this->moduleCount - 15 + $i][8] = $mod;
            }

            if ($i < 8) {
                $this->modules[8][$this->moduleCount - $i - 1] = $mod;
            } elseif ($i < 9) {
                $this->modules[8][15 - $i - 1 + 1] = $mod;
            } else {
                $this->modules[8][15 - $i - 1] = $mod;
            }
        }

        $this->modules[$this->moduleCount - 8][8] = true;
    }

    /**
     * Version information block, required for versions 7-40.
     * 6 data bits + 12 BCH(18,6) error-correction bits, written into the
     * bottom-left and top-right corners.
     */
    private function setupTypeNumber(): void
    {
        $bits = $this->bchTypeNumber($this->version);

        for ($i = 0; $i < 18; $i++) {
            $mod = (($bits >> $i) & 1) === 1;
            $this->modules[(int) floor($i / 3)][$i % 3 + $this->moduleCount - 8 - 3] = $mod;
            $this->modules[$i % 3 + $this->moduleCount - 8 - 3][(int) floor($i / 3)] = $mod;
        }
    }

    private function bchTypeNumber(int $data): int
    {
        $d = $data << 12;
        while ($this->bchDigit($d) - $this->bchDigit(0x1F25) >= 0) {
            $d ^= 0x1F25 << ($this->bchDigit($d) - $this->bchDigit(0x1F25));
        }

        return ($data << 12) | $d;
    }

    private function bchTypeInfo(int $data): int
    {
        $d = $data << 10;
        while ($this->bchDigit($d) - $this->bchDigit(0x537) >= 0) {
            $d ^= 0x537 << ($this->bchDigit($d) - $this->bchDigit(0x537));
        }

        return (($data << 10) | $d) ^ 0x5412;
    }

    private function bchDigit(int $data): int
    {
        $digit = 0;
        while ($data !== 0) {
            $digit++;
            $data >>= 1;
        }

        return $digit;
    }

    /** @return array<int,int> Final interleaved codeword stream. */
    private function createData(): array
    {
        $buffer = [];
        $length = 0;

        $put = function (int $num, int $bits) use (&$buffer, &$length): void {
            for ($i = 0; $i < $bits; $i++) {
                $bit = (($num >> ($bits - $i - 1)) & 1) === 1;
                $index = (int) floor($length / 8);
                if (count($buffer) <= $index) {
                    $buffer[] = 0;
                }
                if ($bit) {
                    $buffer[$index] |= 0x80 >> ($length % 8);
                }
                $length++;
            }
        };

        $put(4, 4); // Byte mode.
        $put(strlen($this->data), $this->version < 10 ? 8 : 16);
        foreach (str_split($this->data) as $char) {
            $put(ord($char), 8);
        }

        $rsBlocks = self::RS_BLOCK_M[$this->version];
        $totalDataCount = 0;
        foreach ($rsBlocks as $block) {
            $totalDataCount += $block[0] * $block[2];
        }

        // Terminator, byte alignment, then alternating pad codewords.
        if ($length + 4 <= $totalDataCount * 8) {
            $put(0, 4);
        }
        while ($length % 8 !== 0) {
            $put(0, 1);
        }
        while (count($buffer) < $totalDataCount) {
            $buffer[] = self::PAD0;
            if (count($buffer) >= $totalDataCount) {
                break;
            }
            $buffer[] = self::PAD1;
        }

        return $this->interleave($buffer, $rsBlocks);
    }

    /** @return array<int,int> */
    private function interleave(array $buffer, array $rsBlocks): array
    {
        $offset = 0;
        $maxDc = 0;
        $maxEc = 0;
        $dcData = [];
        $ecData = [];

        foreach ($rsBlocks as $block) {
            [$count, $totalCount, $dataCount] = [$block[0], $block[1], $block[2]];

            for ($i = 0; $i < $count; $i++) {
                $ecCount = $totalCount - $dataCount;
                $maxDc = max($maxDc, $dataCount);
                $maxEc = max($maxEc, $ecCount);

                $dc = array_slice($buffer, $offset, $dataCount);
                $offset += $dataCount;

                $dcData[] = $dc;
                $ecData[] = $this->reedSolomon($dc, $ecCount);
            }
        }

        $result = [];
        for ($i = 0; $i < $maxDc; $i++) {
            foreach ($dcData as $dc) {
                if ($i < count($dc)) {
                    $result[] = $dc[$i];
                }
            }
        }
        for ($i = 0; $i < $maxEc; $i++) {
            foreach ($ecData as $ec) {
                if ($i < count($ec)) {
                    $result[] = $ec[$i];
                }
            }
        }

        return $result;
    }

    /** @return array<int,int> */
    private function reedSolomon(array $data, int $ecCount): array
    {
        self::initTables();

        // Generator polynomial for `ecCount` error-correction codewords.
        $generator = [1];
        for ($i = 0; $i < $ecCount; $i++) {
            $generator = $this->polyMultiply($generator, [1, self::$expTable[$i]]);
        }

        $raw = array_merge($data, array_fill(0, $ecCount, 0));
        $dataLen = count($data);

        for ($i = 0; $i < $dataLen; $i++) {
            $factor = $raw[$i];
            if ($factor === 0) {
                continue;
            }
            $lead = self::$logTable[$factor];
            foreach ($generator as $j => $coefficient) {
                $raw[$i + $j] ^= self::$expTable[(self::$logTable[$coefficient] + $lead) % 255];
            }
        }

        return array_slice($raw, $dataLen, $ecCount);
    }

    /** @return array<int,int> */
    private function polyMultiply(array $a, array $b): array
    {
        $result = array_fill(0, count($a) + count($b) - 1, 0);

        foreach ($a as $i => $x) {
            foreach ($b as $j => $y) {
                if ($x === 0 || $y === 0) {
                    continue;
                }
                $result[$i + $j] ^= self::$expTable[(self::$logTable[$x] + self::$logTable[$y]) % 255];
            }
        }

        return $result;
    }

    private static function initTables(): void
    {
        if (self::$expTable !== []) {
            return;
        }

        // GF(256) with primitive polynomial x^8 + x^4 + x^3 + x^2 + 1 (0x11D).
        self::$expTable = array_fill(0, 256, 0);
        self::$logTable = array_fill(0, 256, 0);

        $x = 1;
        for ($i = 0; $i < 255; $i++) {
            self::$expTable[$i] = $x;
            self::$logTable[$x] = $i;
            $x <<= 1;
            if ($x & 0x100) {
                $x ^= 0x11D;
            }
        }
    }

    private function mapData(array $data, int $maskPattern): void
    {
        $inc = -1;
        $row = $this->moduleCount - 1;
        $bitIndex = 7;
        $byteIndex = 0;
        $dataLen = count($data);

        for ($col = $this->moduleCount - 1; $col > 0; $col -= 2) {
            if ($col === 6) {
                $col--;
            }

            while (true) {
                for ($c = 0; $c < 2; $c++) {
                    if ($this->modules[$row][$col - $c] !== null) {
                        continue;
                    }

                    $dark = false;
                    if ($byteIndex < $dataLen) {
                        $dark = ((($data[$byteIndex] >> $bitIndex) & 1) === 1);
                    }

                    if ($this->mask($maskPattern, $row, $col - $c)) {
                        $dark = ! $dark;
                    }

                    $this->modules[$row][$col - $c] = $dark;
                    $bitIndex--;

                    if ($bitIndex === -1) {
                        $byteIndex++;
                        $bitIndex = 7;
                    }
                }

                $row += $inc;
                if ($row < 0 || $this->moduleCount <= $row) {
                    $row -= $inc;
                    $inc = -$inc;
                    break;
                }
            }
        }
    }

    private function mask(int $pattern, int $i, int $j): bool
    {
        return match ($pattern) {
            0 => ($i + $j) % 2 === 0,
            1 => $i % 2 === 0,
            2 => $j % 3 === 0,
            3 => ($i + $j) % 3 === 0,
            4 => ((int) floor($i / 2) + (int) floor($j / 3)) % 2 === 0,
            5 => (($i * $j) % 2) + (($i * $j) % 3) === 0,
            6 => (((($i * $j) % 2) + (($i * $j) % 3)) % 2) === 0,
            7 => (((($i * $j) % 3) + (($i + $j) % 2)) % 2) === 0,
            default => throw new \InvalidArgumentException("Bad mask pattern: {$pattern}"),
        };
    }

    /** Standard ISO/IEC 18004 penalty rules, used to pick the best mask. */
    private function penaltyScore(): int
    {
        $n = $this->moduleCount;
        $penalty = 0;

        // Rule 1: runs of 5+ same-coloured modules in a row/column.
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $same = 0;
                $dark = $this->modules[$row][$col];

                for ($r = -1; $r <= 1; $r++) {
                    if ($row + $r < 0 || $n <= $row + $r) {
                        continue;
                    }
                    for ($c = -1; $c <= 1; $c++) {
                        if ($col + $c < 0 || $n <= $col + $c || ($r === 0 && $c === 0)) {
                            continue;
                        }
                        if ($dark === $this->modules[$row + $r][$col + $c]) {
                            $same++;
                        }
                    }
                }

                if ($same > 5) {
                    $penalty += 3 + $same - 5;
                }
            }
        }

        // Rule 2: 2x2 blocks of the same colour.
        for ($row = 0; $row < $n - 1; $row++) {
            for ($col = 0; $col < $n - 1; $col++) {
                $count = 0;
                if ($this->modules[$row][$col]) $count++;
                if ($this->modules[$row + 1][$col]) $count++;
                if ($this->modules[$row][$col + 1]) $count++;
                if ($this->modules[$row + 1][$col + 1]) $count++;
                if ($count === 0 || $count === 4) {
                    $penalty += 3;
                }
            }
        }

        // Rule 3: 1:1:3:1:1 finder-like patterns.
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n - 6; $col++) {
                if ($this->modules[$row][$col]
                    && ! $this->modules[$row][$col + 1]
                    && $this->modules[$row][$col + 2]
                    && $this->modules[$row][$col + 3]
                    && $this->modules[$row][$col + 4]
                    && ! $this->modules[$row][$col + 5]
                    && $this->modules[$row][$col + 6]) {
                    $penalty += 40;
                }
            }
        }
        for ($col = 0; $col < $n; $col++) {
            for ($row = 0; $row < $n - 6; $row++) {
                if ($this->modules[$row][$col]
                    && ! $this->modules[$row + 1][$col]
                    && $this->modules[$row + 2][$col]
                    && $this->modules[$row + 3][$col]
                    && $this->modules[$row + 4][$col]
                    && ! $this->modules[$row + 5][$col]
                    && $this->modules[$row + 6][$col]) {
                    $penalty += 40;
                }
            }
        }

        // Rule 4: deviation from a 50/50 dark/light balance.
        $darkCount = 0;
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                if ($this->modules[$row][$col]) {
                    $darkCount++;
                }
            }
        }
        $ratio = abs(100 * $darkCount / ($n * $n) - 50) / 5;
        $penalty += (int) $ratio * 10;

        return $penalty;
    }
}
