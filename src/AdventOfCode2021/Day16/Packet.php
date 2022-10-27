<?php

namespace MueR\AdventOfCode\AdventOfCode2021\Day16;

class Packet
{
    /** @param Packet[] $subPackets */
    public function __construct(
        public int $version,
        public int $type,
        public ?int $value = null,
        public array $subPackets = [],
    ) {
    }

    public function versionSum(): int
    {
        return $this->version + array_sum(array_map(
            static fn (Packet $subPacket) => $subPacket->versionSum(),
            $this->subPackets
        ));
    }

    public function value()
    {
        return match ($this->type) {
            PacketType::LITERAL => $this->value,
            PacketType::SUM => array_sum($this->subPacketValues()),
            PacketType::PROD => array_product($this->subPacketValues()),
            PacketType::MIN => min($this->subPacketValues()),
            PacketType::MAX => max($this->subPacketValues()),
            PacketType::GT => $this->subPackets[0]->value() > $this->subPackets[1]->value() ? 1 : 0,
            PacketType::EQ => $this->subPackets[0]->value() === $this->subPackets[1]->value() ? 1 : 0,
            PacketType::LT => $this->subPackets[0]->value() < $this->subPackets[1]->value() ? 1 : 0,
        };
    }

    public function subPacketValues(): array
    {
        return array_map(static fn (Packet $subPacket) => $subPacket->value(), $this->subPackets);
    }

    public function debug(int $depth = 0): void
    {
        printf("%s[V: %d, T: %d] value = %d\n", str_repeat('  ', $depth), $this->version, $this->type, $this->value);
        array_map(static fn (Packet $packet) => $packet->debug($depth + 1), $this->subPackets);
    }
}
